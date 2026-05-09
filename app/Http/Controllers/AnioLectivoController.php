<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnioLectivoRequest;
use App\Models\AnioLectivo;
use Illuminate\Http\Request;

class AnioLectivoController extends Controller
{
    public function index()
    {
        $anios = AnioLectivo::withCount('grupos')->orderByDesc('nombre')->get();
        return view('anio_lectivos.index', compact('anios'));
    }

    public function create()
    {
        return view('anio_lectivos.create');
    }

    public function store(AnioLectivoRequest $request)
    {
        $data = $request->validated();

        if (! empty($data['activo'])) {
            AnioLectivo::where('activo', true)->update(['activo' => false]);
        }

        AnioLectivo::create($data);

        return redirect()->route('anio-lectivos.index')
            ->with('success', "Año lectivo {$data['nombre']} creado correctamente.");
    }

    public function edit(AnioLectivo $anioLectivo)
    {
        return view('anio_lectivos.edit', compact('anioLectivo'));
    }

    public function update(AnioLectivoRequest $request, AnioLectivo $anioLectivo)
    {
        $data = $request->validated();

        if (! empty($data['activo']) && ! $anioLectivo->activo) {
            AnioLectivo::where('activo', true)->update(['activo' => false]);
        }

        $anioLectivo->update($data);

        return redirect()->route('anio-lectivos.index')
            ->with('success', 'Año lectivo actualizado.');
    }

    public function destroy(AnioLectivo $anioLectivo)
    {
        if ($anioLectivo->grupos()->exists()) {
            return back()->with('error', 'No se puede eliminar un año lectivo con grupos asignados.');
        }

        $anioLectivo->delete();

        return redirect()->route('anio-lectivos.index')
            ->with('success', 'Año lectivo eliminado.');
    }
}
