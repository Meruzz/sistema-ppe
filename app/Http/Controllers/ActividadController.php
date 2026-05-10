<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRequest;
use App\Mail\ProgresoPPEMail;
use App\Mail\RiesgoNotaPPEMail;
use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Ambito;
use App\Models\Configuracion;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ActividadController extends Controller
{
    public function index(Request $request)
    {
        $actividades = Actividad::with(['grupo', 'ambito'])
            ->when($request->q, fn ($q, $t) => $q->where('titulo', 'like', "%{$t}%"))
            ->when($request->estado, fn ($q, $e) => $q->where('estado', $e))
            ->when($request->fase, fn ($q, $f) => $q->where('fase', $f))
            ->when($request->grupo_id, fn ($q, $g) => $q->where('grupo_id', $g))
            ->orderByDesc('fecha')
            ->paginate(15)
            ->withQueryString();

        return view('actividades.index', [
            'actividades' => $actividades,
            'grupos'      => Grupo::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        return view('actividades.create', [
            'grupos'  => Grupo::where('activo', true)->with('alumnos')->orderBy('nombre')->get(),
            'ambitos' => Ambito::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function store(ActividadRequest $request)
    {
        $actividad = DB::transaction(function () use ($request) {
            $actividad = Actividad::create($request->validated());

            foreach ($actividad->grupo->alumnos as $alumno) {
                $actividad->alumnos()->syncWithoutDetaching([
                    $alumno->id => ['estado' => 'pendiente', 'horas_confirmadas' => 0],
                ]);
            }

            return $actividad;
        });

        return redirect()->route('actividades.show', $actividad)->with('success', 'Actividad creada.');
    }

    public function show(Actividad $actividad)
    {
        $actividad->load(['grupo.docente', 'ambito', 'alumnos']);
        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad)
    {
        return view('actividades.edit', [
            'actividad' => $actividad,
            'grupos'    => Grupo::where('activo', true)->orderBy('nombre')->get(),
            'ambitos'   => Ambito::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function update(ActividadRequest $request, Actividad $actividad)
    {
        $actividad->update($request->validated());
        return redirect()->route('actividades.show', $actividad)->with('success', 'Actividad actualizada.');
    }

    public function destroy(Actividad $actividad)
    {
        $actividad->delete();
        return redirect()->route('actividades.index')->with('success', 'Actividad eliminada.');
    }

    public function asistencia(Request $request, Actividad $actividad)
    {
        $request->validate([
            'asistencia'                     => ['required', 'array'],
            'asistencia.*.estado'            => ['required', 'in:pendiente,asistio,falto,justificado'],
            'asistencia.*.horas_confirmadas' => ['nullable', 'numeric', 'min:0'],
            'asistencia.*.observaciones'     => ['nullable', 'string'],
        ]);

        $alumnosAfectados = [];

        DB::transaction(function () use ($request, $actividad, &$alumnosAfectados) {
            foreach ($request->asistencia as $alumnoId => $datos) {
                $horas = $datos['estado'] === 'asistio'
                    ? ($datos['horas_confirmadas'] ?? $actividad->horas_asignadas)
                    : 0;

                $actividad->alumnos()->updateExistingPivot($alumnoId, [
                    'estado'            => $datos['estado'],
                    'horas_confirmadas' => $horas,
                    'observaciones'     => $datos['observaciones'] ?? null,
                    'confirmado_en'     => $datos['estado'] === 'asistio' ? now() : null,
                ]);

                if ($datos['estado'] === 'asistio') {
                    $alumnosAfectados[] = (int) $alumnoId;
                }
            }
        });

        $this->enviarNotificaciones($alumnosAfectados);

        return back()->with('success', 'Asistencia registrada.');
    }

    private function enviarNotificaciones(array $alumnoIds): void
    {
        if (empty($alumnoIds)) {
            return;
        }

        $meta       = config('ppe.horas_por_anio', 80);
        $notaMinima = config('ppe.nota_minima', 7.0);

        $notif50  = Configuracion::get('email_notif_50', '1') === '1';
        $notif80  = Configuracion::get('email_notif_80', '1') === '1';
        $notif100 = Configuracion::get('email_notif_100', '1') === '1';
        $notifNota = Configuracion::get('email_notif_nota', '1') === '1';

        $alumnos = Alumno::with('user')->findMany($alumnoIds);

        foreach ($alumnos as $alumno) {
            $email = $alumno->user?->email;
            if (!$email) {
                continue;
            }

            $horas   = $alumno->horas_completadas;
            $progreso = $meta > 0 ? ($horas / $meta) * 100 : 0;
            $nota    = $meta > 0 ? min(10, round($horas / $meta * 10, 2)) : 0;

            // Hito 100%
            if ($notif100 && !$alumno->notif_100_enviada && $progreso >= 100) {
                Mail::to($email)->queue(new ProgresoPPEMail($alumno, 100, $horas, $meta));
                $alumno->update(['notif_100_enviada' => true, 'notif_80_enviada' => true, 'notif_50_enviada' => true]);
                continue;
            }

            // Hito 80%
            if ($notif80 && !$alumno->notif_80_enviada && $progreso >= 80) {
                Mail::to($email)->queue(new ProgresoPPEMail($alumno, 80, $horas, $meta));
                $alumno->update(['notif_80_enviada' => true, 'notif_50_enviada' => true]);
                continue;
            }

            // Hito 50%
            if ($notif50 && !$alumno->notif_50_enviada && $progreso >= 50) {
                Mail::to($email)->queue(new ProgresoPPEMail($alumno, 50, $horas, $meta));
                $alumno->update(['notif_50_enviada' => true]);
                continue;
            }

            // Nota en riesgo: al menos 40% del programa hecho y nota baja
            if ($notifNota && $progreso >= 40 && $nota < $notaMinima) {
                $yaNotificado = $alumno->notif_nota_baja_en
                    && $alumno->notif_nota_baja_en->diffInDays(now()) < 7;

                if (!$yaNotificado) {
                    $horasNecesarias = max(0, ($notaMinima / 10) * $meta - $horas);
                    Mail::to($email)->queue(new RiesgoNotaPPEMail($alumno, $nota, $notaMinima, $horasNecesarias));
                    $alumno->update(['notif_nota_baja_en' => now()]);
                }
            }
        }
    }
}
