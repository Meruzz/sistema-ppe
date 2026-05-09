<?php

namespace App\Http\Controllers;

use App\Http\Requests\BitacoraRequest;
use App\Models\Actividad;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Bitacora::with(['alumno', 'actividad', 'revisor'])
            ->orderByDesc('fecha');

        if ($user->hasRole('alumno') && $user->alumno) {
            $query->where('alumno_id', $user->alumno->id);
        } elseif ($user->hasRole('docente') && $user->docente) {
            $grupoIds = $user->docente->grupos()->pluck('id');
            $actividadIds = Actividad::whereIn('grupo_id', $grupoIds)->pluck('id');
            $query->whereIn('actividad_id', $actividadIds);
        }

        $bitacoras = $query->paginate(20);

        return view('bitacoras.index', compact('bitacoras'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->hasRole('alumno'), 403);

        $alumno = $request->user()->alumno;
        abort_if(! $alumno, 403, 'Tu cuenta no está vinculada a un alumno.');

        $actividades = $alumno->actividades()
            ->where('alumno_actividad.estado', 'asistio')
            ->whereDoesntHave('bitacoras', fn ($q) => $q->where('alumno_id', $alumno->id))
            ->orderByDesc('fecha')
            ->get();

        return view('bitacoras.create', compact('alumno', 'actividades'));
    }

    public function store(BitacoraRequest $request)
    {
        abort_unless($request->user()->hasRole('alumno'), 403);

        $alumno = $request->user()->alumno;
        abort_if(! $alumno, 403);

        $alumno->bitacoras()->create($request->validated());

        return redirect()->route('bitacoras.index')
            ->with('success', 'Bitácora registrada correctamente.');
    }

    public function show(Bitacora $bitacora, Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('alumno')) {
            abort_unless($user->alumno?->id === $bitacora->alumno_id, 403);
        }

        $bitacora->load(['alumno', 'actividad.grupo', 'revisor']);

        return view('bitacoras.show', compact('bitacora'));
    }

    public function edit(Bitacora $bitacora, Request $request)
    {
        abort_unless($request->user()->hasRole('alumno'), 403);
        abort_unless($request->user()->alumno?->id === $bitacora->alumno_id, 403);
        abort_if($bitacora->revisada, 403, 'No puedes editar una bitácora ya revisada por el docente.');

        $alumno = $request->user()->alumno;
        $actividades = $alumno->actividades()
            ->where('alumno_actividad.estado', 'asistio')
            ->where(fn ($q) => $q
                ->where('actividades.id', $bitacora->actividad_id)
                ->orWhereDoesntHave('bitacoras', fn ($q2) => $q2->where('alumno_id', $alumno->id))
            )
            ->orderByDesc('fecha')
            ->get();

        return view('bitacoras.edit', compact('bitacora', 'alumno', 'actividades'));
    }

    public function update(BitacoraRequest $request, Bitacora $bitacora)
    {
        abort_unless($request->user()->hasRole('alumno'), 403);
        abort_unless($request->user()->alumno?->id === $bitacora->alumno_id, 403);
        abort_if($bitacora->revisada, 403);

        $bitacora->update($request->validated());

        return redirect()->route('bitacoras.show', $bitacora)
            ->with('success', 'Bitácora actualizada.');
    }

    public function revisar(Request $request, Bitacora $bitacora)
    {
        abort_unless($request->user()->hasAnyRole(['administrador', 'docente']), 403);

        $request->validate([
            'calificacion'          => ['required', 'numeric', 'min:0', 'max:10'],
            'observaciones_docente' => ['nullable', 'string'],
        ]);

        $docente = $request->user()->docente;

        $bitacora->update([
            'calificacion'              => $request->calificacion,
            'observaciones_docente'     => $request->observaciones_docente,
            'revisado_por_docente_id'   => $docente?->id,
            'revisado_en'               => now(),
        ]);

        return redirect()->route('bitacoras.show', $bitacora)
            ->with('success', 'Bitácora calificada correctamente.');
    }
}
