<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrupoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['administrador', 'docente']);
    }

    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:255'],
            'docente_id'   => ['nullable', 'exists:docentes,id'],
            'materia_id'   => ['nullable', 'exists:materias,id'],
            'anio_lectivo' => ['required', 'string', 'max:9'],
            'descripcion'  => ['nullable', 'string'],
            'activo'       => ['sometimes', 'boolean'],
            'alumnos'      => ['nullable', 'array'],
            'alumnos.*'    => ['exists:alumnos,id'],
        ];
    }
}
