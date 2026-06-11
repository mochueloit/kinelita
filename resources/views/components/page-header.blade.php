@props([
    'title',
    'subtitle' => null,
    'centered' => true,
    'showLogo' => true,
    'logoSize' => 'md',
])

<div {{ $attributes->merge(['class' => ($centered ? 'text-center' : '') . ' mb-8 md:mb-10']) }}>
    @if ($showLogo)
        <x-logo :size="$logoSize" class="{{ $centered ? 'justify-center mb-4' : 'mb-4' }}" />
    @endif
    <h1 class="wc-title {{ $centered ? '' : '!text-2xl md:!text-3xl text-left' }}">{{ $title }}</h1>
    @if ($subtitle)
        <p class="wc-subtitle {{ $centered ? 'text-lg mt-2' : 'mt-1 text-left' }}">{{ $subtitle }}</p>
    @endif
    {{ $slot }}
</div>
