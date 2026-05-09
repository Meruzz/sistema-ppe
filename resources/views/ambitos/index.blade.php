<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="cy-page-title">Ámbitos PPE</h1>
                <p class="cy-page-subtitle">Las 5 áreas de acción oficiales del Programa de Participación Estudiantil.</p>
            </div>
            <a href="{{ route('ambitos.create') }}" class="cy-btn-primary">+ Nuevo ámbito</a>
        </div>
    </x-slot>

    <div class="cy-card">
        {{-- Filtro --}}
        <form method="GET" class="mb-4">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar ámbito…"
                   class="cy-input max-w-xs text-sm">
        </form>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                        <th class="text-left py-3 px-2 font-medium text-slate-500 dark:text-slate-400">Ámbito</th>
                        <th class="text-left py-3 px-2 font-medium text-slate-500 dark:text-slate-400">Código</th>
                        <th class="text-left py-3 px-2 font-medium text-slate-500 dark:text-slate-400">Descripción</th>
                        <th class="text-left py-3 px-2 font-medium text-slate-500 dark:text-slate-400">Estado</th>
                        <th class="py-3 px-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($ambitos as $a)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-2">
                                    @php
                                        $colorClasses = [
                                            'blue'    => 'bg-blue-500',
                                            'green'   => 'bg-green-500',
                                            'emerald' => 'bg-emerald-500',
                                            'amber'   => 'bg-amber-500',
                                            'rose'    => 'bg-rose-500',
                                        ];
                                        $dot = $colorClasses[$a->color] ?? 'bg-slate-400';
                                    @endphp
                                    <span class="inline-block w-2.5 h-2.5 rounded-full {{ $dot }}"></span>
                                    <span class="font-medium text-slate-900 dark:text-slate-100">{{ $a->nombre }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-2 font-mono text-slate-500 dark:text-slate-400">{{ $a->codigo ?? '—' }}</td>
                            <td class="py-3 px-2 text-slate-600 dark:text-slate-300 max-w-xs truncate">{{ $a->descripcion ?? '—' }}</td>
                            <td class="py-3 px-2">
                                @if($a->activo)
                                    <span class="cy-badge-green">Activo</span>
                                @else
                                    <span class="cy-badge-gray">Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3 px-2 text-right whitespace-nowrap">
                                <a href="{{ route('ambitos.edit', $a) }}" class="cy-btn-ghost text-xs py-1 px-2">Editar</a>
                                <button type="button"
                                        @click="$dispatch('confirm-delete', {
                                            url: '{{ route('ambitos.destroy', $a) }}',
                                            name: '{{ addslashes($a->nombre) }}'
                                        })"
                                        class="cy-btn-danger text-xs py-1 px-2 ms-1">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400 dark:text-slate-500">
                                No se encontraron ámbitos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $ambitos->links() }}</div>
    </div>
</x-app-layout>
