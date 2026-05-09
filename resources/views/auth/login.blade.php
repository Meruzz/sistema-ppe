<x-guest-layout>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Iniciar sesión</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ingresa con tu cuenta institucional.</p>
    </header>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="cy-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   autocomplete="username" inputmode="email" spellcheck="false"
                   placeholder="usuario@ppe.edu.ec"
                   class="cy-input" required autofocus>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="cy-label">Contraseña</label>
            <input id="password" type="password" name="password"
                   autocomplete="current-password" class="cy-input" required>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-300">Recuérdame</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brand-600 dark:text-brand-400 hover:underline"
                   href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="cy-btn-primary w-full justify-center">
            Iniciar sesión
        </button>
    </form>
</x-guest-layout>
