<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
        <meta name="theme-color" content="#f8fafc" media="(prefers-color-scheme: light)">

        <title>{{ config('app.name') }} · {{ $title ?? 'Panel' }}</title>

        <script>
            (function () {
                try {
                    var saved = localStorage.getItem('theme');
                    var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.classList.toggle('dark', saved ? saved === 'dark' : prefersDark);
                } catch (e) {}
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full antialiased">
        <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 cy-btn-primary">
            Saltar al contenido
        </a>

        <div class="min-h-screen flex flex-col">
            @auth
                @include('layouts.navigation')
            @endauth

            @isset($header)
                <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                    <div class="max-w-7xl mx-auto py-6 safe-px">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main id="main" class="flex-1" x-data>
                <div aria-live="polite" aria-atomic="true" class="sr-only" id="cy-live"></div>

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4500)"
                         class="max-w-7xl mx-auto safe-px mt-4">
                        <div role="status"
                             class="cy-card p-3 border-l-4 border-l-emerald-500 text-sm flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="max-w-7xl mx-auto safe-px mt-4">
                        <div role="alert" class="cy-card p-4 border-l-4 border-l-red-500 text-sm">
                            <p class="font-medium text-red-700 dark:text-red-300 mb-1">Hay errores en el formulario:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-700 dark:text-slate-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <footer class="border-t border-slate-200 dark:border-slate-800 py-5 mt-auto">
                <div class="max-w-7xl mx-auto safe-px text-xs text-slate-500 dark:text-slate-400 flex justify-between items-center flex-wrap gap-2">
                    <span>© {{ date('Y') }} Sistema PPE</span>
                    <span>{{ config('ppe.institucion') }}</span>
                </div>
            </footer>
        </div>

        <x-confirm-modal />

        @stack('scripts')
    </body>
</html>
