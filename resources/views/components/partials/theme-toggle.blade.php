@props([
    'position',
])

@php
    use Caresome\FilamentAuthDesigner\Enums\ThemePosition;
    use Filament\Facades\Filament;

    $positionClass = match($position) {
        ThemePosition::TopRight => 'fi-position-top-right',
        ThemePosition::TopLeft => 'fi-position-top-left',
        ThemePosition::BottomRight => 'fi-position-bottom-right',
        ThemePosition::BottomLeft => 'fi-position-bottom-left',
        default => 'fi-position-top-right',
    };

    $hasDarkMode = Filament::hasDarkMode();
    $hasDarkModeForced = Filament::hasDarkModeForced();
@endphp

@if($hasDarkMode && !$hasDarkModeForced)
    <div class="fi-auth-theme-switcher-wrapper {{ $positionClass }}">
        <x-filament-panels::theme-switcher />
    </div>
@endif
