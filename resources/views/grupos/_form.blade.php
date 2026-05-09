@props(['grupo' => null, 'docentes', 'ambitos', 'alumnos', 'anioLectivos'])
@csrf
@php $alumnosSeleccionados = old('alumnos', $grupo?->alumnos->pluck('id')->toArray() ?? []); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="nombre" class="cy-label">Nombre</label>
        <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $grupo?->nombre) }}"
               class="cy-input mt-1" required>
    </div>
    <div>
        <label for="anio_lectivo_id" class="cy-label">Año lectivo</label>
        <select id="anio_lectivo_id" name="anio_lectivo_id" class="cy-select mt-1">
            <option value="">— Sin asignar —</option>
            @foreach($anioLectivos as $al)
                <option value="{{ $al->id }}"
                        @selected(old('anio_lectivo_id', $grupo?->anio_lectivo_id) == $al->id)>
                    {{ $al->nombre }} ({{ ucfirst($al->ciclo) }}){{ $al->activo ? ' ✓' : '' }}
                </option>
            @endforeach
        </select>
        @error('anio_lectivo_id') <p class="cy-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="anio_bachillerato" class="cy-label">Año de bachillerato <span class="text-rose-500">*</span></label>
        <select id="anio_bachillerato" name="anio_bachillerato" class="cy-select mt-1" required>
            @foreach(['1ro' => 'Primero (80 h)', '2do' => 'Segundo (80 h)'] as $val => $label)
                <option value="{{ $val }}" @selected(old('anio_bachillerato', $grupo?->anio_bachillerato ?? '1ro') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="ambito_id" class="cy-label">Ámbito PPE</label>
        <select id="ambito_id" name="ambito_id" class="cy-select mt-1">
            <option value="">— Sin ámbito asignado —</option>
            @foreach($ambitos as $a)
                <option value="{{ $a->id }}" @selected(old('ambito_id', $grupo?->ambito_id) == $a->id)>{{ $a->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="docente_id" class="cy-label">Docente facilitador</label>
        <select id="docente_id" name="docente_id" class="cy-select mt-1">
            <option value="">—</option>
            @foreach($docentes as $d)
                <option value="{{ $d->id }}" @selected(old('docente_id', $grupo?->docente_id) == $d->id)>{{ $d->nombre_completo }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="descripcion" class="cy-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="2" class="cy-input mt-1">{{ old('descripcion', $grupo?->descripcion) }}</textarea>
    </div>

    {{-- Selección de alumnos --}}
    <div class="md:col-span-2" x-data="{ search: '' }">
        <label class="cy-label mb-2 block">Alumnos</label>
        <input type="text" x-model="search" placeholder="Filtrar alumnos…"
               class="cy-input mb-2 text-sm" autocomplete="off">
        <div class="border border-slate-200 dark:border-slate-800 rounded-lg p-3 max-h-64 overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-2">
            @foreach($alumnos as $a)
                <label class="inline-flex items-center text-sm cursor-pointer"
                       x-show="'{{ strtolower($a->nombre_completo . ' ' . $a->cedula) }}'.includes(search.toLowerCase())">
                    <input type="checkbox" name="alumnos[]" value="{{ $a->id }}"
                           class="rounded border-slate-300 dark:border-slate-600 text-brand-600 focus:ring-brand-500"
                           @checked(in_array($a->id, $alumnosSeleccionados))>
                    <span class="ms-2 text-slate-700 dark:text-slate-300">{{ $a->apellidos }}, {{ $a->nombres }}
                        <span class="text-slate-400">({{ $a->anio_bachillerato }})</span>
                    </span>
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
