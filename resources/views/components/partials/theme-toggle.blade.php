@props(['position'])

@php
    use Filament\Facades\Filament;

    $styles = '';

    if (is_array($position)) {
        $styles = 'style="';
        foreach ($position as $key => $value) {
            $styles .= "--ad-theme-switcher-{$key}: {$value}; ";
        }
        $styles .= '"';
    }

    $hasDarkMode = Filament::hasDarkMode();
    $hasDarkModeForced = Filament::hasDarkModeForced();
@endphp

@if ($hasDarkMode && !$hasDarkModeForced)
    <div class="fi-auth-theme-switcher-wrapper" {!! $styles !!}>
        <x-filament-panels::theme-switcher />
    </div>
@endif
