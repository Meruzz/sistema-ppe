@csrf
<div class="space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="nombre" class="cy-label">Año lectivo <span class="text-red-500">*</span></label>
            <input id="nombre" type="text" name="nombre"
                   value="{{ old('nombre', $anioLectivo->nombre ?? '') }}"
                   placeholder="2025-2026" maxlength="9"
                   class="cy-input mt-1 font-mono" required>
            @error('nombre') <p class="cy-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="ciclo" class="cy-label">Ciclo <span class="text-red-500">*</span></label>
            <select id="ciclo" name="ciclo" class="cy-select mt-1" required>
                <option value="sierra" @selected(old('ciclo', $anioLectivo->ciclo ?? 'sierra') === 'sierra')>Sierra</option>
                <option value="costa"  @selected(old('ciclo', $anioLectivo->ciclo ?? '') === 'costa')>Costa</option>
            </select>
            @error('ciclo') <p class="cy-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="inicio" class="cy-label">Fecha de inicio</label>
            <input id="inicio" type="date" name="inicio"
                   value="{{ old('inicio', $anioLectivo->inicio?->toDateString() ?? '') }}"
                   class="cy-input mt-1">
            @error('inicio') <p class="cy-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="fin" class="cy-label">Fecha de fin</label>
            <input id="fin" type="date" name="fin"
                   value="{{ old('fin', $anioLectivo->fin?->toDateString() ?? '') }}"
                   class="cy-input mt-1">
            @error('fin') <p class="cy-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                   class="rounded border-slate-300 dark:border-slate-700 text-brand-600"
                   @checked(old('activo', $anioLectivo->activo ?? false))>
            <span class="text-sm text-slate-700 dark:text-slate-300">Marcar como año lectivo activo</span>
        </label>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ms-6">Solo puede haber un año lectivo activo a la vez.</p>
    </div>

    <div class="flex justify-end gap-3 pt-2">
        <a href="{{ route('anio-lectivos.index') }}" class="cy-btn-ghost">Cancelar</a>
        <button type="submit" class="cy-btn-primary">Guardar</button>
    </div>
</div>
