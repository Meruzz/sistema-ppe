<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Nuevo año lectivo</h1>
    </x-slot>
    <div class="py-8 max-w-2xl mx-auto safe-px">
        <div class="cy-card p-6">
            <form method="POST" action="{{ route('anio-lectivos.store') }}">
                @include('anio_lectivos._form')
            </form>
        </div>
    </div>
</x-app-layout>
