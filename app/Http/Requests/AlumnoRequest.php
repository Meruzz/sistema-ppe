<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('administrador');
    }

    public function rules(): array
    {
        $alumnoId = $this->route('alumno')?->id;
        $userId   = $this->route('alumno')?->user_id;

        return [
            'name'                   => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password'               => [$alumnoId ? 'nullable' : 'required', 'string', 'min:6'],
            'cedula'                 => ['required', 'string', 'size:10', Rule::unique('alumnos', 'cedula')->ignore($alumnoId)],
            'nombres'                => ['required', 'string', 'max:255'],
            'apellidos'              => ['required', 'string', 'max:255'],
            'fecha_nacimiento'       => ['nullable', 'date', 'before:today'],
            'telefono'               => ['nullable', 'string', 'max:20'],
            'direccion'              => ['nullable', 'string', 'max:255'],
            'anio_bachillerato'      => ['required', Rule::in(['1ro', '2do', '3ro'])],
            'paralelo'               => ['nullable', 'string', 'size:1'],
            'representante'          => ['nullable', 'string', 'max:255'],
            'telefono_representante' => ['nullable', 'string', 'max:20'],
            'activo'                 => ['sometimes', 'boolean'],
        ];
    }
}
