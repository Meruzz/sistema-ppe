<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Grupo;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function alumnoPdf(Alumno $alumno)
    {
        $alumno->load(['user', 'grupos.materia', 'actividades']);

        $meta              = config('ppe.horas_requeridas', 80);
        $horasCompletadas  = $alumno->horas_completadas;
        $progreso          = $alumno->progreso_horas;
        $institucion       = config('ppe.institucion');

        $pdf = Pdf::loadView('reportes.alumno', compact('alumno', 'meta', 'horasCompletadas', 'progreso', 'institucion'));

        return $pdf->stream("certificado-{$alumno->cedula}.pdf");
    }

    public function grupoPdf(Grupo $grupo)
    {
        $grupo->load(['docente', 'materia', 'alumnos']);
        $institucion = config('ppe.institucion');
        $meta        = config('ppe.horas_requeridas', 80);

        $pdf = Pdf::loadView('reportes.grupo', compact('grupo', 'institucion', 'meta'));

        return $pdf->stream("grupo-{$grupo->id}.pdf");
    }
}
