@props(['actividad' => null, 'grupos', 'materias'])
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label for="titulo" class="cy-label">Título</label>
        <input id="titulo" type="text" name="titulo" value="{{ old('titulo', $actividad?->titulo) }}"
               class="cy-input mt-1" required>
    </div>
    <div>
        <label for="grupo_id" class="cy-label">Grupo</label>
        <select id="grupo_id" name="grupo_id" class="cy-select mt-1" required>
            <option value="">—</option>
            @foreach($grupos as $g)
                <option value="{{ $g->id }}" @selected(old('grupo_id', $actividad?->grupo_id ?? request('grupo_id'))==$g->id)>{{ $g->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="materia_id" class="cy-label">Materia</label>
        <select id="materia_id" name="materia_id" class="cy-select mt-1">
            <option value="">—</option>
            @foreach($materias as $m)
                <option value="{{ $m->id }}" @selected(old('materia_id', $actividad?->materia_id)==$m->id)>{{ $m->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="fecha" class="cy-label">Fecha</label>
        <input id="fecha" type="date" name="fecha" value="{{ old('fecha', $actividad?->fecha?->format('Y-m-d')) }}"
               class="cy-input mt-1" required>
    </div>
    <div>
        <label for="horas_asignadas" class="cy-label">Horas asignadas</label>
        <input id="horas_asignadas" type="number" step="0.5" min="0.5" max="24" name="horas_asignadas"
               value="{{ old('horas_asignadas', $actividad?->horas_asignadas) }}"
               class="cy-input mt-1" required>
    </div>
    <div>
        <label for="hora_inicio" class="cy-label">Hora inicio</label>
        <input id="hora_inicio" type="time" name="hora_inicio"
               value="{{ old('hora_inicio', $actividad?->hora_inicio) }}"
               class="cy-input mt-1">
    </div>
    <div>
        <label for="hora_fin" class="cy-label">Hora fin</label>
        <input id="hora_fin" type="time" name="hora_fin"
               value="{{ old('hora_fin', $actividad?->hora_fin) }}"
               class="cy-input mt-1">
    </div>
    <div>
        <label for="lugar" class="cy-label">Lugar</label>
        <input id="lugar" type="text" name="lugar" value="{{ old('lugar', $actividad?->lugar) }}"
               class="cy-input mt-1">
    </div>
    <div>
        <label for="estado" class="cy-label">Estado</label>
        <select id="estado" name="estado" class="cy-select mt-1" required>
            @foreach(['planificada','en_curso','completada','cancelada'] as $e)
                <option value="{{ $e }}" @selected(old('estado', $actividad?->estado ?? 'planificada')===$e)>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="descripcion" class="cy-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3" class="cy-input mt-1">{{ old('descripcion', $actividad?->descripcion) }}</textarea>
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('actividades.index') }}" class="cy-btn-ghost">Cancelar</a>
    <button type="submit" class="cy-btn-primary">Guardar</button>
</div>
