<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Editar Alumno</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('alumnos.update', $alumno) }}">
                @method('PUT')
                @include('alumnos._form', ['alumno' => $alumno])
            </form>
        </div>
    </div>
</x-app-layout>
