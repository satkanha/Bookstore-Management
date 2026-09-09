@props(['variant' => 'dark'])

@php
    $currentLocale = app()->getLocale();
    $inactiveClass = $variant === 'dark' ? 'btn-outline-light' : 'btn-outline-secondary';
    $activeClass = $variant === 'dark' ? 'btn-warning' : 'btn-primary';
@endphp

<div {{ $attributes->merge(['class' => 'language-switcher btn-group btn-group-sm']) }} role="group" aria-label="{{ __('Language') }}">
    <a class="btn {{ $currentLocale === 'km' ? $activeClass : $inactiveClass }}" href="{{ route('locale.switch', 'km') }}">
        🇰🇭 {{ __('Khmer') }}
    </a>
    <a class="btn {{ $currentLocale === 'en' ? $activeClass : $inactiveClass }}" href="{{ route('locale.switch', 'en') }}">
        🇺🇸 {{ __('English') }}
    </a>
</div>
