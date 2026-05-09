<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Editar convalidación</h1>
        <p class="cy-page-subtitle">{{ $convalidacion->alumno->nombre_completo }}</p>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto safe-px">
        <div class="cy-card p-6">
            <form method="POST" action="{{ route('convalidaciones.update', $convalidacion) }}" class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="alumno_id" value="{{ $convalidacion->alumno_id }}">

                <div>
                    <label for="tipo" class="cy-label">Tipo <span class="text-red-500">*</span></label>
                    <select id="tipo" name="tipo" class="cy-select mt-1" required>
                        @foreach(\App\Models\Convalidacion::$tipos as $val => $label)
                            <option value="{{ $val }}" @selected(old('tipo', $convalidacion->tipo) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="descripcion" class="cy-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                              class="cy-input mt-1">{{ old('descripcion', $convalidacion->descripcion) }}</textarea>
                </div>

                <div>
                    <label for="documento_referencia" class="cy-label">Referencia de documento</label>
                    <input id="documento_referencia" type="text" name="documento_referencia"
                           value="{{ old('documento_referencia', $convalidacion->documento_referencia) }}"
                           class="cy-input mt-1">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="fecha_inicio" class="cy-label">Desde</label>
                        <input id="fecha_inicio" type="date" name="fecha_inicio"
                               value="{{ old('fecha_inicio', $convalidacion->fecha_inicio?->toDateString()) }}"
                               class="cy-input mt-1">
                    </div>
                    <div>
                        <label for="fecha_fin" class="cy-label">Hasta</label>
                        <input id="fecha_fin" type="date" name="fecha_fin"
                               value="{{ old('fecha_fin', $convalidacion->fecha_fin?->toDateString()) }}"
                               class="cy-input mt-1">
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1"
                               class="rounded border-slate-300 dark:border-slate-700 text-brand-600"
                               @checked(old('activo', $convalidacion->activo))>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Convalidación vigente</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('alumnos.show', $convalidacion->alumno_id) }}" class="cy-btn-ghost">Cancelar</a>
                    <button type="submit" class="cy-btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
