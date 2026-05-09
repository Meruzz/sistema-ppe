<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Grupos</h1>
                <p class="cy-page-subtitle">Gestión de grupos de participación estudiantil.</p>
            </div>
            <a href="{{ route('grupos.create') }}" class="cy-btn-primary">+ Nuevo grupo</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto safe-px">
        <div class="cy-card overflow-hidden">
            <form method="GET" class="p-4 border-b border-slate-200 dark:border-slate-800">
                <label for="q" class="cy-label">Buscar</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" class="cy-input max-w-md" autocomplete="off">
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3">Grupo</th>
                            <th class="text-left px-4 py-3">Materia</th>
                            <th class="text-left px-4 py-3">Docente</th>
                            <th class="text-left px-4 py-3">Año</th>
                            <th class="text-left px-4 py-3">Alumnos</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($grupos as $g)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ $g->nombre }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $g->materia->nombre ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $g->docente->nombre_completo ?? '—' }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $g->anio_lectivo }}</td>
                                <td class="px-4 py-3"><span class="cy-badge-cyan">{{ $g->alumnos_count }}</span></td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-sm">
                                    <a href="{{ route('grupos.show', $g) }}" class="text-brand-600 dark:text-brand-400 hover:underline">Ver</a>
                                    <a href="{{ route('grupos.edit', $g) }}" class="text-slate-600 dark:text-slate-300 hover:underline ms-3">Editar</a>
                                    <button type="button"
                                            @click="$dispatch('open-confirm', { action: '{{ route('grupos.destroy', $g) }}', message: 'Se eliminará el grupo «{{ $g->nombre }}». Esta acción no se puede deshacer.' })"
                                            class="text-red-600 dark:text-red-400 hover:underline ms-3">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
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
