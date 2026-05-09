<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">{{ $alumno->nombre_completo }}</h1>
                <p class="cy-page-subtitle">Perfil del estudiante y progreso PPE.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reportes.alumno', $alumno) }}" target="_blank" class="cy-btn-ghost">
                    Certificado PDF
                </a>
                <a href="{{ route('alumnos.edit', $alumno) }}" class="cy-btn-primary">Editar</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto safe-px space-y-6">

        <section class="cy-card p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <div class="cy-label">Cédula</div>
                    <div class="font-mono text-slate-900 dark:text-slate-100">{{ $alumno->cedula }}</div>
                </div>
                <div>
                    <div class="cy-label">Email</div>
                    <div class="font-mono text-xs break-all text-slate-700 dark:text-slate-300">{{ $alumno->user->email }}</div>
                </div>
                <div>
                    <div class="cy-label">Año</div>
                    <div><span class="cy-badge-cyan">{{ $alumno->anio_bachillerato }} {{ $alumno->paralelo }}</span></div>
                </div>
                <div>
                    <div class="cy-label">Teléfono</div>
                    <div class="text-slate-600 dark:text-slate-400">{{ $alumno->telefono ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">F. Nacimiento</div>
                    <div class="font-mono text-xs text-slate-600 dark:text-slate-400">{{ $alumno->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">Representante</div>
                    <div class="text-slate-600 dark:text-slate-400">{{ $alumno->representante ?? '—' }}</div>
                </div>
            </div>
        </section>

        <section class="cy-card p-6">
            <div class="flex items-end justify-between mb-3">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Progreso del PPE</h2>
                <div class="text-sm">
                    <span class="text-brand-600 dark:text-brand-400 font-bold text-2xl">{{ number_format($alumno->horas_completadas, 1) }}</span>
                    <span class="text-slate-500 dark:text-slate-400"> / {{ config('ppe.horas_requeridas') }} HRS</span>
                </div>
            </div>
            <div class="cy-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $alumno->progreso_horas }}">
                <div class="cy-progress-bar" style="width:{{ $alumno->progreso_horas }}%"></div>
            </div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-2">{{ $alumno->progreso_horas }}% completado</div>
        </section>

        <section class="cy-card p-6">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Grupos inscritos</h2>
            @if($alumno->grupos->isEmpty())
                <p class="text-sm text-slate-500 dark:text-slate-400">Sin grupos asignados.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($alumno->grupos as $g)
                        <a href="{{ route('grupos.show', $g) }}" class="cy-badge-cyan hover:opacity-80 transition-opacity">
                            {{ $g->nombre }}{{ $g->materia ? ' · ' . $g->materia->nombre : '' }}
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="cy-card overflow-hidden">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 p-6 pb-3">Historial de actividades</h2>
            @if($alumno->actividades->isEmpty())
                <p class="px-6 pb-6 text-sm text-slate-500 dark:text-slate-400">Sin actividades registradas.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            <tr>
                                <th class="text-left px-6 py-2">Fecha</th>
                                <th class="text-left px-4 py-2">Actividad</th>
                                <th class="text-left px-4 py-2">Estado</th>
                                <th class="text-right px-6 py-2">Horas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($alumno->actividades as $a)
                                @php
                                    $b = match($a->pivot->estado) {
                                        'asistio'     => 'cy-badge-yellow',
                                        'falto'       => 'cy-badge-magenta',
                                        'justificado' => 'cy-badge-cyan',
                                        default       => 'cy-badge-muted',
                                    };
                                @endphp
                                <tr>
                                    <td class="px-6 py-2 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $a->fecha->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 text-slate-800 dark:text-slate-200">{{ $a->titulo }}</td>
                                    <td class="px-4 py-2"><span class="{{ $b }}">{{ ucfirst($a->pivot->estado) }}</span></td>
                                    <td class="px-6 py-2 text-right font-mono text-slate-700 dark:text-slate-300">{{ $a->pivot->horas_confirmadas }}h</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
