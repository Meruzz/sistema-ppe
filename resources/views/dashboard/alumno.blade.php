<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Mi progreso</h1>
        <p class="cy-page-subtitle">Avance del programa de participación estudiantil.</p>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto safe-px space-y-6">
        @if(! $alumno)
            <div role="alert" class="cy-card p-4 border-l-4 border-l-amber-500">
                <p class="text-sm text-slate-700 dark:text-slate-300">Tu cuenta aún no está vinculada a un perfil de alumno. Contacta al administrador.</p>
            </div>
        @else
            {{-- Hero progress --}}
            <section class="cy-card p-6 sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $alumno->nombre_completo }}</h2>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="cy-badge-cyan">{{ $alumno->anio_bachillerato }} {{ $alumno->paralelo }}</span>
                            <span class="cy-badge-muted">CI {{ $alumno->cedula }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-semibold text-slate-900 dark:text-slate-100 leading-none">
                            {{ number_format($horasCompletadas, 1) }}
                            <span class="text-base font-normal text-slate-500 dark:text-slate-400"> / {{ $meta }} h</span>
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            {{ $progreso }}% completado
                        </div>
                    </div>
                </div>

                <div class="mt-6 cy-progress" role="progressbar"
                     aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $progreso }}"
                     aria-label="Progreso del programa de participación estudiantil">
                    <div class="cy-progress-bar" style="width: {{ $progreso }}%"></div>
                </div>

                @if($progreso >= 100)
                    <p class="mt-4 text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                        ✓ Has completado el programa
                    </p>
                @elseif($progreso >= 80)
                    <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">
                        Te faltan {{ number_format($meta - $horasCompletadas, 1) }} horas para completar el PPE.
                    </p>
                @endif
            </section>

            {{-- Bitácoras pendientes --}}
            @if($actividadesSinBitacora->isNotEmpty())
                <section class="cy-card p-4 border-l-4 border-l-amber-400">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                Tienes {{ $actividadesSinBitacora->count() }} {{ $actividadesSinBitacora->count() === 1 ? 'actividad' : 'actividades' }} sin bitácora registrada.
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Documenta tu experiencia para que el docente pueda revisarla.</p>
                        </div>
                        <a href="{{ route('bitacoras.create') }}" class="cy-btn-primary shrink-0 text-sm">Escribir ahora</a>
                    </div>
                </section>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="cy-card p-6">
                    <h3 class="cy-section-title mb-4">Próximas actividades</h3>
                    @if($proximas->isEmpty())
                        <p class="text-sm text-slate-500 dark:text-slate-400">No tienes actividades próximas.</p>
                    @else
                        <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($proximas as $a)
                                <li class="py-3">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $a->titulo }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $a->fecha->format('d/m/Y') }} · {{ $a->horas_asignadas }}h
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <section class="cy-card p-6">
                    <h3 class="cy-section-title mb-4">Historial reciente</h3>
                    @if($historial->isEmpty())
                        <p class="text-sm text-slate-500 dark:text-slate-400">Aún no tienes actividades completadas.</p>
                    @else
                        <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($historial as $a)
                                <li class="py-3 flex justify-between items-center">
                                    <div class="min-w-0">
                                        <div class="font-medium text-slate-900 dark:text-slate-100 truncate">{{ $a->titulo }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $a->fecha->format('d/m/Y') }}</div>
                                    </div>
                                    <span class="cy-badge-yellow">+{{ $a->pivot->horas_confirmadas }}h</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>
        @endif
    </div>
</x-app-layout>
