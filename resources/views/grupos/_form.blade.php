@props(['grupo' => null, 'docentes', 'materias', 'alumnos'])
@csrf
@php $alumnosSeleccionados = old('alumnos', $grupo?->alumnos->pluck('id')->toArray() ?? []); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="nombre" class="cy-label">Nombre</label>
        <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $grupo?->nombre) }}"
               class="cy-input mt-1" required>
    </div>
    <div>
        <label for="anio_lectivo" class="cy-label">Año lectivo</label>
        <input id="anio_lectivo" type="text" name="anio_lectivo"
               value="{{ old('anio_lectivo', $grupo?->anio_lectivo ?? '2025-2026') }}"
               maxlength="9" class="cy-input mt-1 font-mono" required>
    </div>
    <div>
        <label for="docente_id" class="cy-label">Docente</label>
        <select id="docente_id" name="docente_id" class="cy-select mt-1">
            <option value="">—</option>
            @foreach($docentes as $d)
                <option value="{{ $d->id }}" @selected(old('docente_id', $grupo?->docente_id)==$d->id)>{{ $d->nombre_completo }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="materia_id" class="cy-label">Materia</label>
        <select id="materia_id" name="materia_id" class="cy-select mt-1">
            <option value="">—</option>
            @foreach($materias as $m)
                <option value="{{ $m->id }}" @selected(old('materia_id', $grupo?->materia_id)==$m->id)>{{ $m->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="descripcion" class="cy-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="2" class="cy-input mt-1">{{ old('descripcion', $grupo?->descripcion) }}</textarea>
    </div>
    <div class="md:col-span-2" x-data="{ search: '' }">
        <label class="cy-label mb-2 block">Alumnos</label>
        <input type="text" x-model="search" placeholder="Filtrar alumnos..."
               class="cy-input mb-2 text-sm" autocomplete="off">
        <div class="border border-slate-200 dark:border-slate-800 rounded-lg p-3 max-h-64 overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-2">
            @foreach($alumnos as $a)
                <label class="inline-flex items-center text-sm cursor-pointer"
                       x-show="'{{ strtolower($a->nombre_completo . ' ' . $a->cedula) }}'.includes(search.toLowerCase())">
                    <input type="checkbox" name="alumnos[]" value="{{ $a->id }}"
                           class="rounded border-slate-300 dark:border-slate-600 text-brand-600 focus:ring-brand-500"
                           @checked(in_array($a->id, $alumnosSeleccionados))>
                    <span class="ms-2 text-slate-700 dark:text-slate-300">{{ $a->apellidos }}, {{ $a->nombres }} ({{ $a->anio_bachillerato }})</span>
                </label>
            @endforeach
        </div>
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                   class="rounded border-slate-300 dark:border-slate-600 text-brand-600 focus:ring-brand-500"
                   @checked(old('activo', $grupo?->activo ?? true))>
            <span class="ms-2 text-sm text-slate-700 dark:text-slate-300">Activo</span>
        </label>
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('grupos.index') }}" class="cy-btn-ghost">Cancelar</a>
    <button type="submit" class="cy-btn-primary">Guardar</button>
</div>
