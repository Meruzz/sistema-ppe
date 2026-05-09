<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">{{ $grupo->nombre }}</h1>
                <p class="cy-page-subtitle">
                    {{ $grupo->anio_bachillerato }} Bachillerato
                    @if($grupo->ambito) · {{ $grupo->ambito->nombre }} @endif
                    · {{ $grupo->anio_lectivo }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reportes.grupo', $grupo) }}" target="_blank" class="cy-btn-ghost">Reporte PDF</a>
                <a href="{{ route('grupos.edit', $grupo) }}" class="cy-btn-primary">Editar</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto safe-px space-y-6">

        {{-- Info del grupo --}}
        <section class="cy-card p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <div class="cy-label">Año bachillerato</div>
                    <span class="{{ $grupo->anio_bachillerato === '1ro' ? 'cy-badge-cyan' : 'cy-badge-yellow' }} mt-1">
                        {{ $grupo->anio_bachillerato }} · 80 h
                    </span>
                </div>
                <div>
                    <div class="cy-label">Ámbito PPE</div>
                    @if($grupo->ambito)
                        @php
                            $dotColor = ['blue'=>'bg-blue-500','green'=>'bg-green-500','emerald'=>'bg-emerald-500','amber'=>'bg-amber-500','rose'=>'bg-rose-500'][$grupo->ambito->color] ?? 'bg-slate-400';
                        @endphp
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="w-2.5 h-2.5 rounded-full {{ $dotColor }}"></span>
                            <span class="text-slate-800 dark:text-slate-200">{{ $grupo->ambito->nombre }}</span>
                        </div>
                    @else
                        <div class="text-slate-400 mt-1">Sin ámbito</div>
                    @endif
                </div>
                <div>
                    <div class="cy-label">Docente facilitador</div>
                    <div class="text-slate-800 dark:text-slate-200 mt-1">{{ $grupo->docente->nombre_completo ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">Año lectivo</div>
                    <div class="font-mono text-slate-800 dark:text-slate-200 mt-1">{{ $grupo->anio_lectivo }}</div>
                </div>
            </div>
            @if($grupo->descripcion)
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">{{ $grupo->descripcion }}</p>
            @endif
        </section>

        {{-- Alumnos --}}
        <section class="cy-card overflow-hidden">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                Alumnos <span class="cy-badge-cyan ms-2">{{ $grupo->alumnos->count() }}</span>
            </h2>
            @if($grupo->alumnos->isEmpty())
                <p class="px-6 py-6 text-sm text-slate-500 dark:text-slate-400">Sin alumnos asignados.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            <tr>
                                <th class="text-left px-6 py-3">Cédula</th>
                                <th class="text-left px-4 py-3">Nombre</th>
                                <th class="text-left px-4 py-3">Año</th>
                                <th class="text-right px-4 py-3">Horas {{ $grupo->anio_bachillerato }}</th>
                                <th class="text-right px-6 py-3">Nota</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($grupo->alumnos as $a)
                                @php
                                    $horas = $grupo->anio_bachillerato === '1ro' ? $a->horas_completadas_1ro : $a->horas_completadas_2do;
                                    $nota  = $grupo->anio_bachillerato === '1ro' ? $a->calificacion_1ro    : $a->calificacion_2do;
                                    $notaMinima = config('ppe.nota_minima', 7.0);
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $a->cedula }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('alumnos.show', $a) }}" class="text-brand-600 dark:text-brand-400 hover:underline">{{ $a->nombre_completo }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $a->anio_bachillerato }} {{ $a->paralelo }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="cy-badge-cyan">{{ $horas }}h / 80h</span>
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <span class="{{ $nota >= $notaMinima ? 'cy-badge-green' : 'cy-badge-magenta' }}">
                                            {{ number_format($nota, 2) }}/10
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Actividades --}}
        <section class="cy-card overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Actividades del grupo</h2>
                <a href="{{ route('actividades.create') }}?grupo_id={{ $grupo->id }}" class="text-brand-600 dark:text-brand-400 hover:underline text-sm">+ Nueva actividad</a>
            </div>
            @if($grupo->actividades->isEmpty())
                <p class="px-6 py-6 text-sm text-slate-500 dark:text-slate-400">Sin actividades registradas.</p>
            @else
                <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($grupo->actividades as $act)
                        <li class="px-6 py-3 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <a href="{{ route('actividades.show', $act) }}" class="text-brand-600 dark:text-brand-400 hover:underline font-medium">{{ $act->titulo }}</a>
                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                @if($act->fase)
                                    @php $faseLabel = config('ppe.fases')[$act->fase] ?? $act->fase; @endphp
                                    <span class="cy-badge-muted">{{ $faseLabel }}</span>
                                @endif
                                <span>{{ $act->fecha->format('d/m/Y') }}</span>
                                <span class="cy-badge-cyan">{{ $act->horas_asignadas }}h</span>
                                <span class="{{ $act->estado === 'completada' ? 'cy-badge-green' : 'cy-badge-muted' }}">
                                    {{ ucfirst(str_replace('_', ' ', $act->estado)) }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-app-layout>
