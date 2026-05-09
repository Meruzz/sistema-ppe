<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">{{ $actividad->titulo }}</h1>
                <p class="cy-page-subtitle">{{ $actividad->grupo->nombre }} · {{ $actividad->fecha->format('d/m/Y') }}</p>
            </div>
            <a href="{{ route('actividades.edit', $actividad) }}" class="cy-btn-primary">Editar</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto safe-px space-y-6">

        {{-- Info de la actividad --}}
        <section class="cy-card p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <div class="cy-label">Grupo</div>
                    <a href="{{ route('grupos.show', $actividad->grupo) }}" class="text-brand-600 dark:text-brand-400 hover:underline mt-1 block">
                        {{ $actividad->grupo->nombre }}
                    </a>
                </div>
                <div>
                    <div class="cy-label">Fase PPE</div>
                    <div class="mt-1">
                        @if($actividad->fase)
                            @php
                                $faseBadge = match($actividad->fase) {
                                    'formacion'    => 'cy-badge-muted',
                                    'ejecucion'    => 'cy-badge-cyan',
                                    'presentacion' => 'cy-badge-yellow',
                                    default        => 'cy-badge-muted',
                                };
                            @endphp
                            <span class="{{ $faseBadge }}">{{ config('ppe.fases')[$actividad->fase] ?? $actividad->fase }}</span>
                        @else
                            <span class="text-slate-400">Sin fase asignada</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="cy-label">Ámbito PPE</div>
                    <div class="text-slate-800 dark:text-slate-200 mt-1">{{ $actividad->ambito->nombre ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">Fecha</div>
                    <div class="font-mono text-slate-800 dark:text-slate-200 mt-1">{{ $actividad->fecha->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="cy-label">Horario</div>
                    <div class="font-mono text-slate-800 dark:text-slate-200 mt-1">
                        {{ $actividad->hora_inicio ?? '—' }}
                        @if($actividad->hora_fin) → {{ $actividad->hora_fin }} @endif
                    </div>
                </div>
                <div>
                    <div class="cy-label">Horas asignadas</div>
                    <div class="mt-1"><span class="cy-badge-cyan">{{ $actividad->horas_asignadas }}h</span></div>
                </div>
                <div>
                    <div class="cy-label">Lugar</div>
                    <div class="text-slate-600 dark:text-slate-400 mt-1">{{ $actividad->lugar ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">Docente</div>
                    <div class="text-slate-600 dark:text-slate-400 mt-1">{{ $actividad->grupo->docente->nombre_completo ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">Estado</div>
                    <div class="mt-1">
                        @php
                            $estadoBadge = match($actividad->estado) {
                                'completada' => 'cy-badge-green',
                                'en_curso'   => 'cy-badge-cyan',
                                'cancelada'  => 'cy-badge-magenta',
                                default      => 'cy-badge-muted',
                            };
                        @endphp
                        <span class="{{ $estadoBadge }}">{{ ucfirst(str_replace('_', ' ', $actividad->estado)) }}</span>
                    </div>
                </div>
            </div>
            @if($actividad->descripcion)
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-4">
                    {{ $actividad->descripcion }}
                </p>
            @endif
        </section>

        {{-- Asistencia --}}
        <section class="cy-card overflow-hidden">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                Asistencia y horas
            </h2>
            @if($actividad->alumnos->isEmpty())
                <p class="px-6 py-6 text-sm text-slate-500 dark:text-slate-400">
                    Sin alumnos vinculados. Revisa que el grupo tenga alumnos asignados.
                </p>
            @else
                <form method="POST" action="{{ route('actividades.asistencia', $actividad) }}">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                                <tr>
                                    <th class="text-left px-6 py-3">Alumno</th>
                                    <th class="text-left px-4 py-3">Estado</th>
                                    <th class="text-left px-4 py-3">Horas</th>
                                    <th class="text-left px-4 py-3">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @foreach($actividad->alumnos as $alumno)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-3 font-medium text-slate-800 dark:text-slate-200">{{ $alumno->nombre_completo }}</td>
                                        <td class="px-4 py-3">
                                            <select name="asistencia[{{ $alumno->id }}][estado]" class="cy-select text-sm">
                                                @foreach(['pendiente' => 'Pendiente', 'asistio' => 'Asistió', 'falto' => 'Faltó', 'justificado' => 'Justificado'] as $val => $label)
                                                    <option value="{{ $val }}" @selected($alumno->pivot->estado === $val)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" step="0.5" min="0"
                                                   name="asistencia[{{ $alumno->id }}][horas_confirmadas]"
                                                   value="{{ $alumno->pivot->horas_confirmadas }}"
                                                   class="cy-input w-24 text-sm">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text"
                                                   name="asistencia[{{ $alumno->id }}][observaciones]"
                                                   value="{{ $alumno->pivot->observaciones }}"
                                                   class="cy-input text-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                        <button type="submit" class="cy-btn-primary">Guardar asistencia</button>
                    </div>
                </form>
            @endif
        </section>
    </div>
</x-app-layout>
