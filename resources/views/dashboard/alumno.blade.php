<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Mi progreso PPE</h1>
        <p class="cy-page-subtitle">Programa de Participación Estudiantil — avance personal.</p>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto safe-px space-y-6">
        @if(! $alumno)
            <div role="alert" class="cy-card p-4 border-l-4 border-l-amber-500">
                <p class="text-sm text-slate-700 dark:text-slate-300">Tu cuenta aún no está vinculada a un perfil de alumno. Contacta al administrador.</p>
            </div>
        @else

            {{-- ── Alerta de riesgo ───────────────────────── --}}
            @if($enRiesgo)
                <div role="alert" class="cy-card p-4 border-l-4 border-l-red-500 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-700 dark:text-red-400">Tu nota PPE está en riesgo.</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            Necesitas {{ number_format(config('ppe.nota_minima', 7.0), 1) }}/10 para aprobar. Participa en más actividades y completa tus bitácoras.
                        </p>
                    </div>
                </div>
            @endif

            {{-- ── Bitácoras pendientes ───────────────────── --}}
            @if($actividadesSinBitacora->isNotEmpty())
                <section class="cy-card overflow-hidden border-l-4 border-l-amber-400">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <div>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                {{ $actividadesSinBitacora->count() }} {{ $actividadesSinBitacora->count() === 1 ? 'actividad' : 'actividades' }} sin bitácora
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Documenta tu experiencia para que el docente pueda calificarla.</p>
                        </div>
                        <a href="{{ route('bitacoras.create') }}" class="cy-btn-primary text-sm shrink-0">Escribir ahora</a>
                    </div>
                    <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($actividadesSinBitacora->take(4) as $a)
                            <li class="px-5 py-2.5 flex items-center justify-between gap-4 text-sm">
                                <span class="text-slate-700 dark:text-slate-300 truncate">{{ $a->titulo }}</span>
                                <span class="text-xs text-slate-500 shrink-0">{{ $a->fecha->format('d/m/Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @if($actividadesSinBitacora->count() > 4)
                        <div class="px-5 py-2.5 border-t border-slate-200 dark:border-slate-800 text-xs text-slate-500">
                            y {{ $actividadesSinBitacora->count() - 4 }} más…
                        </div>
                    @endif
                </section>
            @endif

            {{-- ── Hero progress ──────────────────────────── --}}
            <section class="cy-card p-6 sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $alumno->nombre_completo }}</h2>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="cy-badge-cyan">{{ $alumno->anio_bachillerato }} {{ $alumno->paralelo }}</span>
                            <span class="cy-badge-muted">CI {{ $alumno->cedula }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-semibold leading-none {{ $enRiesgo ? 'text-red-600 dark:text-red-400' : 'text-slate-900 dark:text-slate-100' }}">
                            {{ number_format($horasCompletadas, 1) }}
                            <span class="text-base font-normal text-slate-500 dark:text-slate-400"> / {{ $meta }} h</span>
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $progreso }}% completado</div>
                    </div>
                </div>

                @php
                    $barColor = $progreso >= 100 ? 'bg-emerald-500'
                              : ($enRiesgo         ? 'bg-red-500'
                              : ($progreso >= 70   ? 'bg-brand-600'
                                                   : 'bg-amber-500'));
                @endphp
                <div class="cy-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                     aria-valuenow="{{ min($progreso, 100) }}" aria-label="Progreso PPE total">
                    <div class="cy-progress-bar {{ $barColor }}" style="width: {{ min($progreso, 100) }}%"></div>
                </div>

                {{-- Por-year breakdown --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach([['1ro', $horas_1ro, $nota_1ro], ['2do', $horas_2do, $nota_2do]] as [$anio, $horas, $nota])
                        @php
                            $pct = $meta > 0 ? min(100, round(($horas / $meta) * 100, 1)) : 0;
                            $ok  = $nota >= config('ppe.nota_minima', 7.0);
                        @endphp
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ $anio }} Bachillerato</span>
                                <span class="font-bold text-lg {{ $ok ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ number_format($nota, 2) }}<span class="text-xs font-normal text-slate-400">/10</span>
                                </span>
                            </div>
                            <div class="cy-progress mb-1">
                                <div class="cy-progress-bar {{ $ok ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ number_format($horas, 1) }} / {{ $meta }} h · {{ $pct }}%</div>
                        </div>
                    @endforeach
                </div>

                @if($progreso >= 100)
                    <p class="mt-4 text-sm text-emerald-600 dark:text-emerald-400 font-medium">✓ Has completado el programa PPE</p>
                @elseif($progreso >= 80)
                    <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">
                        Te faltan <strong>{{ number_format($meta - $horasCompletadas, 1) }} horas</strong> para completar el PPE.
                    </p>
                @endif
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ── Próximas actividades ──────────────── --}}
                <section class="cy-card overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="cy-section-title">Próximas actividades</h3>
                    </div>
                    @if($proximas->isEmpty())
                        <p class="px-6 py-8 text-sm text-slate-500 dark:text-slate-400">No tienes actividades próximas.</p>
                    @else
                        <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($proximas as $a)
                                <li class="px-6 py-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-medium text-slate-900 dark:text-slate-100 truncate">{{ $a->titulo }}</span>
                                        <span class="cy-badge-cyan shrink-0">{{ $a->horas_asignadas }}h</span>
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $a->fecha->format('d/m/Y') }} · {{ config('ppe.fases.'.$a->fase, $a->fase) }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- ── Historial reciente ─────────────────── --}}
                <section class="cy-card overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="cy-section-title">Historial reciente</h3>
                    </div>
                    @if($historial->isEmpty())
                        <p class="px-6 py-8 text-sm text-slate-500 dark:text-slate-400">Aún no tienes actividades completadas.</p>
                    @else
                        <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($historial as $a)
                                <li class="px-6 py-3 flex justify-between items-center">
                                    <div class="min-w-0">
                                        <div class="font-medium text-slate-900 dark:text-slate-100 truncate">{{ $a->titulo }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $a->fecha->format('d/m/Y') }}</div>
                                    </div>
                                    <span class="cy-badge-green shrink-0">+{{ $a->pivot->horas_confirmadas }}h</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="px-6 py-3 border-t border-slate-200 dark:border-slate-800">
                            <a href="{{ route('bitacoras.index') }}" class="text-sm text-brand-600 dark:text-brand-400 hover:underline">Ver mis bitácoras</a>
                        </div>
                    @endif
                </section>
            </div>

        @endif
    </div>
</x-app-layout>
