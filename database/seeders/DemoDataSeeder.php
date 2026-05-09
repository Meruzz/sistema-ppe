<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Ambito;
use App\Models\Bitacora;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 5 ámbitos oficiales del PPE
        $ambitosData = config('ppe.ambitos');
        $ambitos = collect($ambitosData)->map(fn ($a) => Ambito::firstOrCreate(
            ['nombre' => $a['nombre']],
            ['codigo' => match($a['slug']) {
                 'accion_civica'       => 'CIV',
                 'salud_bienestar'     => 'SAL',
                 'accion_ambiente'     => 'AMB',
                 'animacion_lectura'   => 'LEC',
                 'prevencion_embarazo' => 'PRE',
                 default               => strtoupper(substr($a['slug'], 0, 3)),
             }, 'color' => $a['color'], 'activo' => true,
             'descripcion' => match($a['slug']) {
                 'accion_civica'       => 'Derechos humanos, ciudadanía activa, democracia y seguridad ciudadana.',
                 'salud_bienestar'     => 'Prevención del sedentarismo y la desnutrición crónica infantil.',
                 'accion_ambiente'     => 'Competencias para enfrentar emergencias climáticas y cuidado ambiental.',
                 'animacion_lectura'   => 'Fomento de la lectura y el arte como herramientas comunitarias.',
                 'prevencion_embarazo' => 'Educación integral en sexualidad y prevención del embarazo temprano.',
                 default               => null,
             }]
        ));

        // 5 docentes
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
                    'nombres'      => 'Docente',
                    'apellidos'    => "Apellido{$i}",
                    'especialidad' => $ambitos->random()->nombre,
                    'telefono'     => '099' . random_int(1000000, 9999999),
                    'activo'       => true,
                ]
            ));
        }

        // Grupos: 2 de 1ro y 2 de 2do (PPE solo aplica a 1ro y 2do bachillerato)
        $gruposConfig = [
            ['nombre' => '1ro A', 'anio_bachillerato' => '1ro'],
            ['nombre' => '1ro B', 'anio_bachillerato' => '1ro'],
            ['nombre' => '2do A', 'anio_bachillerato' => '2do'],
            ['nombre' => '2do B', 'anio_bachillerato' => '2do'],
            ['nombre' => '1ro C', 'anio_bachillerato' => '1ro'],
        ];

        $grupos = collect();
        foreach ($gruposConfig as $cfg) {
            $grupos->push(Grupo::firstOrCreate(
                ['nombre' => $cfg['nombre'], 'anio_lectivo' => '2025-2026'],
                [
                    'docente_id'         => $docentes->random()->id,
                    'ambito_id'          => $ambitos->random()->id,
                    'anio_bachillerato'  => $cfg['anio_bachillerato'],
                    'descripcion'        => "Grupo {$cfg['nombre']} — PPE {$cfg['anio_bachillerato']} bachillerato",
                    'activo'             => true,
                ]
            ));
        }

        // 30 alumnos (solo 1ro y 2do bachillerato)
        $aniosBach = ['1ro', '2do'];
        $alumnos   = collect();
        for ($i = 1; $i <= 30; $i++) {
            $user = User::firstOrCreate(
                ['email' => "alumno{$i}@ppe.edu.ec"],
                ['name' => "Alumno {$i}", 'password' => Hash::make('password'), 'email_verified_at' => now()]
            );
            $user->syncRoles(['alumno']);

            $anio   = $aniosBach[array_rand($aniosBach)];
            $alumno = Alumno::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'cedula'                 => '10' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'nombres'                => 'Estudiante',
                    'apellidos'              => "Apellido{$i}",
                    'fecha_nacimiento'       => now()->subYears(random_int(15, 18))->subDays(random_int(0, 365)),
                    'telefono'               => '098' . random_int(1000000, 9999999),
                    'direccion'              => 'Dirección ' . Str::random(6),
                    'anio_bachillerato'      => $anio,
                    'paralelo'               => ['A', 'B'][array_rand(['A', 'B'])],
                    'representante'          => "Representante {$i}",
                    'telefono_representante' => '097' . random_int(1000000, 9999999),
                    'activo'                 => true,
                ]
            );

            // Asignar al grupo que corresponde a su año de bachillerato
            $grupoCompatible = $grupos->where('anio_bachillerato', $anio)->random();
            $alumno->grupos()->syncWithoutDetaching([$grupoCompatible->id]);
            $alumnos->push($alumno);
        }

        // 15 actividades con las 3 fases del PPE
        $fases = ['formacion', 'ejecucion', 'presentacion'];
        for ($i = 1; $i <= 15; $i++) {
            $grupo     = $grupos->random();
            $fase      = $fases[($i - 1) % 3]; // ciclo formacion → ejecucion → presentacion
            $actividad = Actividad::firstOrCreate(
                ['titulo' => "Actividad {$i}", 'grupo_id' => $grupo->id],
                [
                    'descripcion'     => "Descripción de la actividad {$i} — fase de " . config('ppe.fases')[$fase],
                    'ambito_id'       => $grupo->ambito_id,
                    'fase'            => $fase,
                    'fecha'           => now()->subDays(random_int(0, 90))->toDateString(),
                    'hora_inicio'     => '08:00',
                    'hora_fin'        => '12:00',
                    'horas_asignadas' => random_int(2, 8),
                    'lugar'           => 'Aula ' . random_int(1, 20),
                    'estado'          => ['planificada', 'en_curso', 'completada'][array_rand(['planificada', 'en_curso', 'completada'])],
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

        // Bitácoras de ejemplo: alumnos con actividades completadas
        $contenidos = [
            'Participé en las actividades organizadas por el grupo. Trabajamos en equipo para alcanzar los objetivos planteados por el docente facilitador. La jornada fue muy enriquecedora y pude aportar desde mis conocimientos previos.',
            'Durante la actividad puse en práctica los valores de respeto y cooperación. Me sorprendió la respuesta positiva de la comunidad al ver nuestra iniciativa. Aprendí que pequeñas acciones generan grandes cambios.',
            'Hoy fue una jornada intensa pero gratificante. Colaboré con compañeros de otros paralelos y comprendí la importancia del trabajo interdisciplinario dentro del PPE.',
            'La actividad me permitió reflexionar sobre mi rol como ciudadano activo. Realizamos talleres y dinámicas grupales que fortalecieron nuestra capacidad de diálogo y escucha activa.',
            'Asistí a la sesión de formación con mucho entusiasmo. Los temas tratados están directamente relacionados con nuestra realidad local, lo que hizo la experiencia muy significativa.',
        ];
        $aprendizajes = [
            'Aprendí que el trabajo en equipo es fundamental para lograr metas comunes. Mejoraría la coordinación previa para optimizar el tiempo durante la actividad.',
            'Comprendí la importancia de la participación ciudadana desde temprana edad. Próximamente buscaría involucrar más a la familia en las actividades comunitarias.',
            'Descubrí habilidades de liderazgo que no sabía que tenía. Mejoraría mi gestión del tiempo para aprovechar mejor cada sesión.',
            'Reforcé mis conocimientos sobre el tema del ámbito asignado. En futuras actividades propongo incluir más dinámicas prácticas.',
            'La experiencia fortaleció mi sentido de pertenencia a la institución. Aprendí a escuchar diferentes perspectivas antes de tomar decisiones grupales.',
        ];

        $docentesPorId = $docentes->keyBy('id');
        $actividadesCompletadas = Actividad::where('estado', 'completada')->with('grupo.docente')->get();

        foreach ($actividadesCompletadas as $act) {
            foreach ($act->alumnos()->where('alumno_actividad.estado', 'asistio')->get() as $idx => $alumno) {
                // ~60 % de los alumnos escribe su bitácora
                if (random_int(1, 10) > 6) {
                    continue;
                }

                $revisada       = random_int(0, 1) === 1;
                $docenteRevisor = $act->grupo->docente;

                Bitacora::firstOrCreate(
                    ['alumno_id' => $alumno->id, 'actividad_id' => $act->id],
                    [
                        'fecha'                    => $act->fecha->addDays(random_int(0, 3))->toDateString(),
                        'contenido'                => $contenidos[$idx % count($contenidos)],
                        'aprendizajes'             => $aprendizajes[$idx % count($aprendizajes)],
                        'calificacion'             => $revisada ? round(random_int(70, 100) / 10, 1) : null,
                        'observaciones_docente'    => $revisada ? 'Buena reflexión. Continúa participando activamente.' : null,
                        'revisado_por_docente_id'  => $revisada ? $docenteRevisor?->id : null,
                        'revisado_en'              => $revisada ? now()->subDays(random_int(1, 10)) : null,
                    ]
                );
            }
        }
    }
}
