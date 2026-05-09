<?php

namespace App\Http\Controllers;

use App\Http\Requests\MateriaRequest;
use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index(Request $request)
    {
        $materias = Materia::when($request->q, fn ($q, $t) => $q->where('nombre', 'like', "%{$t}%"))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('materias.index', compact('materias'));
    }

    public function create()
    {
        return view('materias.create');
    }

    public function store(MateriaRequest $request)
    {
        Materia::create($request->validated());
        return redirect()->route('materias.index')->with('success', 'Materia creada.');
    }

    public function edit(Materia $materia)
    {
        return view('materias.edit', compact('materia'));
    }

    public function update(MateriaRequest $request, Materia $materia)
    {
        $materia->update($request->validated());
        return redirect()->route('materias.index')->with('success', 'Materia actualizada.');
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();
        return redirect()->route('materias.index')->with('success', 'Materia eliminada.');
    }
}
