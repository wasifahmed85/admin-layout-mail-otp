@props(['href' => 'javascript:void(0)', 'target' => '_self', 'secondary' => false, 'disabled' => false])

<a @disabled($disabled) href="{{ $href }}" target="{{ $target }}"
    {{ $attributes->merge(['title' => '', 'class' => 'btn btn-sm text-white uppercase tracking-widest' . ($secondary ? ' btn-secondary' : ' btn-primary')]) }}>
    {{ $slot }}
</a>

