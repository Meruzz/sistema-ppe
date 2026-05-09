@props(['active'])

@php
$base = 'block w-full ps-4 pe-4 py-2.5 text-sm font-medium border-l-4 transition-colors';
$classes = ($active ?? false)
    ? "$base border-brand-600 text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-950/40"
    : "$base border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($active ?? false) aria-current="page" @endif>
    {{ $slot }}
</a>
