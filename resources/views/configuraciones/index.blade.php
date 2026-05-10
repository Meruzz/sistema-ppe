<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="cy-page-title">Configuración</h1>
            <p class="cy-page-subtitle">Parámetros institucionales, PDF y notificaciones por correo.</p>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto safe-px">

        @if(session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('configuraciones.update') }}">
            @csrf
            @method('PUT')

            {{-- ── Institución ────────────────────────────── --}}
            <div class="cy-card mb-6">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">Institución</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Aparece en encabezados de PDF y correos.</p>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($grupos->get('institucion', collect()) as $cfg)
                        <div>
                            <label for="{{ $cfg->clave }}" class="cy-label">{{ $cfg->descripcion }}</label>
                            <input id="{{ $cfg->clave }}"
                                   type="text"
                                   name="{{ $cfg->clave }}"
                                   value="{{ old($cfg->clave, $cfg->valor) }}"
                                   class="cy-input">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── PDF ────────────────────────────────────── --}}
            <div class="cy-card mb-6">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">Reportes PDF</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Controla qué secciones aparecen en los certificados.</p>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($grupos->get('pdf', collect()) as $cfg)
                        @if(in_array($cfg->clave, ['pdf_firmas', 'pdf_actividades']))
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       name="{{ $cfg->clave }}"
                                       value="1"
                                       {{ old($cfg->clave, $cfg->valor) == '1' ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-sm text-slate-700 dark:text-slate-300">{{ $cfg->descripcion }}</span>
                            </label>
                        @else
                            <div>
                                <label for="{{ $cfg->clave }}" class="cy-label">{{ $cfg->descripcion }}</label>
                                <input id="{{ $cfg->clave }}"
                                       type="text"
                                       name="{{ $cfg->clave }}"
                                       value="{{ old($cfg->clave, $cfg->valor) }}"
                                       class="cy-input">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- ── Email ───────────────────────────────────── --}}
            <div class="cy-card mb-6">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">Notificaciones por correo</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">El sistema envía correos al alumno y al representante en los hitos configurados.</p>
                </div>
                <div class="p-6 space-y-4">
                    @php
                        $emailAdmin = $grupos->get('email', collect())->firstWhere('clave', 'email_admin');
                        $toggles    = $grupos->get('email', collect())->filter(fn($c) => $c->clave !== 'email_admin');
                    @endphp

                    @if($emailAdmin)
                        <div>
                            <label for="email_admin" class="cy-label">{{ $emailAdmin->descripcion }}</label>
                            <input id="email_admin"
                                   type="email"
                                   name="email_admin"
                                   value="{{ old('email_admin', $emailAdmin->valor) }}"
                                   placeholder="admin@institución.edu.ec"
                                   class="cy-input">
                        </div>
                    @endif

                    <div class="border-t border-slate-100 dark:border-slate-800 pt-4 space-y-3">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Activar notificaciones</p>
                        @foreach($toggles as $cfg)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       name="{{ $cfg->clave }}"
                                       value="1"
                                       {{ old($cfg->clave, $cfg->valor) == '1' ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-sm text-slate-700 dark:text-slate-300">{{ $cfg->descripcion }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="cy-btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
