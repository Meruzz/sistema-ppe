<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Nuevo ámbito</h1>
        <p class="cy-page-subtitle">Registra un área de acción del PPE.</p>
    </x-slot>

    <div class="cy-card max-w-2xl">
        <form method="POST" action="{{ route('ambitos.store') }}">
            @include('ambitos._form', ['ambito' => null])
        </form>
    </div>
</x-app-layout>
