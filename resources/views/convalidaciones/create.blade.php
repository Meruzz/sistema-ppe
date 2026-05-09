<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Nueva convalidación</h1>
        <p class="cy-page-subtitle">{{ $alumno->nombre_completo }} — {{ $alumno->cedula }}</p>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto safe-px">
        <div class="cy-card p-6">
            <form method="POST" action="{{ route('convalidaciones.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="alumno_id" value="{{ $alumno->id }}">

                <div>
                    <label for="tipo" class="cy-label">Tipo de convalidación <span class="text-red-500">*</span></label>
                    <select id="tipo" name="tipo" class="cy-select mt-1" required>
                        <option value="">Seleccionar…</option>
                        @foreach(\App\Models\Convalidacion::$tipos as $val => $label)
                            <option value="{{ $val }}" @selected(old('tipo') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('tipo') <p class="cy-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="descripcion" class="cy-label">Descripción / observaciones</label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                              class="cy-input mt-1">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <p class="cy-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="documento_referencia" class="cy-label">Referencia de documento</label>
                    <input id="documento_referencia" type="text" name="documento_referencia"
                           value="{{ old('documento_referencia') }}"
                           placeholder="Nro. oficio, resolución, etc."
                           class="cy-input mt-1">
                    @error('documento_referencia') <p class="cy-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="fecha_inicio" class="cy-label">Desde</label>
                        <input id="fecha_inicio" type="date" name="fecha_inicio"
                               value="{{ old('fecha_inicio') }}" class="cy-input mt-1">
                    </div>
                    <div>
                        <label for="fecha_fin" class="cy-label">Hasta</label>
                        <input id="fecha_fin" type="date" name="fecha_fin"
                               value="{{ old('fecha_fin') }}" class="cy-input mt-1">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('alumnos.show', $alumno) }}" class="cy-btn-ghost">Cancelar</a>
                    <button type="submit" class="cy-btn-primary">Registrar convalidación</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
