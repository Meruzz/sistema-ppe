<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumnoRequest;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $alumnos = Alumno::with('user')
            ->when($request->q, function ($q, $term) {
                $q->where(function ($qq) use ($term) {
                    $qq->where('nombres', 'like', "%{$term}%")
                       ->orWhere('apellidos', 'like', "%{$term}%")
                       ->orWhere('cedula', 'like', "%{$term}%");
                });
            })
            ->when($request->anio, fn ($q) => $q->where('anio_bachillerato', $request->anio))
            ->orderBy('apellidos')
            ->paginate(15)
            ->withQueryString();

        return view('alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(AlumnoRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('alumno');

            Alumno::create([
                'user_id'                => $user->id,
                'cedula'                 => $request->cedula,
                'nombres'                => $request->nombres,
                'apellidos'              => $request->apellidos,
                'fecha_nacimiento'       => $request->fecha_nacimiento,
                'telefono'               => $request->telefono,
                'direccion'              => $request->direccion,
                'anio_bachillerato'      => $request->anio_bachillerato,
                'paralelo'               => $request->paralelo,
                'representante'          => $request->representante,
                'telefono_representante' => $request->telefono_representante,
                'activo'                 => $request->boolean('activo', true),
            ]);
        });

        return redirect()->route('alumnos.index')->with('success', 'Alumno registrado correctamente.');
    }

    public function show(Alumno $alumno)
    {
        $alumno->load(['user', 'grupos.ambito', 'convalidaciones', 'actividades' => fn ($q) => $q->orderByDesc('fecha')]);
        return view('alumnos.show', compact('alumno'));
    }

    public function edit(Alumno $alumno)
    {
        $alumno->load('user');
        return view('alumnos.edit', compact('alumno'));
    }

    public function update(AlumnoRequest $request, Alumno $alumno)
    {
        DB::transaction(function () use ($request, $alumno) {
            $data = [
                'name'  => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $alumno->user->update($data);

            $alumno->update($request->safe()->except(['name', 'email', 'password']));
        });

        return redirect()->route('alumnos.show', $alumno)->with('success', 'Alumno actualizado.');
    }

    public function destroy(Alumno $alumno)
    {
        DB::transaction(function () use ($alumno) {
            $user = $alumno->user;
            $alumno->delete();
            $user?->delete();
        });

        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado.');
    }
}
