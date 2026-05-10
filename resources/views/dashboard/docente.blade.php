<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Panel docente</h1>
        <p class="cy-page-subtitle">Gestiona tus grupos, actividades y bitácoras.</p>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto safe-px space-y-6">
        @if(! $docente)
            <div role="alert" class="cy-card p-4 border-l-4 border-l-amber-500">
                <p class="text-sm text-slate-700 dark:text-slate-300">Tu cuenta no está vinculada a un perfil de docente. Contacta al administrador.</p>
            </div>
        @else

            {{-- ── Stat cards ───────────────────────────────── --}}
            <section class="grid grid-cols-2 sm:grid-cols-4 gap-3" aria-label="Resumen docente">
                <div class="cy-stat">
                    <div class="cy-stat-label">Mis grupos</div>
                    <div class="cy-stat-value">{{ $grupos->count() }}</div>
                </div>
                <div class="cy-stat">
                    <div class="cy-stat-label">Total alumnos</div>
                    <div class="cy-stat-value">{{ $totalAlumnos }}</div>
                </div>
                <div class="cy-stat">
                    <div class="cy-stat-label">Actividades próximas</div>
                    <div class="cy-stat-value text-amber-600 dark:text-amber-400">{{ $proximasActividades->count() }}</div>
                </div>
                <div class="cy-stat">
                    <div class="cy-stat-label">Bitácoras por revisar</div>
                    <div class="cy-stat-value {{ $bitacorasPendientes->isNotEmpty() ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                        {{ $bitacorasPendientes->count() }}
                    </div>
                </div>
            </section>

            {{-- ── Perfil breve --}}
            <div class="cy-card px-6 py-4 flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 text-lg font-semibold shrink-0">
                    {{ strtoupper(substr($docente->nombres, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $docente->nombre_completo }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ $docente->especialidad ?? 'Docente PPE' }}</div>
                </div>
                @if($docente->es_coordinador)
                    <span class="cy-badge-amber ml-auto shrink-0">Coordinador PPE</span>
                @endif
            </div>

            {{-- ── Mis grupos ───────────────────────────────── --}}
            <section>
                <h2 class="cy-section-title mb-3">Mis grupos</h2>
                @if($grupos->isEmpty())
                    <div class="cy-card p-6 text-sm text-slate-500 dark:text-slate-400">Aún no tienes grupos asignados.</div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($grupos as $g)
                            @php
                                $dotColor = [
                                    'blue'    => 'bg-blue-500',
                                    'green'   => 'bg-green-500',
                                    'emerald' => 'bg-emerald-500',
                                    'amber'   => 'bg-amber-500',
                                    'rose'    => 'bg-rose-500',
                                ][$g->ambito?->color ?? ''] ?? 'bg-slate-400';
                            @endphp
                            <a href="{{ route('grupos.show', $g) }}"
                               class="cy-card block p-5 hover:border-brand-400 hover:shadow-soft-md transition-all">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100 text-base">{{ $g->nombre }}</div>
                                    <span class="{{ $g->anio_bachillerato === '1ro' ? 'cy-badge-cyan' : 'cy-badge-amber' }} shrink-0">
                                        {{ $g->anio_bachillerato }}
                                    </span>
                                </div>
                                @if($g->ambito)
                                    <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                        <span class="w-2 h-2 rounded-full {{ $dotColor }} shrink-0"></span>
                                        {{ $g->ambito->nombre }}
                                    </div>
                                @endif
                                <div class="mt-3 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                    <span>{{ $g->alumnos_count }} alumnos</span>
                                    <span>{{ $g->anioLectivo?->nombre ?? '—' }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- ── Bitácoras pendientes ────────────────────── --}}
            @if($bitacorasPendientes->isNotEmpty())
                <section class="cy-card overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h2 class="cy-section-title">Bitácoras por revisar</h2>
                        <span class="cy-badge-amber">{{ $bitacorasPendientes->count() }} pendiente{{ $bitacorasPendientes->count() !== 1 ? 's' : '' }}</span>
                    </div>
                    <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($bitacorasPendientes->take(8) as $b)
                            <li class="px-6 py-3 flex items-center justify-between gap-4 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-800 dark:text-slate-200 text-sm truncate">{{ $b->alumno->nombre_completo }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $b->fecha->format('d/m/Y') }}
                                        @if($b->actividad) · {{ $b->actividad->titulo }} @endif
                                    </p>
                                </div>
                                <a href="{{ route('bitacoras.show', $b) }}" class="cy-btn-ghost text-xs py-1 px-3 shrink-0">Revisar</a>
                            </li>
                        @endforeach
                    </ul>
                    @if($bitacorasPendientes->count() > 8)
                        <div class="px-6 py-3 border-t border-slate-200 dark:border-slate-800">
                            <a href="{{ route('bitacoras.index') }}" class="text-sm text-brand-600 dark:text-brand-400 hover:underline">
                                Ver todas ({{ $bitacorasPendientes->count() }})
                            </a>
                        </div>
                    @endif
                </section>
            @endif

            {{-- ── Próximas actividades ────────────────────── --}}
            <section class="cy-card overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="cy-section-title">Próximas actividades</h2>
                </div>
                @if($proximasActividades->isEmpty())
                    <p class="px-6 py-8 text-sm text-slate-500 dark:text-slate-400">Sin actividades próximas.</p>
                @else
                    <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($proximasActividades as $a)
                            <li class="px-6 py-3 flex items-center justify-between gap-4 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <div class="min-w-0">
                                    <a href="{{ route('actividades.show', $a) }}"
                                       class="font-medium text-slate-900 dark:text-slate-100 hover:text-brand-600 dark:hover:text-brand-400 truncate block">
                                        {{ $a->titulo }}
                                    </a>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $a->fecha->format('d/m/Y') }} · {{ config('ppe.fases.'.$a->fase, $a->fase) }}
                                    </div>
                                </div>
                                <span class="cy-badge-cyan shrink-0">{{ $a->horas_asignadas }}h</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="px-6 py-3 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('actividades.index') }}" class="text-sm text-brand-600 dark:text-brand-400 hover:underline">Ver todas</a>
                    </div>
                @endif
            </section>

        @endif
    </div>
</x-app-layout>
