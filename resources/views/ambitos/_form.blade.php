@props(['ambito' => null])
@csrf

@php
$colores = [
    'blue'    => 'Azul — Acción Cívica',
    'green'   => 'Verde — Salud y Bienestar',
    'emerald' => 'Esmeralda — Acción por el Ambiente',
    'amber'   => 'Ámbar — Animación a la Lectura',
    'rose'    => 'Rosa — Prevención del Embarazo Temprano',
];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label for="nombre" class="cy-label">Nombre del ámbito</label>
        <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $ambito?->nombre) }}"
               class="cy-input mt-1" required>
    </div>
    <div>
        <label for="codigo" class="cy-label">Código <span class="text-slate-400 font-normal">(opcional)</span></label>
        <input id="codigo" type="text" name="codigo" value="{{ old('codigo', $ambito?->codigo) }}"
               maxlength="20" class="cy-input mt-1 font-mono">
    </div>
    <div>
        <label for="color" class="cy-label">Color</label>
        <select id="color" name="color" class="cy-select mt-1">
            @foreach($colores as $val => $label)
                <option value="{{ $val }}" @selected(old('color', $ambito?->color ?? 'blue') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="descripcion" class="cy-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3" class="cy-input mt-1">{{ old('descripcion', $ambito?->descripcion) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                   class="rounded border-slate-300 dark:border-slate-600 text-brand-600 focus:ring-brand-500"
                   @checked(old('activo', $ambito?->activo ?? true))>
            <span class="ms-2 text-sm text-slate-700 dark:text-slate-300">Activo</span>
        </label>
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('ambitos.index') }}" class="cy-btn-ghost">Cancelar</a>
    <button type="submit" class="cy-btn-primary">Guardar</button>
</div>
