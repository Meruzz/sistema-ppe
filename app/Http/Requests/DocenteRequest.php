<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('administrador');
    }

    public function rules(): array
    {
        $docenteId = $this->route('docente')?->id;
        $userId    = $this->route('docente')?->user_id;

        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password'     => [$docenteId ? 'nullable' : 'required', 'string', 'min:6'],
            'cedula'       => ['required', 'string', 'size:10', Rule::unique('docentes', 'cedula')->ignore($docenteId)],
            'nombres'      => ['required', 'string', 'max:255'],
            'apellidos'    => ['required', 'string', 'max:255'],
            'especialidad' => ['nullable', 'string', 'max:255'],
            'telefono'       => ['nullable', 'string', 'max:20'],
            'activo'         => ['sometimes', 'boolean'],
            'es_coordinador' => ['sometimes', 'boolean'],
        ];
    }
}
