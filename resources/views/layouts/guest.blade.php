<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
        <meta name="theme-color" content="#f8fafc" media="(prefers-color-scheme: light)">

        <title>{{ config('app.name') }} · Acceso</title>

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
        <div class="min-h-screen flex flex-col items-center justify-center safe-px py-12">

            <div class="w-full max-w-md">

                {{-- Brand --}}
                <header class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-600 text-white mb-4">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c0 2 4 3 6 3s6-1 6-3v-5"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Sistema PPE</h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ config('ppe.institucion') }}</p>
                </header>

                <div class="cy-card p-6 sm:p-8">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
                    © {{ date('Y') }} · Acceso autorizado únicamente
                </p>
            </div>
        </div>
    </body>
</html>
