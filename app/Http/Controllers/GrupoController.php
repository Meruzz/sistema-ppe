<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrupoRequest;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index(Request $request)
    {
        $grupos = Grupo::with(['docente', 'materia'])
            ->withCount('alumnos')
            ->when($request->q, fn ($q, $t) => $q->where('nombre', 'like', "%{$t}%"))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        return view('grupos.create', [
            'docentes' => Docente::where('activo', true)->orderBy('apellidos')->get(),
            'materias' => Materia::where('activo', true)->orderBy('nombre')->get(),
            'alumnos'  => Alumno::where('activo', true)->orderBy('apellidos')->get(),
        ]);
    }

    public function store(GrupoRequest $request)
    {
        $grupo = Grupo::create($request->safe()->except('alumnos'));

        if ($request->filled('alumnos')) {
            $grupo->alumnos()->sync($request->alumnos);
        }

        return redirect()->route('grupos.show', $grupo)->with('success', 'Grupo creado.');
    }

    public function show(Grupo $grupo)
    {
        $grupo->load(['docente', 'materia', 'alumnos', 'actividades' => fn ($q) => $q->orderByDesc('fecha')]);
        return view('grupos.show', compact('grupo'));
    }

    public function edit(Grupo $grupo)
    {
        return view('grupos.edit', [
            'grupo'    => $grupo->load('alumnos'),
            'docentes' => Docente::where('activo', true)->orderBy('apellidos')->get(),
            'materias' => Materia::where('activo', true)->orderBy('nombre')->get(),
            'alumnos'  => Alumno::where('activo', true)->orderBy('apellidos')->get(),
        ]);
    }

    public function update(GrupoRequest $request, Grupo $grupo)
    {
        $grupo->update($request->safe()->except('alumnos'));
        $grupo->alumnos()->sync($request->alumnos ?? []);

        return redirect()->route('grupos.show', $grupo)->with('success', 'Grupo actualizado.');
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado.');
    }
}
