<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">{{ $docente->nombre_completo }}</h1>
                <p class="cy-page-subtitle">Perfil del docente y grupos a cargo.</p>
            </div>
            <a href="{{ route('docentes.edit', $docente) }}" class="cy-btn-primary">Editar</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto safe-px space-y-6">

        <section class="cy-card p-6">
            <div class="flex flex-wrap gap-2 mb-4">
                @if($docente->es_coordinador)
                    <span class="cy-badge-amber">Coordinador PPE</span>
                @else
                    <span class="cy-badge-muted">Facilitador</span>
                @endif
                @if($docente->activo)
                    <span class="cy-badge-green">Activo</span>
                @else
                    <span class="cy-badge-muted">Inactivo</span>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <div class="cy-label">Cédula</div>
                    <div class="font-mono text-slate-800 dark:text-slate-200">{{ $docente->cedula }}</div>
                </div>
                <div>
                    <div class="cy-label">Email</div>
                    <div class="font-mono text-xs break-all text-slate-700 dark:text-slate-300">{{ $docente->user->email }}</div>
                </div>
                <div>
                    <div class="cy-label">Especialidad</div>
                    <div class="text-slate-600 dark:text-slate-400">{{ $docente->especialidad ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">Teléfono</div>
                    <div class="text-slate-600 dark:text-slate-400">{{ $docente->telefono ?? '—' }}</div>
                </div>
            </div>
        </section>

        <section class="cy-card overflow-hidden">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                Grupos a cargo <span class="cy-badge-cyan ms-2">{{ $docente->grupos->count() }}</span>
            </h2>
            @if($docente->grupos->isEmpty())
                <p class="px-6 py-6 text-sm text-slate-500 dark:text-slate-400">Sin grupos asignados.</p>
            @else
                <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($docente->grupos as $g)
                        <li class="px-6 py-3 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <a href="{{ route('grupos.show', $g) }}" class="text-brand-600 dark:text-brand-400 hover:underline font-medium">{{ $g->nombre }}</a>
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                <span class="cy-badge-cyan">{{ $g->alumnos->count() }}</span> alumnos
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-app-layout>
