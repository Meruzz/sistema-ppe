<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnioLectivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('administrador');
    }

    public function rules(): array
    {
        $id = $this->route('anio_lectivo')?->id;

        return [
            'nombre' => ['required', 'string', 'max:9', 'regex:/^\d{4}-\d{4}$/', Rule::unique('anio_lectivos', 'nombre')->ignore($id)],
            'ciclo'  => ['required', 'in:sierra,costa'],
            'inicio' => ['nullable', 'date'],
            'fin'    => ['nullable', 'date', 'after_or_equal:inicio'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.regex' => 'El formato debe ser YYYY-YYYY (ej. 2025-2026).',
        ];
    }
}
