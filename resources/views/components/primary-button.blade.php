<button {{ $attributes->merge(['type' => 'submit', 'class' => 'cy-btn-primary']) }}>
    {{ $slot }}
</button>
