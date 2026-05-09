<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Materias</h1>
                <p class="cy-page-subtitle">Áreas de conocimiento del programa.</p>
            </div>
            <a href="{{ route('materias.create') }}" class="cy-btn-primary">Nueva materia</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto safe-px">
        <div class="cy-card overflow-hidden">
            <form method="GET" class="p-4 border-b border-slate-200 dark:border-slate-800">
                <label for="q" class="cy-label">Buscar</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}"
                       placeholder="Nombre…" class="cy-input max-w-md" autocomplete="off">
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3">Código</th>
                            <th class="text-left px-4 py-3">Materia</th>
                            <th class="text-left px-4 py-3">Estado</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($materias as $m)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $m->codigo ?? '—' }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ $m->nombre }}</td>
                                <td class="px-4 py-3">
                                    @if($m->activo)<span class="cy-badge-yellow">Activa</span>
                                    @else<span class="cy-badge-muted">Inactiva</span>@endif
                                </td>
                                <td class="px-4 py-3 text-right text-sm">
                                    <a href="{{ route('materias.edit', $m) }}" class="text-brand-600 dark:text-brand-400 hover:underline">Editar</a>
                                    <button type="button"
                                            @click="$dispatch('open-confirm', { action: '{{ route('materias.destroy', $m) }}', message: 'Se eliminará la materia «{{ $m->nombre }}». Esta acción no se puede deshacer.' })"
                                            class="text-red-600 dark:text-red-400 hover:underline ms-3">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400">No hay materias registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">{{ $materias->links() }}</div>
        </div>
    </div>
</x-app-layout>
