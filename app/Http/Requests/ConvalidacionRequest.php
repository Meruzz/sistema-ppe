<?php

namespace App\Http\Requests;

use App\Models\Convalidacion;
use Illuminate\Foundation\Http\FormRequest;

class ConvalidacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('administrador');
    }

    public function rules(): array
    {
        return [
            'alumno_id'            => ['required', 'exists:alumnos,id'],
            'tipo'                 => ['required', 'in:' . implode(',', array_keys(Convalidacion::$tipos))],
            'descripcion'          => ['nullable', 'string', 'max:500'],
            'documento_referencia' => ['nullable', 'string', 'max:255'],
            'fecha_inicio'         => ['nullable', 'date'],
            'fecha_fin'            => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'activo'               => ['sometimes', 'boolean'],
        ];
    }
}
