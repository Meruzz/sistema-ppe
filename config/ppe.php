<?php

return [
    'horas_por_anio'  => env('PPE_HORAS_POR_ANIO', 80),
    'horas_totales'   => 160,
    'nota_minima'     => 7.0,
    'max_faltas_pct'  => 10,

    'institucion'     => env('PPE_INSTITUCION', 'Unidad Educativa'),

    'anios_bachillerato' => ['1ro', '2do'],
    'paralelos'          => ['A', 'B', 'C', 'D'],

    'fases' => [
        'formacion'   => 'Formación',
        'ejecucion'   => 'Ejecución',
        'presentacion'=> 'Presentación',
    ],

    'ambitos' => [
        ['slug' => 'accion_civica',       'nombre' => 'Acción Cívica',                   'color' => 'blue'],
        ['slug' => 'salud_bienestar',     'nombre' => 'Salud y Bienestar',               'color' => 'green'],
        ['slug' => 'accion_ambiente',     'nombre' => 'Acción por el Ambiente',          'color' => 'emerald'],
        ['slug' => 'animacion_lectura',   'nombre' => 'Animación a la Lectura',          'color' => 'amber'],
        ['slug' => 'prevencion_embarazo', 'nombre' => 'Prevención del Embarazo Temprano','color' => 'rose'],
    ],
];
