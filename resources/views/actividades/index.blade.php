<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Actividades</h1>
                <p class="cy-page-subtitle">Registro de actividades y control de asistencia.</p>
            </div>
            <a href="{{ route('actividades.create') }}" class="cy-btn-primary">+ Nueva actividad</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto safe-px">
        <div class="cy-card overflow-hidden">
            <form method="GET" class="p-4 border-b border-slate-200 dark:border-slate-800 flex flex-wrap gap-2 items-end">
                <div class="flex-1 min-w-48">
                    <label for="q" class="cy-label">Buscar</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}" class="cy-input" autocomplete="off">
                </div>
                <div>
                    <label for="estado" class="cy-label">Estado</label>
                    <select id="estado" name="estado" class="cy-select">
                        <option value="">Todos</option>
                        @foreach(['planificada','en_curso','completada','cancelada'] as $e)
                            <option value="{{ $e }}" @selected(request('estado')===$e)>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="grupo_id" class="cy-label">Grupo</label>
                    <select id="grupo_id" name="grupo_id" class="cy-select">
                        <option value="">Todos</option>
                        @foreach($grupos as $g)
                            <option value="{{ $g->id }}" @selected(request('grupo_id')==$g->id)>{{ $g->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="cy-btn-ghost">Aplicar</button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3">Fecha</th>
                            <th class="text-left px-4 py-3">Título</th>
                            <th class="text-left px-4 py-3">Grupo</th>
                            <th class="text-left px-4 py-3">Horas</th>
                            <th class="text-left px-4 py-3">Estado</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($actividades as $a)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $a->fecha->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ $a->titulo }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $a->grupo->nombre }}</td>
                                <td class="px-4 py-3"><span class="cy-badge-cyan">{{ $a->horas_asignadas }}h</span></td>
                                <td class="px-4 py-3">
                                    @php
                                        $b = match($a->estado) {
                                            'completada' => 'cy-badge-yellow',
                                            'en_curso'   => 'cy-badge-cyan',
                                            'cancelada'  => 'cy-badge-magenta',
                                            default      => 'cy-badge-muted',
                                        };
                                    @endphp
                                    <span class="{{ $b }}">{{ ucfirst(str_replace('_',' ',$a->estado)) }}</span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-sm">
                                    <a href="{{ route('actividades.show', $a) }}" class="text-brand-600 dark:text-brand-400 hover:underline">Ver</a>
                                    <a href="{{ route('actividades.edit', $a) }}" class="text-slate-600 dark:text-slate-300 hover:underline ms-3">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Sin actividades registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">{{ $actividades->links() }}</div>
        </div>
    </div>
</x-app-layout>
