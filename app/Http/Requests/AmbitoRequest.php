<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AmbitoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('administrador');
    }

    public function rules(): array
    {
        $id = $this->route('ambito')?->id;

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('ambitos', 'nombre')->ignore($id)],
            'codigo'      => ['nullable', 'string', 'max:20', Rule::unique('ambitos', 'codigo')->ignore($id)],
            'descripcion' => ['nullable', 'string'],
            'color'       => ['nullable', 'string', 'max:20'],
            'activo'      => ['sometimes', 'boolean'],
        ];
    }
}
