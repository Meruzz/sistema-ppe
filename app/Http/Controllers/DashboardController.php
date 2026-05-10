<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Bitacora;
use App\Models\Docente;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('alumno')) {
            return $this->vistaAlumno($user);
        }

        if ($user->hasRole('docente')) {
            return $this->vistaDocente($user);
        }

        return $this->vistaAdmin();
    }

    private function vistaAdmin()
    {
        $meta       = config('ppe.horas_por_anio', 80);
        $notaMinima = config('ppe.nota_minima', 7.0);
        $horasMin   = ($notaMinima / 10) * $meta;

        $stats = [
            'alumnos_activos'        => Alumno::where('activo', true)->count(),
            'docentes_activos'       => Docente::where('activo', true)->count(),
            'grupos_activos'         => Grupo::where('activo', true)->count(),
            'actividades_pendientes' => Actividad::whereIn('estado', ['planificada', 'en_curso'])->count(),
            'horas_registradas'      => round((float) DB::table('alumno_actividad')
                                                ->where('estado', 'asistio')
                                                ->sum('horas_confirmadas'), 1),
            'bitacoras_sin_revisar'  => Bitacora::whereNull('revisado_en')->count(),
            'alumnos_en_riesgo'      => DB::table('alumnos')
                                            ->where('activo', true)
                                            ->whereRaw(
                                                'COALESCE((SELECT SUM(horas_confirmadas) FROM alumno_actividad WHERE alumno_id = alumnos.id AND estado = "asistio"), 0) < ?',
                                                [$horasMin]
                                            )
                                            ->count(),
        ];

        $progresoPorAnio = Alumno::select('anio_bachillerato')
            ->selectRaw('COUNT(*) as total_alumnos')
            ->selectRaw('(SELECT COALESCE(SUM(aa.horas_confirmadas),0)
                            FROM alumno_actividad aa
                            JOIN alumnos a2 ON a2.id = aa.alumno_id
                            WHERE a2.anio_bachillerato = alumnos.anio_bachillerato
                              AND aa.estado = "asistio") as horas_total')
            ->groupBy('anio_bachillerato')
            ->get()
            ->map(function ($row) use ($meta) {
                $promedioHoras = $row->total_alumnos > 0 ? $row->horas_total / $row->total_alumnos : 0;
                $row->progreso = round(min(100, ($promedioHoras / $meta) * 100), 1);
                $row->nota_promedio = round(min(10, ($promedioHoras / $meta) * 10), 2);
                return $row;
            });

        $topRiesgo = DB::table('alumnos')
            ->selectRaw('alumnos.id, alumnos.nombres, alumnos.apellidos, alumnos.cedula,
                         alumnos.anio_bachillerato, alumnos.paralelo,
                         COALESCE((SELECT SUM(horas_confirmadas) FROM alumno_actividad
                                   WHERE alumno_id = alumnos.id AND estado = "asistio"), 0) as horas_total')
            ->where('alumnos.activo', true)
            ->orderByRaw('(SELECT COALESCE(SUM(horas_confirmadas), 0) FROM alumno_actividad
                           WHERE alumno_id = alumnos.id AND estado = "asistio") ASC')
            ->limit(8)
            ->get()
            ->filter(fn ($r) => $meta > 0 && (($r->horas_total / $meta) * 10) < $notaMinima)
            ->map(function ($r) use ($meta) {
                $r->nota    = round(min(10, ($r->horas_total / $meta) * 10), 2);
                $r->progreso = round(min(100, ($r->horas_total / $meta) * 100), 1);
                $r->nombre_completo = trim($r->nombres . ' ' . $r->apellidos);
                return $r;
            })
            ->take(5);

        $proximasActividades = Actividad::with('grupo')
            ->where('fecha', '>=', now()->toDateString())
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->limit(6)
            ->get();

        return view('dashboard.admin', compact(
            'stats', 'progresoPorAnio', 'proximasActividades', 'meta', 'topRiesgo'
        ));
    }

    private function vistaDocente($user)
    {
        $docente = $user->docente;
        $grupos  = $docente
            ? Grupo::where('docente_id', $docente->id)
                   ->where('activo', true)
                   ->with(['ambito', 'anioLectivo'])
                   ->withCount('alumnos')
                   ->get()
            : collect();

        $proximasActividades = Actividad::whereIn('grupo_id', $grupos->pluck('id'))
            ->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->limit(10)
            ->get();

        $actividadIds = Actividad::whereIn('grupo_id', $grupos->pluck('id'))->pluck('id');
        $bitacorasPendientes = Bitacora::with(['alumno', 'actividad'])
            ->whereIn('actividad_id', $actividadIds)
            ->whereNull('revisado_en')
            ->orderBy('fecha')
            ->get();

        $totalAlumnos = $grupos->sum('alumnos_count');

        return view('dashboard.docente', compact(
            'docente', 'grupos', 'proximasActividades', 'bitacorasPendientes', 'totalAlumnos'
        ));
    }

    private function vistaAlumno($user)
    {
        $alumno = $user->alumno;
        if (! $alumno) {
            return view('dashboard.alumno', [
                'alumno'                => null,
                'actividadesSinBitacora'=> collect(),
            ]);
        }

        $meta    = config('ppe.horas_por_anio', 80);
        $nota_1ro = $alumno->calificacion_1ro;
        $nota_2do = $alumno->calificacion_2do;
        $horas_1ro = $alumno->horas_completadas_1ro;
        $horas_2do = $alumno->horas_completadas_2do;
        $horasCompletadas = $alumno->horas_completadas;
        $progreso  = $alumno->progreso_horas;
        $enRiesgo  = $alumno->en_riesgo;

        $proximas = $alumno->actividades()
            ->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->limit(8)
            ->get();

        $historial = $alumno->actividades()
            ->where('alumno_actividad.estado', 'asistio')
            ->orderByDesc('fecha')
            ->limit(10)
            ->get();

        $actividadesSinBitacora = $alumno->actividades()
            ->where('alumno_actividad.estado', 'asistio')
            ->whereDoesntHave('bitacoras', fn ($q) => $q->where('alumno_id', $alumno->id))
            ->orderByDesc('fecha')
            ->get();

        return view('dashboard.alumno', compact(
            'alumno', 'meta', 'horasCompletadas', 'progreso', 'enRiesgo',
            'nota_1ro', 'nota_2do', 'horas_1ro', 'horas_2do',
            'proximas', 'historial', 'actividadesSinBitacora'
        ));
    }
}
