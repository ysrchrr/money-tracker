@props(['value'])

<label {{ $attributes->merge(['class' => 'brutal-label']) }}>
    {{ $value ?? $slot }}
</label>
