<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BitacoraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'actividad_id'  => ['nullable', 'exists:actividades,id'],
            'fecha'         => ['required', 'date'],
            'contenido'     => ['required', 'string', 'min:20'],
            'aprendizajes'  => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'contenido.required' => 'El contenido de la bitácora es obligatorio.',
            'contenido.min'      => 'El contenido debe tener al menos 20 caracteres.',
            'fecha.required'     => 'La fecha es obligatoria.',
        ];
    }
}
