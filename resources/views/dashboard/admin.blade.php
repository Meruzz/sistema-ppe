<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Dashboard</h1>
                <p class="cy-page-subtitle">Resumen general del Programa de Participación Estudiantil.</p>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400">Meta: {{ $meta }} h / año</span>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto safe-px space-y-6">

        {{-- ── Stat cards ───────────────────────────────────── --}}
        <section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3" aria-label="Indicadores clave">
            <div class="cy-stat">
                <div class="cy-stat-label">Alumnos activos</div>
                <div class="cy-stat-value">{{ $stats['alumnos_activos'] }}</div>
                <a href="{{ route('alumnos.index') }}" class="mt-2 text-xs text-brand-600 dark:text-brand-400 hover:underline inline-block">Ver todos</a>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Docentes</div>
                <div class="cy-stat-value">{{ $stats['docentes_activos'] }}</div>
                <a href="{{ route('docentes.index') }}" class="mt-2 text-xs text-brand-600 dark:text-brand-400 hover:underline inline-block">Ver todos</a>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Grupos activos</div>
                <div class="cy-stat-value">{{ $stats['grupos_activos'] }}</div>
                <a href="{{ route('grupos.index') }}" class="mt-2 text-xs text-brand-600 dark:text-brand-400 hover:underline inline-block">Ver todos</a>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Actividades pendientes</div>
                <div class="cy-stat-value text-amber-600 dark:text-amber-400">{{ $stats['actividades_pendientes'] }}</div>
                <a href="{{ route('actividades.index') }}" class="mt-2 text-xs text-brand-600 dark:text-brand-400 hover:underline inline-block">Ver todas</a>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Horas registradas</div>
                <div class="cy-stat-value text-emerald-600 dark:text-emerald-400">{{ number_format($stats['horas_registradas'], 0) }}</div>
                <div class="mt-2 text-xs text-slate-400">acumuladas</div>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Bitácoras sin revisar</div>
                <div class="cy-stat-value {{ $stats['bitacoras_sin_revisar'] > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-slate-100' }}">
                    {{ $stats['bitacoras_sin_revisar'] }}
                </div>
                <a href="{{ route('bitacoras.index') }}" class="mt-2 text-xs text-brand-600 dark:text-brand-400 hover:underline inline-block">Ver todas</a>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Alumnos en riesgo</div>
                <div class="cy-stat-value {{ $stats['alumnos_en_riesgo'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                    {{ $stats['alumnos_en_riesgo'] }}
                </div>
                <a href="{{ route('alumnos.index') }}" class="mt-2 text-xs text-brand-600 dark:text-brand-400 hover:underline inline-block">Ver alumnos</a>
            </div>
        </section>

        {{-- ── Gráfico + próximas actividades ──────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <section class="cy-card p-6">
                <header class="flex items-center justify-between mb-4">
                    <h2 class="cy-section-title">Progreso promedio por año</h2>
                </header>
                <canvas id="chartProgreso" height="200" aria-label="Gráfico de progreso promedio por año de bachillerato" role="img"></canvas>
                <div class="mt-4 grid grid-cols-{{ $progresoPorAnio->count() }} gap-3">
                    @foreach($progresoPorAnio as $row)
                        <div class="text-center">
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $row->anio_bachillerato }} bach.</div>
                            <div class="font-semibold text-lg text-slate-900 dark:text-slate-100">{{ number_format($row->nota_promedio, 2) }}<span class="text-xs font-normal text-slate-400">/10</span></div>
                            <div class="text-xs text-slate-500">{{ $row->total_alumnos }} alumnos</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="cy-card overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="cy-section-title">Próximas actividades</h2>
                </div>
                @if($proximasActividades->isEmpty())
                    <p class="px-6 py-8 text-sm text-slate-500 dark:text-slate-400">No hay actividades próximas.</p>
                @else
                    <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($proximasActividades as $a)
                            @php
                                $faseLbl = config('ppe.fases.'.$a->fase, $a->fase);
                                $estadoClasses = match($a->estado) {
                                    'en_curso'    => 'cy-badge-green',
                                    'planificada' => 'cy-badge-cyan',
                                    default       => 'cy-badge-muted',
                                };
                            @endphp
                            <li class="px-6 py-3 flex items-center justify-between gap-4 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <div class="min-w-0">
                                    <a href="{{ route('actividades.show', $a) }}" class="font-medium text-slate-900 dark:text-slate-100 hover:text-brand-600 dark:hover:text-brand-400 truncate block">{{ $a->titulo }}</a>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $a->grupo->nombre ?? '—' }} · {{ $a->fecha->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="{{ $estadoClasses }}">{{ ucfirst(str_replace('_',' ',$a->estado)) }}</span>
                                    <span class="cy-badge-muted">{{ $a->horas_asignadas }}h</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="px-6 py-3 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('actividades.index') }}" class="text-sm text-brand-600 dark:text-brand-400 hover:underline">Ver todas las actividades</a>
                    </div>
                @endif
            </section>
        </div>

        {{-- ── Alumnos en riesgo ───────────────────────────── --}}
        @if($topRiesgo->isNotEmpty())
        <section class="cy-card overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h2 class="cy-section-title">Alumnos en riesgo</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Nota proyectada por debajo del mínimo ({{ config('ppe.nota_minima', 7.0) }}/10)</p>
                </div>
                <span class="cy-badge-magenta">{{ $stats['alumnos_en_riesgo'] }} en riesgo</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-6 py-3">Alumno</th>
                            <th class="text-left px-6 py-3">Año</th>
                            <th class="text-left px-6 py-3">Horas</th>
                            <th class="text-left px-6 py-3">Progreso</th>
                            <th class="text-right px-6 py-3">Nota</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($topRiesgo as $r)
                        <tr class="hover:bg-red-50/50 dark:hover:bg-red-950/20">
                            <td class="px-6 py-3 font-medium text-slate-900 dark:text-slate-100">
                                {{ $r->nombre_completo }}<br>
                                <span class="text-xs font-normal text-slate-500">{{ $r->cedula }}</span>
                            </td>
                            <td class="px-6 py-3">
                                <span class="{{ $r->anio_bachillerato === '1ro' ? 'cy-badge-cyan' : 'cy-badge-amber' }}">
                                    {{ $r->anio_bachillerato }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-slate-600 dark:text-slate-400">{{ number_format($r->horas_total, 1) }} / {{ $meta }} h</td>
                            <td class="px-6 py-3 w-32">
                                <div class="cy-progress">
                                    <div class="cy-progress-bar bg-red-500" style="width: {{ $r->progreso }}%"></div>
                                </div>
                                <div class="text-xs text-slate-500 mt-1">{{ $r->progreso }}%</div>
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-red-600 dark:text-red-400">{{ number_format($r->nota, 2) }}</td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('alumnos.show', $r->id) }}" class="text-brand-600 dark:text-brand-400 hover:underline text-sm">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        @endif

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const isDark = document.documentElement.classList.contains('dark');
            const ctx = document.getElementById('chartProgreso').getContext('2d');
            const labelColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor  = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(0,0,0,0.06)';

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($progresoPorAnio->pluck('anio_bachillerato')->map(fn($v) => $v . ' Bach.')),
                    datasets: [{
                        label: '% Progreso promedio',
                        data: @json($progresoPorAnio->pluck('progreso')),
                        backgroundColor: ['#2563eb', '#7c3aed'],
                        borderRadius: 6,
                        maxBarThickness: 64,
                    }]
                },
                options: {
                    animation: reduceMotion ? false : { duration: 600 },
                    scales: {
                        y: {
                            beginAtZero: true, max: 100,
                            ticks: { color: labelColor, font: { size: 11 }, callback: v => v + '%' },
                            grid:  { color: gridColor },
                        },
                        x: {
                            ticks: { color: labelColor, font: { size: 12 } },
                            grid:  { display: false },
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ctx.raw + '% completado' } }
                    }
                }
            });
        })();
    </script>
    @endpush
</x-app-layout>
