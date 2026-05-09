<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MateriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('administrador');
    }

    public function rules(): array
    {
        $id = $this->route('materia')?->id;

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('materias', 'nombre')->ignore($id)],
            'codigo'      => ['nullable', 'string', 'max:20', Rule::unique('materias', 'codigo')->ignore($id)],
            'descripcion' => ['nullable', 'string'],
            'activo'      => ['sometimes', 'boolean'],
        ];
    }
}
