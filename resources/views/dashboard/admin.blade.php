<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Dashboard</h1>
                <p class="cy-page-subtitle">Resumen general del programa de participación estudiantil.</p>
            </div>
            <div class="text-xs text-slate-500 dark:text-slate-400">
                Meta: {{ $meta }} horas
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto safe-px space-y-6">

        {{-- Stat tiles --}}
        <section class="grid grid-cols-2 lg:grid-cols-5 gap-4" aria-label="Indicadores clave">
            <div class="cy-stat">
                <div class="cy-stat-label">Alumnos activos</div>
                <div class="cy-stat-value">{{ $stats['alumnos_activos'] }}</div>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Docentes activos</div>
                <div class="cy-stat-value">{{ $stats['docentes_activos'] }}</div>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Grupos activos</div>
                <div class="cy-stat-value">{{ $stats['grupos_activos'] }}</div>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Actividades pendientes</div>
                <div class="cy-stat-value text-amber-600 dark:text-amber-400">{{ $stats['actividades_pendientes'] }}</div>
            </div>
            <div class="cy-stat">
                <div class="cy-stat-label">Horas registradas</div>
                <div class="cy-stat-value text-emerald-600 dark:text-emerald-400">{{ number_format($stats['horas_registradas'], 1) }}</div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section class="cy-card p-6">
                <header class="flex items-center justify-between mb-4">
                    <h2 class="cy-section-title">Progreso promedio por año</h2>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Meta: {{ $meta }}h</span>
                </header>
                <canvas id="chartProgreso" height="220" aria-label="Gráfico de progreso promedio por año de bachillerato" role="img"></canvas>
            </section>

            <section class="cy-card p-6">
                <header class="mb-4">
                    <h2 class="cy-section-title">Próximas actividades</h2>
                </header>
                @if($proximasActividades->isEmpty())
                    <p class="text-sm text-slate-500 dark:text-slate-400">No hay actividades próximas.</p>
                @else
                    <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($proximasActividades as $a)
                            <li class="py-3 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="font-medium text-slate-900 dark:text-slate-100 truncate">{{ $a->titulo }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $a->grupo->nombre ?? '—' }} · {{ $a->fecha->format('d/m/Y') }}
                                    </div>
                                </div>
                                <span class="cy-badge-cyan">{{ $a->horas_asignadas }}h</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const isDark = document.documentElement.classList.contains('dark');
            const ctx = document.getElementById('chartProgreso').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($progresoPorAnio->pluck('anio_bachillerato')),
                    datasets: [{
                        label: '% Progreso promedio',
                        data: @json($progresoPorAnio->pluck('progreso')),
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        barThickness: 'flex',
                        maxBarThickness: 60,
                    }]
                },
                options: {
                    animation: reduceMotion ? false : { duration: 600 },
                    scales: {
                        y: {
                            beginAtZero: true, max: 100,
                            ticks: { color: isDark ? '#94a3b8' : '#64748b', font: { size: 11 } },
                            grid:  { color: isDark ? 'rgba(148,163,184,0.1)' : 'rgba(0,0,0,0.06)' },
                        },
                        x: {
                            ticks: { color: isDark ? '#94a3b8' : '#64748b', font: { size: 12 } },
                            grid:  { display: false },
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        })();
    </script>
    @endpush
</x-app-layout>
