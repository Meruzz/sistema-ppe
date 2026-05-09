<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocenteRequest;
use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DocenteController extends Controller
{
    public function index(Request $request)
    {
        $docentes = Docente::with('user')
            ->when($request->q, function ($q, $term) {
                $q->where(function ($qq) use ($term) {
                    $qq->where('nombres', 'like', "%{$term}%")
                       ->orWhere('apellidos', 'like', "%{$term}%")
                       ->orWhere('cedula', 'like', "%{$term}%");
                });
            })
            ->orderBy('apellidos')
            ->paginate(15)
            ->withQueryString();

        return view('docentes.index', compact('docentes'));
    }

    public function create()
    {
        return view('docentes.create');
    }

    public function store(DocenteRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            $esCoordinador = $request->boolean('es_coordinador');
            $roles = $esCoordinador ? ['docente', 'coordinador_ppe'] : ['docente'];
            $user->syncRoles($roles);

            Docente::create([
                'user_id'        => $user->id,
                'cedula'         => $request->cedula,
                'nombres'        => $request->nombres,
                'apellidos'      => $request->apellidos,
                'especialidad'   => $request->especialidad,
                'telefono'       => $request->telefono,
                'activo'         => $request->boolean('activo', true),
                'es_coordinador' => $esCoordinador,
            ]);
        });

        return redirect()->route('docentes.index')->with('success', 'Docente registrado correctamente.');
    }

    public function show(Docente $docente)
    {
        $docente->load(['user', 'grupos.ambito', 'grupos.alumnos']);
        return view('docentes.show', compact('docente'));
    }

    public function edit(Docente $docente)
    {
        $docente->load('user');
        return view('docentes.edit', compact('docente'));
    }

    public function update(DocenteRequest $request, Docente $docente)
    {
        DB::transaction(function () use ($request, $docente) {
            $data = ['name' => $request->name, 'email' => $request->email];
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $docente->user->update($data);

            $esCoordinador = $request->boolean('es_coordinador');
            $roles = $esCoordinador ? ['docente', 'coordinador_ppe'] : ['docente'];
            $docente->user->syncRoles($roles);

            $docente->update($request->safe()->except(['name', 'email', 'password']));
        });

        return redirect()->route('docentes.show', $docente)->with('success', 'Docente actualizado.');
    }

    public function destroy(Docente $docente)
    {
        DB::transaction(function () use ($docente) {
            $user = $docente->user;
            $docente->delete();
            $user?->delete();
        });

        return redirect()->route('docentes.index')->with('success', 'Docente eliminado.');
    }
}
