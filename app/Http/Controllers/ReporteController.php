<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Configuracion;
use App\Models\Grupo;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function alumnoPdf(Alumno $alumno)
    {
        $alumno->load(['user', 'grupos.ambito', 'actividades.grupo']);

        $meta             = config('ppe.horas_por_anio', 80);
        $horasCompletadas = $alumno->horas_completadas;
        $progreso         = $alumno->progreso_horas;
        $nota             = $meta > 0 ? round(min($horasCompletadas / $meta, 1) * 10, 2) : 0;

        $cfg = [
            'inst_nombre'      => Configuracion::get('inst_nombre', config('ppe.institucion')),
            'inst_ciudad'      => Configuracion::get('inst_ciudad', 'Ecuador'),
            'inst_director'    => Configuracion::get('inst_director', 'Director/a'),
            'inst_coordinador' => Configuracion::get('inst_coordinador', 'Coordinador/a PPE'),
            'pdf_firmas'       => Configuracion::get('pdf_firmas', '1') === '1',
            'pdf_actividades'  => Configuracion::get('pdf_actividades', '1') === '1',
            'pdf_pie'          => Configuracion::get('pdf_pie', 'Documento generado por el Sistema PPE.'),
        ];

        $pdf = Pdf::loadView('reportes.alumno', compact('alumno', 'meta', 'horasCompletadas', 'progreso', 'nota', 'cfg'));

        return $pdf->stream("certificado-{$alumno->cedula}.pdf");
    }

    public function grupoPdf(Grupo $grupo)
    {
        $grupo->load(['docente', 'ambito', 'anioLectivo', 'alumnos']);

        $meta = config('ppe.horas_por_anio', 80);

        $cfg = [
            'inst_nombre'      => Configuracion::get('inst_nombre', config('ppe.institucion')),
            'inst_ciudad'      => Configuracion::get('inst_ciudad', 'Ecuador'),
            'inst_director'    => Configuracion::get('inst_director', 'Director/a'),
            'inst_coordinador' => Configuracion::get('inst_coordinador', 'Coordinador/a PPE'),
            'pdf_firmas'       => Configuracion::get('pdf_firmas', '1') === '1',
            'pdf_pie'          => Configuracion::get('pdf_pie', 'Documento generado por el Sistema PPE.'),
        ];

        $pdf = Pdf::loadView('reportes.grupo', compact('grupo', 'meta', 'cfg'));

        return $pdf->stream("grupo-{$grupo->id}.pdf");
    }
}
