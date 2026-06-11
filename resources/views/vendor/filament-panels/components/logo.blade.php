@php
    $brandName = filament()->getBrandName();
    $brandLogo = filament()->getBrandLogo();
    $darkModeBrandLogo = filament()->getDarkModeBrandLogo();
    $logo = filled($darkModeBrandLogo) && request()->cookie('theme') === 'dark'
        ? $darkModeBrandLogo
        : $brandLogo;
@endphp

<div {{ $attributes->class(['jj-filament-brand']) }}>
    @if (filled($logo))
        <img
            alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}"
            class="jj-filament-brand__logo"
            src="{{ $logo }}"
        />
    @endif

    <span class="jj-filament-brand__name">
        {{ $brandName }}
    </span>
</div>
