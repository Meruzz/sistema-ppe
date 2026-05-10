<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Institución
            ['clave' => 'inst_nombre',       'valor' => 'Unidad Educativa',      'descripcion' => 'Nombre de la institución',           'grupo' => 'institucion'],
            ['clave' => 'inst_ciudad',       'valor' => 'Ecuador',               'descripcion' => 'Ciudad / cantón',                    'grupo' => 'institucion'],
            ['clave' => 'inst_director',     'valor' => 'Director/a',            'descripcion' => 'Nombre del rector/a o director/a',   'grupo' => 'institucion'],
            ['clave' => 'inst_coordinador',  'valor' => 'Coordinador/a PPE',     'descripcion' => 'Nombre del coordinador/a PPE',       'grupo' => 'institucion'],

            // PDF
            ['clave' => 'pdf_firmas',        'valor' => '1',                     'descripcion' => 'Incluir bloque de firmas en PDF',    'grupo' => 'pdf'],
            ['clave' => 'pdf_actividades',   'valor' => '1',                     'descripcion' => 'Incluir detalle de actividades',     'grupo' => 'pdf'],
            ['clave' => 'pdf_pie',           'valor' => 'Documento generado por el Sistema PPE.', 'descripcion' => 'Texto del pie de página', 'grupo' => 'pdf'],

            // Email / notificaciones
            ['clave' => 'email_admin',       'valor' => '',                      'descripcion' => 'Correo destino de notificaciones admin', 'grupo' => 'email'],
            ['clave' => 'email_notif_50',    'valor' => '1',                     'descripcion' => 'Notificar al llegar al 50 % de horas',   'grupo' => 'email'],
            ['clave' => 'email_notif_80',    'valor' => '1',                     'descripcion' => 'Notificar al llegar al 80 % de horas',   'grupo' => 'email'],
            ['clave' => 'email_notif_100',   'valor' => '1',                     'descripcion' => 'Notificar al completar el programa',     'grupo' => 'email'],
            ['clave' => 'email_notif_nota',  'valor' => '1',                     'descripcion' => 'Notificar si la nota proyectada es baja', 'grupo' => 'email'],
        ];

        foreach ($defaults as $row) {
            Configuracion::firstOrCreate(['clave' => $row['clave']], $row);
        }
    }
}
