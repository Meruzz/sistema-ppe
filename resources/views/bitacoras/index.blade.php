<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Bitácora</h1>
                <p class="cy-page-subtitle">Registro de actividades y reflexiones del PPE.</p>
            </div>
            @role('alumno')
                <a href="{{ route('bitacoras.create') }}" class="cy-btn-primary">+ Nueva entrada</a>
            @endrole
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto safe-px">

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="cy-card overflow-hidden">
            @if($bitacoras->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                    @role('alumno')
                        Aún no has escrito ninguna entrada. Selecciona una actividad completada para comenzar.
                    @else
                        No hay entradas de bitácora para mostrar.
                    @endrole
                </p>
            @else
                <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($bitacoras as $b)
                        <li class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    @hasanyrole('administrador|docente')
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-0.5">{{ $b->alumno->nombre_completo }}</p>
                                    @endhasanyrole
                                    <a href="{{ route('bitacoras.show', $b) }}"
                                       class="font-medium text-brand-600 dark:text-brand-400 hover:underline">
                                        {{ $b->fecha->format('d/m/Y') }}
                                        @if($b->actividad)
                                            · {{ $b->actividad->titulo }}
                                        @endif
                                    </a>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 line-clamp-2">
                                        {{ $b->contenido }}
                                    </p>
                                </div>
                                <div class="shrink-0 flex flex-col items-end gap-1.5">
                                    @if($b->revisada)
                                        <span class="cy-badge-green">Revisada</span>
                                        @if($b->calificacion !== null)
                                            <span class="cy-badge-cyan text-xs">{{ number_format($b->calificacion, 1) }}/10</span>
                                        @endif
                                    @else
                                        <span class="cy-badge-muted">Pendiente</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $bitacoras->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
