<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Importar alumnos desde CSV</h1>
        <p class="cy-page-subtitle">Carga masiva al inicio del año lectivo.</p>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto safe-px space-y-6">

        @if(session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('import_errores'))
            <div class="cy-card p-4 border-l-4 border-l-amber-400">
                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 mb-2">Filas con errores:</p>
                <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 list-disc list-inside">
                    @foreach(session('import_errores') as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="cy-card p-6">
            <form method="POST" action="{{ route('alumnos.importar.store') }}"
                  enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label for="archivo" class="cy-label">Archivo CSV <span class="text-red-500">*</span></label>
                    <input id="archivo" type="file" name="archivo" accept=".csv,.txt"
                           class="cy-input mt-1 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0
                                  file:text-xs file:font-medium file:bg-brand-50 file:text-brand-700
                                  dark:file:bg-brand-900/30 dark:file:text-brand-300" required>
                    @error('archivo') <p class="cy-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('alumnos.index') }}" class="cy-btn-ghost">Cancelar</a>
                    <button type="submit" class="cy-btn-primary">Importar</button>
                </div>
            </form>
        </div>

        <div class="cy-card p-6">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Formato del CSV</h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-3">
                La primera fila debe contener los encabezados. Columnas obligatorias:
            </p>
            <div class="overflow-x-auto">
                <table class="w-full text-xs border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        <tr>
                            <th class="text-left px-3 py-2">Columna</th>
                            <th class="text-left px-3 py-2">Obligatoria</th>
                            <th class="text-left px-3 py-2">Ejemplo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach([
                            ['cedula', 'Sí', '1712345678'],
                            ['nombres', 'Sí', 'María'],
                            ['apellidos', 'Sí', 'García'],
                            ['email', 'Sí', 'mgarcia@escuela.edu.ec'],
                            ['anio_bachillerato', 'Sí', '1ro o 2do'],
                            ['paralelo', 'No', 'A'],
                            ['fecha_nacimiento', 'No', '2008-05-15'],
                            ['representante', 'No', 'Carlos García'],
                            ['telefono', 'No', '0991234567'],
                        ] as [$col, $req, $ej])
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-3 py-2 font-mono text-slate-800 dark:text-slate-200">{{ $col }}</td>
                                <td class="px-3 py-2">
                                    @if($req === 'Sí')
                                        <span class="cy-badge-green">Sí</span>
                                    @else
                                        <span class="cy-badge-muted">No</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-slate-500 dark:text-slate-400">{{ $ej }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">
                La contraseña inicial de cada alumno será su número de cédula.
            </p>
        </div>
    </div>
</x-app-layout>
