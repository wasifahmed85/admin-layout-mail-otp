@props(['secondary' => false])
<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-sm text-text-white' . ($secondary ? 'btn-secondary' : ' btn-accent')]) }}>
    {{ $slot }}
</button>
