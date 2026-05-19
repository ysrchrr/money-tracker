<button {{ $attributes->merge(['type' => 'submit', 'class' => 'brutal-btn']) }}>
    {{ $slot }}
</button>
