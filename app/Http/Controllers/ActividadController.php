<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRequest;
use App\Models\Actividad;
use App\Models\Ambito;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($request, $actividad) {
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
            }
        });

        return back()->with('success', 'Asistencia registrada.');
    }
}
