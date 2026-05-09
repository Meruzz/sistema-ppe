@props(['value'])

<label {{ $attributes->merge(['class' => 'cy-label']) }}>
    {{ $value ?? $slot }}
</label>
