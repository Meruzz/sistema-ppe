<div class="space-y-4">
    <div>
        <label for="calificacion" class="cy-label">Calificación (0 – 10) <span class="text-red-500">*</span></label>
        <input id="calificacion" type="number" name="calificacion" step="0.5" min="0" max="10"
               value="{{ old('calificacion', $bitacora->calificacion) }}"
               class="cy-input w-32 mt-1">
        @error('calificacion')
            <p class="cy-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="observaciones_docente" class="cy-label">Observaciones (opcional)</label>
        <textarea id="observaciones_docente" name="observaciones_docente" rows="3"
                  placeholder="Retroalimentación para el alumno..."
                  class="cy-input mt-1">{{ old('observaciones_docente', $bitacora->observaciones_docente) }}</textarea>
        @error('observaciones_docente')
            <p class="cy-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end">
        <button type="submit" class="cy-btn-primary">Guardar calificación</button>
    </div>
</div>
