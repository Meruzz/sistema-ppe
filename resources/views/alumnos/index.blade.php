<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h1 class="cy-page-title">Alumnos</h1>
                <p class="cy-page-subtitle">Gestiona estudiantes registrados en el programa.</p>
            </div>
            <a href="{{ route('alumnos.create') }}" class="cy-btn-primary">Nuevo alumno</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto safe-px">
        <div class="cy-card overflow-hidden">

            {{-- Filtros --}}
            <form method="GET" class="p-4 border-b border-slate-200 dark:border-slate-800 flex flex-wrap gap-2 items-end">
                <div class="flex-1 min-w-48">
                    <label for="q" class="cy-label">Buscar</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}"
                           placeholder="Nombre, apellido o cédula…"
                           class="cy-input" autocomplete="off" spellcheck="false">
                </div>
                <div>
                    <label for="anio" class="cy-label">Año</label>
                    <select id="anio" name="anio" class="cy-select">
                        <option value="">Todos</option>
                        <option value="1ro" @selected(request('anio')==='1ro')>1ro</option>
                        <option value="2do" @selected(request('anio')==='2do')>2do</option>
                        <option value="3ro" @selected(request('anio')==='3ro')>3ro</option>
                    </select>
                </div>
                <button class="cy-btn-ghost" type="submit">Aplicar</button>
            </form>

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <caption class="sr-only">Listado de alumnos registrados</caption>
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3">Cédula</th>
                            <th class="text-left px-4 py-3">Alumno</th>
                            <th class="text-left px-4 py-3">Año</th>
                            <th class="text-left px-4 py-3 w-56">Progreso</th>
                            <th class="text-left px-4 py-3">Estado</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($alumnos as $a)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $a->cedula }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $a->apellidos }}, {{ $a->nombres }}</div>
                                </td>
                                <td class="px-4 py-3"><span class="cy-badge-cyan">{{ $a->anio_bachillerato }} {{ $a->paralelo }}</span></td>
                                <td class="px-4 py-3">
                                    <div class="cy-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $a->progreso_horas }}">
                                        <div class="cy-progress-bar" style="width:{{ $a->progreso_horas }}%"></div>
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $a->progreso_horas }}%</div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($a->activo)
                                        <span class="cy-badge-yellow">Activo</span>
                                    @else
                                        <span class="cy-badge-muted">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-sm">
                                    <a href="{{ route('alumnos.show', $a) }}" class="text-brand-600 dark:text-brand-400 hover:underline">Ver</a>
                                    <a href="{{ route('alumnos.edit', $a) }}" class="text-slate-600 dark:text-slate-300 hover:underline ms-3">Editar</a>
                                    <button type="button"
                                            @click="$dispatch('open-confirm', { action: '{{ route('alumnos.destroy', $a) }}', message: 'Se eliminará al alumno {{ $a->nombre_completo }}. Esta acción no se puede deshacer.' })"
                                            class="text-red-600 dark:text-red-400 hover:underline ms-3">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400">No hay alumnos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200 dark:border-slate-800">{{ $alumnos->links() }}</div>
        </div>
    </div>
</x-app-layout>
