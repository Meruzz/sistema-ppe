<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvalidacionRequest;
use App\Models\Alumno;
use App\Models\Convalidacion;
use Illuminate\Http\Request;

class ConvalidacionController extends Controller
{
    public function create(Request $request)
    {
        $alumno = Alumno::findOrFail($request->alumno_id);
        return view('convalidaciones.create', compact('alumno'));
    }

    public function store(ConvalidacionRequest $request)
    {
        $data               = $request->validated();
        $data['aprobado_por_id'] = $request->user()->id;

        Convalidacion::create($data);

        return redirect()->route('alumnos.show', $data['alumno_id'])
            ->with('success', 'Convalidación registrada correctamente.');
    }

    public function edit(Convalidacion $convalidacion)
    {
        return view('convalidaciones.edit', compact('convalidacion'));
    }

    public function update(ConvalidacionRequest $request, Convalidacion $convalidacion)
    {
        $convalidacion->update($request->validated());

        return redirect()->route('alumnos.show', $convalidacion->alumno_id)
            ->with('success', 'Convalidación actualizada.');
    }

    public function destroy(Convalidacion $convalidacion)
    {
        $alumnoId = $convalidacion->alumno_id;
        $convalidacion->delete();

        return redirect()->route('alumnos.show', $alumnoId)
            ->with('success', 'Convalidación eliminada.');
    }
}
