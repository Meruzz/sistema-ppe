<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Alumno;
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
        $meta = config('ppe.horas_requeridas', 80);

        $stats = [
            'alumnos_activos'       => Alumno::where('activo', true)->count(),
            'docentes_activos'      => Docente::where('activo', true)->count(),
            'grupos_activos'        => Grupo::where('activo', true)->count(),
            'actividades_pendientes'=> Actividad::whereIn('estado', ['planificada', 'en_curso'])->count(),
            'horas_registradas'     => round((float) DB::table('alumno_actividad')
                                            ->where('estado', 'asistio')
                                            ->sum('horas_confirmadas'), 1),
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
                return $row;
            });

        $proximasActividades = Actividad::with('grupo')
            ->where('fecha', '>=', now()->toDateString())
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'progresoPorAnio', 'proximasActividades', 'meta'));
    }

    private function vistaDocente($user)
    {
        $docente = $user->docente;
        $grupos = $docente
            ? Grupo::where('docente_id', $docente->id)->where('activo', true)->withCount('alumnos')->get()
            : collect();

        $proximasActividades = Actividad::whereIn('grupo_id', $grupos->pluck('id'))
            ->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->limit(10)
            ->get();

        return view('dashboard.docente', compact('docente', 'grupos', 'proximasActividades'));
    }

    private function vistaAlumno($user)
    {
        $alumno = $user->alumno;
        if (! $alumno) {
            return view('dashboard.alumno', ['alumno' => null]);
        }

        $meta              = config('ppe.horas_requeridas', 80);
        $horasCompletadas  = $alumno->horas_completadas;
        $progreso          = $alumno->progreso_horas;

        $proximas = $alumno->actividades()
            ->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->limit(10)
            ->get();

        $historial = $alumno->actividades()
            ->where('alumno_actividad.estado', 'asistio')
            ->orderByDesc('fecha')
            ->limit(15)
            ->get();

        return view('dashboard.alumno', compact('alumno', 'meta', 'horasCompletadas', 'progreso', 'proximas', 'historial'));
    }
}
