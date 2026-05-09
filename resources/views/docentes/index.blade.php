<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Docentes</h1>
                <p class="cy-page-subtitle">Personal docente del programa.</p>
            </div>
            <a href="{{ route('docentes.create') }}" class="cy-btn-primary">Nuevo docente</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto safe-px">
        <div class="cy-card overflow-hidden">
            <form method="GET" class="p-4 border-b border-slate-200 dark:border-slate-800 flex gap-2 items-end">
                <div class="flex-1">
                    <label for="q" class="cy-label">Buscar</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}"
                           placeholder="Nombre, apellido o cédula…" class="cy-input" autocomplete="off" spellcheck="false">
                </div>
                <button class="cy-btn-ghost">Aplicar</button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3">Cédula</th>
                            <th class="text-left px-4 py-3">Docente</th>
                            <th class="text-left px-4 py-3">Especialidad</th>
                            <th class="text-left px-4 py-3">Email</th>
                            <th class="text-left px-4 py-3">Estado</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($docentes as $d)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $d->cedula }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ $d->apellidos }}, {{ $d->nombres }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $d->especialidad ?? '—' }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $d->user->email }}</td>
                                <td class="px-4 py-3">
                                    @if($d->activo)<span class="cy-badge-yellow">Activo</span>
                                    @else<span class="cy-badge-muted">Inactivo</span>@endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-sm">
                                    <a href="{{ route('docentes.show', $d) }}" class="text-brand-600 dark:text-brand-400 hover:underline">Ver</a>
                                    <a href="{{ route('docentes.edit', $d) }}" class="text-slate-600 dark:text-slate-300 hover:underline ms-3">Editar</a>
                                    <button type="button"
                                            @click="$dispatch('open-confirm', { action: '{{ route('docentes.destroy', $d) }}', message: 'Se eliminará al docente {{ $d->nombre_completo }}. Esta acción no se puede deshacer.' })"
                                            class="text-red-600 dark:text-red-400 hover:underline ms-3">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400">No hay docentes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">{{ $docentes->links() }}</div>
        </div>
    </div>
</x-app-layout>
