<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Grupos</h1>
                <p class="cy-page-subtitle">Grupos del Programa de Participación Estudiantil.</p>
            </div>
            <a href="{{ route('grupos.create') }}" class="cy-btn-primary">+ Nuevo grupo</a>
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
                    <label for="anio" class="cy-label">Año bachillerato</label>
                    <select id="anio" name="anio" class="cy-select">
                        <option value="">Todos</option>
                        <option value="1ro" @selected(request('anio')==='1ro')>1ro Bachillerato</option>
                        <option value="2do" @selected(request('anio')==='2do')>2do Bachillerato</option>
                    </select>
                </div>
                <button class="cy-btn-ghost">Aplicar</button>
            </form>

            @if(session('success'))
                <div class="mx-4 mt-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm border border-green-200 dark:border-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3">Grupo</th>
                            <th class="text-left px-4 py-3">Año Bach.</th>
                            <th class="text-left px-4 py-3">Ámbito PPE</th>
                            <th class="text-left px-4 py-3">Docente</th>
                            <th class="text-left px-4 py-3">Año lectivo</th>
                            <th class="text-left px-4 py-3">Alumnos</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($grupos as $g)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ $g->nombre }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $g->anio_bachillerato === '1ro' ? 'cy-badge-cyan' : 'cy-badge-yellow' }}">
                                        {{ $g->anio_bachillerato }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                                    @if($g->ambito)
                                        @php
                                            $dotColor = [
                                                'blue'    => 'bg-blue-500',
                                                'green'   => 'bg-green-500',
                                                'emerald' => 'bg-emerald-500',
                                                'amber'   => 'bg-amber-500',
                                                'rose'    => 'bg-rose-500',
                                            ][$g->ambito->color] ?? 'bg-slate-400';
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                                            {{ $g->ambito->nombre }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $g->docente->nombre_completo ?? '—' }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $g->anio_lectivo }}</td>
                                <td class="px-4 py-3"><span class="cy-badge-cyan">{{ $g->alumnos_count }}</span></td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-sm">
                                    <a href="{{ route('grupos.show', $g) }}" class="text-brand-600 dark:text-brand-400 hover:underline">Ver</a>
                                    <a href="{{ route('grupos.edit', $g) }}" class="text-slate-600 dark:text-slate-300 hover:underline ms-3">Editar</a>
                                    <button type="button"
                                            @click="$dispatch('confirm-delete', {
                                                url: '{{ route('grupos.destroy', $g) }}',
                                                name: '{{ addslashes($g->nombre) }}'
                                            })"
                                            class="text-red-600 dark:text-red-400 hover:underline ms-3">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Sin grupos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">{{ $grupos->links() }}</div>
        </div>
    </div>
</x-app-layout>
