<button {{ $attributes->merge(['type' => 'submit', 'class' => 'cy-btn-danger']) }}>
    {{ $slot }}
</button>
