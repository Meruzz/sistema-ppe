<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Años lectivos</h1>
                <p class="cy-page-subtitle">Gestión de ciclos escolares Sierra / Costa.</p>
            </div>
            <a href="{{ route('anio-lectivos.create') }}" class="cy-btn-primary">+ Nuevo año lectivo</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto safe-px">

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-sm border border-red-200 dark:border-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="cy-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-6 py-3">Año lectivo</th>
                            <th class="text-left px-4 py-3">Ciclo</th>
                            <th class="text-left px-4 py-3">Inicio</th>
                            <th class="text-left px-4 py-3">Fin</th>
                            <th class="text-left px-4 py-3">Grupos</th>
                            <th class="text-left px-4 py-3">Estado</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($anios as $a)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-6 py-3 font-mono font-medium text-slate-900 dark:text-slate-100">{{ $a->nombre }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $a->ciclo === 'sierra' ? 'cy-badge-cyan' : 'cy-badge-amber' }}">
                                        {{ ucfirst($a->ciclo) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $a->inicio?->format('d/m/Y') ?? '—' }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $a->fin?->format('d/m/Y') ?? '—' }}</td>
                                <td class="px-4 py-3"><span class="cy-badge-cyan">{{ $a->grupos_count }}</span></td>
                                <td class="px-4 py-3">
                                    @if($a->activo)
                                        <span class="cy-badge-green">Activo</span>
                                    @else
                                        <span class="cy-badge-muted">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-sm">
                                    <a href="{{ route('anio-lectivos.edit', $a) }}" class="text-slate-600 dark:text-slate-300 hover:underline">Editar</a>
                                    @if($a->grupos_count === 0)
                                        <button type="button"
                                                @click="$dispatch('confirm-delete', {
                                                    url: '{{ route('anio-lectivos.destroy', $a) }}',
                                                    name: '{{ $a->nombre }}'
                                                })"
                                                class="text-red-600 dark:text-red-400 hover:underline ms-3">
                                            Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Sin años lectivos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
