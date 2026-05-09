<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Bitácora — {{ $bitacora->fecha->format('d/m/Y') }}</h1>
                <p class="cy-page-subtitle">{{ $bitacora->alumno->nombre_completo }}</p>
            </div>
            <div class="flex gap-2">
                @role('alumno')
                    @unless($bitacora->revisada)
                        <a href="{{ route('bitacoras.edit', $bitacora) }}" class="cy-btn-ghost">Editar</a>
                    @endunless
                @endrole
                <a href="{{ route('bitacoras.index') }}" class="cy-btn-ghost">Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto safe-px space-y-6">

        @if(session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Contenido del alumno --}}
        <section class="cy-card p-6 space-y-5">
            @if($bitacora->actividad)
                <div>
                    <div class="cy-label">Actividad</div>
                    <a href="{{ route('actividades.show', $bitacora->actividad) }}"
                       class="text-brand-600 dark:text-brand-400 hover:underline mt-1 block">
                        {{ $bitacora->actividad->titulo }}
                        @if($bitacora->actividad->grupo)
                            <span class="text-slate-400 font-normal"> · {{ $bitacora->actividad->grupo->nombre }}</span>
                        @endif
                    </a>
                </div>
            @endif

            <div>
                <div class="cy-label">¿Qué hice? ¿Qué observé?</div>
                <p class="mt-2 text-sm text-slate-800 dark:text-slate-200 whitespace-pre-wrap">{{ $bitacora->contenido }}</p>
            </div>

            @if($bitacora->aprendizajes)
                <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                    <div class="cy-label">¿Qué aprendí? ¿Qué mejoraría?</div>
                    <p class="mt-2 text-sm text-slate-800 dark:text-slate-200 whitespace-pre-wrap">{{ $bitacora->aprendizajes }}</p>
                </div>
            @endif
        </section>

        {{-- Revisión del docente --}}
        @hasanyrole('administrador|docente')
            <section class="cy-card p-6">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4">Calificación del docente</h2>

                @if($bitacora->revisada)
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="cy-badge-green">Revisada</span>
                            <span class="cy-badge-cyan text-sm">{{ number_format($bitacora->calificacion, 1) }} / 10</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                por {{ $bitacora->revisor->nombre_completo ?? '—' }}
                                el {{ $bitacora->revisado_en->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        @if($bitacora->observaciones_docente)
                            <p class="text-sm text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                                {{ $bitacora->observaciones_docente }}
                            </p>
                        @endif

                        <form method="POST" action="{{ route('bitacoras.revisar', $bitacora) }}" class="pt-2 border-t border-slate-100 dark:border-slate-800">
                            @csrf
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Actualizar calificación</p>
                            @include('bitacoras._form_revision', ['bitacora' => $bitacora])
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('bitacoras.revisar', $bitacora) }}">
                        @csrf
                        @include('bitacoras._form_revision', ['bitacora' => $bitacora])
                    </form>
                @endif
            </section>
        @endhasanyrole

        {{-- Vista del alumno: estado de revisión --}}
        @role('alumno')
            <section class="cy-card p-4">
                @if($bitacora->revisada)
                    <div class="flex items-center gap-3">
                        <span class="cy-badge-green">Revisada</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                            {{ number_format($bitacora->calificacion, 1) }} / 10
                        </span>
                    </div>
                    @if($bitacora->observaciones_docente)
                        <p class="mt-3 text-sm text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                            <span class="block text-xs text-slate-400 mb-1">Comentario del docente</span>
                            {{ $bitacora->observaciones_docente }}
                        </p>
                    @endif
                @else
                    <span class="cy-badge-muted">Pendiente de revisión</span>
                @endif
            </section>
        @endrole
    </div>
</x-app-layout>
