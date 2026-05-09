<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Editar año lectivo — {{ $anioLectivo->nombre }}</h1>
    </x-slot>
    <div class="py-8 max-w-2xl mx-auto safe-px">
        <div class="cy-card p-6">
            <form method="POST" action="{{ route('anio-lectivos.update', $anioLectivo) }}">
                @method('PUT')
                @include('anio_lectivos._form', ['anioLectivo' => $anioLectivo])
            </form>
        </div>
    </div>
</x-app-layout>
