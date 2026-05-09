<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    public function create()
    {
        return view('alumnos.importar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'archivo' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $path    = $request->file('archivo')->getRealPath();
        $handle  = fopen($path, 'r');
        $headers = fgetcsv($handle);

        if (! $headers) {
            return back()->with('error', 'El archivo está vacío o no es un CSV válido.');
        }

        $headers   = array_map('trim', $headers);
        $esperados = ['cedula', 'nombres', 'apellidos', 'email', 'anio_bachillerato', 'paralelo'];
        $faltantes = array_diff($esperados, $headers);

        if ($faltantes) {
            fclose($handle);
            return back()->with('error', 'Columnas faltantes: ' . implode(', ', $faltantes));
        }

        $importados = 0;
        $errores    = [];
        $fila       = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $fila++;
            $data = array_combine($headers, array_map('trim', $row));

            $v = Validator::make($data, [
                'cedula'            => ['required', 'digits:10'],
                'nombres'           => ['required', 'string'],
                'apellidos'         => ['required', 'string'],
                'email'             => ['required', 'email'],
                'anio_bachillerato' => ['required', 'in:1ro,2do'],
                'paralelo'          => ['nullable', 'string', 'max:1'],
            ]);

            if ($v->fails()) {
                $errores[] = "Fila {$fila}: " . implode(', ', $v->errors()->all());
                continue;
            }

            if (User::where('email', $data['email'])->exists() || Alumno::where('cedula', $data['cedula'])->exists()) {
                $errores[] = "Fila {$fila}: cédula o email ya existe ({$data['cedula']}).";
                continue;
            }

            try {
                DB::transaction(function () use ($data) {
                    $user = User::create([
                        'name'              => "{$data['nombres']} {$data['apellidos']}",
                        'email'             => $data['email'],
                        'password'          => Hash::make($data['cedula']),
                        'email_verified_at' => now(),
                    ]);
                    $user->assignRole('alumno');

                    Alumno::create([
                        'user_id'           => $user->id,
                        'cedula'            => $data['cedula'],
                        'nombres'           => $data['nombres'],
                        'apellidos'         => $data['apellidos'],
                        'anio_bachillerato' => $data['anio_bachillerato'],
                        'paralelo'          => $data['paralelo'] ?? null,
                        'fecha_nacimiento'  => $data['fecha_nacimiento'] ?? null,
                        'representante'     => $data['representante'] ?? null,
                        'telefono'          => $data['telefono'] ?? null,
                        'activo'            => true,
                    ]);
                });
                $importados++;
            } catch (\Throwable $e) {
                $errores[] = "Fila {$fila}: error al guardar ({$e->getMessage()}).";
            }
        }

        fclose($handle);

        $msg = "{$importados} alumno(s) importado(s) correctamente.";
        if ($errores) {
            $msg .= ' ' . count($errores) . ' fila(s) con errores.';
            session()->flash('import_errores', $errores);
        }

        return redirect()->route('alumnos.index')->with('success', $msg);
    }
}
