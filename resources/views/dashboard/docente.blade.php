<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Panel docente</h1>
        <p class="cy-page-subtitle">Gestiona tus grupos y actividades asignadas.</p>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto safe-px space-y-6">
        @if(! $docente)
            <div role="alert" class="cy-card p-4 border-l-4 border-l-amber-500">
                <p class="text-sm">Tu cuenta no está vinculada a un perfil de docente. Contacta al administrador.</p>
            </div>
        @else
            <section class="cy-card p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $docente->nombre_completo }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $docente->especialidad ?? '—' }}</p>
            </section>

            <section class="cy-card p-6">
                <h3 class="cy-section-title mb-4">Mis grupos</h3>
                @if($grupos->isEmpty())
                    <p class="text-sm text-slate-500 dark:text-slate-400">Aún no tienes grupos asignados.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($grupos as $g)
                            <a href="{{ route('grupos.show', $g) }}"
                               class="block p-4 rounded-lg border border-slate-200 dark:border-slate-800 hover:border-brand-400 hover:shadow-soft-md transition-all">
                                <div class="font-medium text-slate-900 dark:text-slate-100">{{ $g->nombre }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    {{ $g->alumnos_count }} alumnos · {{ $g->anio_lectivo }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="cy-card p-6">
                <h3 class="cy-section-title mb-4">Próximas actividades</h3>
                @if($proximasActividades->isEmpty())
                    <p class="text-sm text-slate-500 dark:text-slate-400">Sin actividades próximas.</p>
                @else
                    <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($proximasActividades as $a)
                            <li class="py-3 flex items-center justify-between gap-4">
                                <a href="{{ route('actividades.show', $a) }}"
                                   class="font-medium text-slate-900 dark:text-slate-100 hover:text-brand-600 dark:hover:text-brand-400 truncate">
                                    {{ $a->titulo }}
                                </a>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $a->fecha->format('d/m/Y') }}</span>
                                    <span class="cy-badge-cyan">{{ $a->horas_asignadas }}h</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endif
    </div>
</x-app-layout>
