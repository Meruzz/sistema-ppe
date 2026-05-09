@props(['active'])

@php
$base = 'inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors';
$classes = ($active ?? false)
    ? "$base text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-950/40"
    : "$base text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($active ?? false) aria-current="page" @endif>
    {{ $slot }}
</a>
