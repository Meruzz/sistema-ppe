@props(['alumno' => null])

@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name" class="cy-label">Nombre completo (usuario)</label>
        <input id="name" type="text" name="name" value="{{ old('name', $alumno?->user->name) }}"
               class="cy-input" autocomplete="name" required>
    </div>
    <div>
        <label for="email" class="cy-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $alumno?->user->email) }}"
               class="cy-input" autocomplete="email" inputmode="email" spellcheck="false" required>
    </div>
    <div>
        <label for="password" class="cy-label">
            Contraseña {{ $alumno ? '(opcional)' : '' }}
        </label>
        <input id="password" type="password" name="password"
               class="cy-input" autocomplete="new-password" {{ $alumno ? '' : 'required' }}>
    </div>
    <div>
        <label for="cedula" class="cy-label">Cédula</label>
        <input id="cedula" type="text" name="cedula" value="{{ old('cedula', $alumno?->cedula) }}"
               maxlength="10" inputmode="numeric" autocomplete="off" spellcheck="false"
               class="cy-input font-mono" required>
    </div>
    <div>
        <label for="nombres" class="cy-label">Nombres</label>
        <input id="nombres" type="text" name="nombres" value="{{ old('nombres', $alumno?->nombres) }}"
               class="cy-input" autocomplete="given-name" required>
    </div>
    <div>
        <label for="apellidos" class="cy-label">Apellidos</label>
        <input id="apellidos" type="text" name="apellidos" value="{{ old('apellidos', $alumno?->apellidos) }}"
               class="cy-input" autocomplete="family-name" required>
    </div>
    <div>
        <label for="fecha_nacimiento" class="cy-label">Fecha de nacimiento</label>
        <input id="fecha_nacimiento" type="date" name="fecha_nacimiento"
               value="{{ old('fecha_nacimiento', $alumno?->fecha_nacimiento?->format('Y-m-d')) }}"
               class="cy-input" autocomplete="bday">
    </div>
    <div>
        <label for="telefono" class="cy-label">Teléfono</label>
        <input id="telefono" type="tel" name="telefono" value="{{ old('telefono', $alumno?->telefono) }}"
               class="cy-input" autocomplete="tel" inputmode="tel">
    </div>
    <div>
        <label for="anio_bachillerato" class="cy-label">Año de bachillerato</label>
        <select id="anio_bachillerato" name="anio_bachillerato" class="cy-select" required>
            @foreach(['1ro','2do','3ro'] as $a)
                <option value="{{ $a }}" @selected(old('anio_bachillerato', $alumno?->anio_bachillerato) === $a)>{{ $a }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="paralelo" class="cy-label">Paralelo</label>
        <select id="paralelo" name="paralelo" class="cy-select">
            <option value="">—</option>
            @foreach(['A','B','C','D'] as $p)
                <option value="{{ $p }}" @selected(old('paralelo', $alumno?->paralelo) === $p)>{{ $p }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="direccion" class="cy-label">Dirección</label>
        <input id="direccion" type="text" name="direccion" value="{{ old('direccion', $alumno?->direccion) }}"
               class="cy-input" autocomplete="street-address">
    </div>
    <div>
        <label for="representante" class="cy-label">Representante</label>
        <input id="representante" type="text" name="representante" value="{{ old('representante', $alumno?->representante) }}"
               class="cy-input">
    </div>
    <div>
        <label for="telefono_representante" class="cy-label">Tel. representante</label>
        <input id="telefono_representante" type="tel" name="telefono_representante"
               value="{{ old('telefono_representante', $alumno?->telefono_representante) }}"
               class="cy-input" inputmode="tel">
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                   class="rounded-cy bg-transparent border-cy-border-light/30 dark:border-cy-border text-cy-yellow focus:ring-cy-yellow"
                   @checked(old('activo', $alumno?->activo ?? true))>
            <span class="ms-2 text-xs font-mono uppercase tracking-widest">Activo</span>
        </label>
    </div>
</div>

<div class="mt-8 flex justify-end gap-2">
    <a href="{{ route('alumnos.index') }}" class="cy-btn-ghost">Cancelar</a>
    <button type="submit" class="cy-btn-primary">Guardar</button>
</div>
