<nav x-data="{ open: false }"
     class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto safe-px">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-brand-600 text-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c0 2 4 3 6 3s6-1 6-3v-5"/>
                            </svg>
                        </span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100 hidden sm:inline">Sistema PPE</span>
                    </a>
                </div>

                {{-- Desktop nav --}}
                <div class="hidden sm:flex sm:items-center sm:ms-8 sm:gap-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>

                    @role('administrador')
                        <x-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')">Alumnos</x-nav-link>
                        <x-nav-link :href="route('docentes.index')" :active="request()->routeIs('docentes.*')">Docentes</x-nav-link>
                        <x-nav-link :href="route('ambitos.index')" :active="request()->routeIs('ambitos.*')">Ámbitos</x-nav-link>
                        <x-nav-link :href="route('anio-lectivos.index')" :active="request()->routeIs('anio-lectivos.*')">Años lectivos</x-nav-link>
                        <x-nav-link :href="route('configuraciones.index')" :active="request()->routeIs('configuraciones.*')">Configuración</x-nav-link>
                    @endrole

                    @hasanyrole('administrador|docente')
                        <x-nav-link :href="route('grupos.index')" :active="request()->routeIs('grupos.*')">Grupos</x-nav-link>
                        <x-nav-link :href="route('actividades.index')" :active="request()->routeIs('actividades.*')">Actividades</x-nav-link>
                    @endhasanyrole
                    <x-nav-link :href="route('bitacoras.index')" :active="request()->routeIs('bitacoras.*')">Bitácora</x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                {{-- Theme toggle --}}
                <button type="button"
                        x-data="{
                            dark: document.documentElement.classList.contains('dark'),
                            toggle() {
                                this.dark = !this.dark;
                                document.documentElement.classList.toggle('dark', this.dark);
                                localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                            }
                        }"
                        @click="toggle()"
                        :aria-pressed="dark.toString()"
                        aria-label="Cambiar tema claro/oscuro"
                        class="p-2 rounded-md text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <svg x-show="!dark" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                    </svg>
                    <svg x-show="dark" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </button>

                {{-- User dropdown --}}
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button type="button"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-brand-100 text-brand-700 dark:bg-brand-900/50 dark:text-brand-300 text-xs font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </span>
                            <span class="text-sm text-slate-700 dark:text-slate-200 max-w-[140px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.25 4.4a.75.75 0 01-1.08 0l-4.25-4.4a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                            @php $rol = Auth::user()->roles->pluck('name')->first(); @endphp
                            @if($rol)
                                <span class="cy-badge-cyan mt-2">{{ ucfirst($rol) }}</span>
                            @endif
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">Mi perfil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        :aria-expanded="open.toString()"
                        aria-controls="mobile-menu"
                        aria-label="Abrir menú"
                        class="p-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-slate-200 dark:border-slate-800" id="mobile-menu">
        <div class="py-2 space-y-0.5">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            @role('administrador')
                <x-responsive-nav-link :href="route('alumnos.index')">Alumnos</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docentes.index')">Docentes</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ambitos.index')">Ámbitos</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('anio-lectivos.index')">Años lectivos</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('configuraciones.index')">Configuración</x-responsive-nav-link>
            @endrole
            @hasanyrole('administrador|docente')
                <x-responsive-nav-link :href="route('grupos.index')">Grupos</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('actividades.index')">Actividades</x-responsive-nav-link>
            @endhasanyrole
            <x-responsive-nav-link :href="route('bitacoras.index')">Bitácora</x-responsive-nav-link>
        </div>
        <div class="py-3 border-t border-slate-200 dark:border-slate-800">
            <div class="px-4 mb-2">
                <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
            </div>
            <x-responsive-nav-link :href="route('profile.edit')">Mi perfil</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    Cerrar sesión
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
