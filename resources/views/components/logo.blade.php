@props([
    'size' => 'md',
    'showText' => false,
    'text' => 'Kinela Mundial 2026',
])

@php
    $sizes = [
        'nav' => ['h' => 48, 'w' => 150],
        'xs' => ['h' => 28, 'w' => 56],
        'sm' => ['h' => 40, 'w' => 80],
        'md' => ['h' => 56, 'w' => 110],
        'lg' => ['h' => 72, 'w' => 130],
        'xl' => ['h' => 96, 'w' => 150],
    ];
    $dims = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 min-w-0']) }}>
    <img
        src="{{ asset('logito.png') }}"
        alt="{{ config('app.name') }}"
        class="wc-logo-img shrink-0"
        style="max-height: {{ $dims['h'] }}px; max-width: {{ $dims['w'] }}px; height: auto; width: auto;"
    >
    @if ($showText)
        <span class="font-bold text-sky-600 leading-tight text-sm sm:text-base truncate">{{ $text }}</span>
    @endif
</div>
