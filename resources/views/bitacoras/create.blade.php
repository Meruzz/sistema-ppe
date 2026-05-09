<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Nueva entrada de bitácora</h1>
        <p class="cy-page-subtitle">Documenta tu experiencia en la actividad del PPE.</p>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto safe-px">
        <div class="cy-card p-6">
            <form method="POST" action="{{ route('bitacoras.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="actividad_id" class="cy-label">Actividad relacionada</label>
                    <select id="actividad_id" name="actividad_id" class="cy-select mt-1">
                        <option value="">Sin actividad específica</option>
                        @foreach($actividades as $act)
                            <option value="{{ $act->id }}" @selected(old('actividad_id') == $act->id)>
                                {{ $act->fecha->format('d/m/Y') }} — {{ $act->titulo }}
                            </option>
                        @endforeach
                    </select>
                    @error('actividad_id')
                        <p class="cy-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fecha" class="cy-label">Fecha de la entrada <span class="text-red-500">*</span></label>
                    <input id="fecha" type="date" name="fecha"
                           value="{{ old('fecha', now()->toDateString()) }}"
                           max="{{ now()->toDateString() }}"
                           class="cy-input mt-1">
                    @error('fecha')
                        <p class="cy-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contenido" class="cy-label">¿Qué hiciste? ¿Qué observaste? <span class="text-red-500">*</span></label>
                    <textarea id="contenido" name="contenido" rows="6"
                              placeholder="Describe las actividades realizadas, lo que ocurrió, quiénes participaron..."
                              class="cy-input mt-1">{{ old('contenido') }}</textarea>
                    @error('contenido')
                        <p class="cy-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="aprendizajes" class="cy-label">¿Qué aprendiste? ¿Qué mejorarías?</label>
                    <textarea id="aprendizajes" name="aprendizajes" rows="4"
                              placeholder="Reflexiona sobre tu experiencia, los aprendizajes obtenidos y posibles mejoras..."
                              class="cy-input mt-1">{{ old('aprendizajes') }}</textarea>
                    @error('aprendizajes')
                        <p class="cy-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('bitacoras.index') }}" class="cy-btn-ghost">Cancelar</a>
                    <button type="submit" class="cy-btn-primary">Guardar entrada</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
