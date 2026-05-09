<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $materias = collect([
            ['nombre' => 'Educación Ambiental', 'codigo' => 'AMB'],
            ['nombre' => 'Servicio Comunitario', 'codigo' => 'SCM'],
            ['nombre' => 'Cultura y Arte',       'codigo' => 'CYA'],
            ['nombre' => 'Deportes y Recreación','codigo' => 'DPR'],
        ])->map(fn ($m) => Materia::firstOrCreate(['nombre' => $m['nombre']], $m));

        $docentes = collect();
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "docente{$i}@ppe.edu.ec"],
                ['name' => "Docente {$i}", 'password' => Hash::make('password'), 'email_verified_at' => now()]
            );
            $user->syncRoles(['docente']);

            $docentes->push(Docente::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'cedula'       => '17' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'nombres'      => "Docente",
                    'apellidos'    => "Apellido{$i}",
                    'especialidad' => $materias->random()->nombre,
                    'telefono'     => '099' . random_int(1000000, 9999999),
                    'activo'       => true,
                ]
            ));
        }

        $grupos = collect();
        foreach (['1ro A', '1ro B', '2do A', '2do B', '3ro A'] as $i => $nombre) {
            $grupos->push(Grupo::firstOrCreate(
                ['nombre' => $nombre, 'anio_lectivo' => '2025-2026'],
                [
                    'docente_id'  => $docentes->random()->id,
                    'materia_id'  => $materias->random()->id,
                    'descripcion' => "Grupo {$nombre} para PPE",
                    'activo'      => true,
                ]
            ));
        }

        $alumnos = collect();
        for ($i = 1; $i <= 30; $i++) {
            $user = User::firstOrCreate(
                ['email' => "alumno{$i}@ppe.edu.ec"],
                ['name' => "Alumno {$i}", 'password' => Hash::make('password'), 'email_verified_at' => now()]
            );
            $user->syncRoles(['alumno']);

            $anio = ['1ro', '2do', '3ro'][array_rand(['1ro', '2do', '3ro'])];
            $alumno = Alumno::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'cedula'                => '10' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'nombres'               => "Estudiante",
                    'apellidos'             => "Apellido{$i}",
                    'fecha_nacimiento'      => now()->subYears(random_int(15, 18))->subDays(random_int(0, 365)),
                    'telefono'              => '098' . random_int(1000000, 9999999),
                    'direccion'             => "Dirección " . Str::random(6),
                    'anio_bachillerato'     => $anio,
                    'paralelo'              => ['A', 'B'][array_rand(['A', 'B'])],
                    'representante'         => "Representante {$i}",
                    'telefono_representante'=> '097' . random_int(1000000, 9999999),
                    'activo'                => true,
                ]
            );

            $alumno->grupos()->syncWithoutDetaching([$grupos->random()->id]);
            $alumnos->push($alumno);
        }

        for ($i = 1; $i <= 15; $i++) {
            $grupo = $grupos->random();
            $actividad = Actividad::firstOrCreate(
                ['titulo' => "Actividad {$i}", 'grupo_id' => $grupo->id],
                [
                    'descripcion'      => "Descripción de la actividad {$i}",
                    'materia_id'       => $grupo->materia_id,
                    'fecha'            => now()->subDays(random_int(0, 90))->toDateString(),
                    'hora_inicio'      => '08:00',
                    'hora_fin'         => '12:00',
                    'horas_asignadas'  => random_int(2, 8),
                    'lugar'            => "Aula " . random_int(1, 20),
                    'estado'           => ['planificada', 'en_curso', 'completada'][array_rand(['planificada', 'en_curso', 'completada'])],
                ]
            );

            foreach ($grupo->alumnos as $alumno) {
                $actividad->alumnos()->syncWithoutDetaching([
                    $alumno->id => [
                        'horas_confirmadas' => $actividad->estado === 'completada' ? $actividad->horas_asignadas : 0,
                        'estado'            => $actividad->estado === 'completada' ? 'asistio' : 'pendiente',
                        'confirmado_en'     => $actividad->estado === 'completada' ? now() : null,
                    ],
                ]);
            }
        }
    }
}
