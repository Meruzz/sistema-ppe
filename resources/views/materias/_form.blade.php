@props(['materia' => null])
@csrf
<div class="space-y-4">
    <div>
        <label for="nombre" class="cy-label">Nombre</label>
        <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $materia?->nombre) }}" class="cy-input" required>
    </div>
    <div>
        <label for="codigo" class="cy-label">Código</label>
        <input id="codigo" type="text" name="codigo" value="{{ old('codigo', $materia?->codigo) }}"
               maxlength="20" autocomplete="off" spellcheck="false" class="cy-input font-mono">
    </div>
    <div>
        <label for="descripcion" class="cy-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3" class="cy-input">{{ old('descripcion', $materia?->descripcion) }}</textarea>
    </div>
    <div>
        <label class="inline-flex items-center cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                   class="rounded-cy bg-transparent border-cy-border-light/30 dark:border-cy-border text-cy-yellow focus:ring-cy-yellow"
                   @checked(old('activo', $materia?->activo ?? true))>
            <span class="ms-2 text-xs font-mono uppercase tracking-widest">Activa</span>
        </label>
    </div>
</div>
<div class="mt-8 flex justify-end gap-2">
    <a href="{{ route('materias.index') }}" class="cy-btn-ghost">Cancelar</a>
    <button class="cy-btn-primary">Guardar</button>
</div>
