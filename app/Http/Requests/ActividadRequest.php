<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['administrador', 'docente']);
    }

    public function rules(): array
    {
        return [
            'titulo'          => ['required', 'string', 'max:255'],
            'descripcion'     => ['nullable', 'string'],
            'grupo_id'        => ['required', 'exists:grupos,id'],
            'ambito_id'       => ['nullable', 'exists:ambitos,id'],
            'fase'            => ['nullable', Rule::in(['formacion', 'ejecucion', 'presentacion'])],
            'fecha'           => ['required', 'date'],
            'hora_inicio'     => ['nullable', 'date_format:H:i'],
            'hora_fin'        => ['nullable', 'date_format:H:i', 'after_or_equal:hora_inicio'],
            'horas_asignadas' => ['required', 'numeric', 'min:0.5', 'max:24'],
            'lugar'           => ['nullable', 'string', 'max:255'],
            'estado'          => ['required', Rule::in(['planificada', 'en_curso', 'completada', 'cancelada'])],
        ];
    }
}
