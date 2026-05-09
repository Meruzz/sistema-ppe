<x-app-layout>
    <x-slot name="header">
        <h1 class="cy-page-title">Editar ámbito</h1>
        <p class="cy-page-subtitle">{{ $ambito->nombre }}</p>
    </x-slot>

    <div class="cy-card max-w-2xl">
        <form method="POST" action="{{ route('ambitos.update', $ambito) }}">
            @method('PUT')
            @include('ambitos._form', ['ambito' => $ambito])
        </form>
    </div>
</x-app-layout>
