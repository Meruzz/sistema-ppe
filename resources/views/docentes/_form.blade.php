@props(['docente' => null])
@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name" class="cy-label">Nombre completo (usuario)</label>
        <input id="name" type="text" name="name" value="{{ old('name', $docente?->user->name) }}"
               class="cy-input" autocomplete="name" required>
    </div>
    <div>
        <label for="email" class="cy-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $docente?->user->email) }}"
               class="cy-input" autocomplete="email" inputmode="email" spellcheck="false" required>
    </div>
    <div>
        <label for="password" class="cy-label">Contraseña {{ $docente ? '(opcional)' : '' }}</label>
        <input id="password" type="password" name="password"
               class="cy-input" autocomplete="new-password" {{ $docente ? '' : 'required' }}>
    </div>
    <div>
        <label for="cedula" class="cy-label">Cédula</label>
        <input id="cedula" type="text" name="cedula" value="{{ old('cedula', $docente?->cedula) }}"
               maxlength="10" inputmode="numeric" autocomplete="off" spellcheck="false"
               class="cy-input font-mono" required>
    </div>
    <div>
        <label for="nombres" class="cy-label">Nombres</label>
        <input id="nombres" type="text" name="nombres" value="{{ old('nombres', $docente?->nombres) }}"
               class="cy-input" autocomplete="given-name" required>
    </div>
    <div>
        <label for="apellidos" class="cy-label">Apellidos</label>
        <input id="apellidos" type="text" name="apellidos" value="{{ old('apellidos', $docente?->apellidos) }}"
               class="cy-input" autocomplete="family-name" required>
    </div>
    <div>
        <label for="especialidad" class="cy-label">Especialidad</label>
        <input id="especialidad" type="text" name="especialidad" value="{{ old('especialidad', $docente?->especialidad) }}" class="cy-input">
    </div>
    <div>
        <label for="telefono" class="cy-label">Teléfono</label>
        <input id="telefono" type="tel" name="telefono" value="{{ old('telefono', $docente?->telefono) }}"
               class="cy-input" autocomplete="tel" inputmode="tel">
    </div>
    <div class="md:col-span-2 flex flex-wrap gap-6">
        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                   class="rounded border-slate-300 dark:border-slate-700 text-brand-600"
                   @checked(old('activo', $docente?->activo ?? true))>
            <span class="text-sm text-slate-700 dark:text-slate-300">Activo</span>
        </label>
        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="es_coordinador" value="0">
            <input type="checkbox" name="es_coordinador" value="1"
                   class="rounded border-slate-300 dark:border-slate-700 text-brand-600"
                   @checked(old('es_coordinador', $docente?->es_coordinador ?? false))>
            <span class="text-sm text-slate-700 dark:text-slate-300">Coordinador PPE</span>
        </label>
    </div>
</div>
<div class="mt-8 flex justify-end gap-2">
    <a href="{{ route('docentes.index') }}" class="cy-btn-ghost">Cancelar</a>
    <button class="cy-btn-primary">Guardar</button>
</div>
