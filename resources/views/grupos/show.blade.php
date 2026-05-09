<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">{{ $grupo->nombre }}</h1>
                <p class="cy-page-subtitle">{{ $grupo->materia->nombre ?? 'Sin materia' }} · {{ $grupo->anio_lectivo }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reportes.grupo', $grupo) }}" target="_blank" class="cy-btn-ghost">Reporte PDF</a>
                <a href="{{ route('grupos.edit', $grupo) }}" class="cy-btn-primary">Editar</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto safe-px space-y-6">

        <section class="cy-card p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <div class="cy-label">Materia</div>
                    <div class="text-slate-800 dark:text-slate-200">{{ $grupo->materia->nombre ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">Docente</div>
                    <div class="text-slate-800 dark:text-slate-200">{{ $grupo->docente->nombre_completo ?? '—' }}</div>
                </div>
                <div>
                    <div class="cy-label">Año lectivo</div>
                    <div class="font-mono text-slate-800 dark:text-slate-200">{{ $grupo->anio_lectivo }}</div>
                </div>
            </div>
            @if($grupo->descripcion)
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">{{ $grupo->descripcion }}</p>
            @endif
        </section>

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
                                <th class="text-right px-6 py-3">Progreso</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($grupo->alumnos as $a)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $a->cedula }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('alumnos.show', $a) }}" class="text-brand-600 dark:text-brand-400 hover:underline">{{ $a->nombre_completo }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $a->anio_bachillerato }} {{ $a->paralelo }}</td>
                                    <td class="px-6 py-3 text-right">
                                        <span class="cy-badge-cyan">{{ $a->progreso_horas }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="cy-card overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Actividades del grupo</h2>
                <a href="{{ route('actividades.create') }}?grupo_id={{ $grupo->id }}" class="text-brand-600 dark:text-brand-400 hover:underline text-sm">+ Nueva actividad</a>
            </div>
            @if($grupo->actividades->isEmpty())
                <p class="px-6 py-6 text-sm text-slate-500 dark:text-slate-400">Sin actividades registradas.</p>
            @else
                <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($grupo->actividades as $a)
                        <li class="px-6 py-3 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <a href="{{ route('actividades.show', $a) }}" class="text-brand-600 dark:text-brand-400 hover:underline font-medium">{{ $a->titulo }}</a>
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $a->fecha->format('d/m/Y') }} · {{ $a->horas_asignadas }}h ·
                                <span class="cy-badge-muted">{{ ucfirst(str_replace('_',' ',$a->estado)) }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-app-layout>
