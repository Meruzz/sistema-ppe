<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Nuevo Docente</h2></x-slot>
    <div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('docentes.store') }}">
                @include('docentes._form', ['docente' => null])
            </form>
        </div>
    </div>
</x-app-layout>
