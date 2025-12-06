@php
    use Caresome\FilamentAuthDesigner\View\AuthDesignerRenderHook;

    $config = $authDesignerConfig;
    $hasMedia = $config->hasMedia();
    $position = $config->position;
    $isCover = $config->isCover();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    @if ($config->showThemeSwitcher)
        @include('filament-auth-designer::components.partials.theme-toggle', [
            'position' => $config->themePosition,
        ])
    @endif

    @php
        $layoutStyles = [];

        if ($hasMedia && !$isCover && $config->mediaSize) {
            $layoutStyles[] = $config->getMediaSizeStyle();
        }

        if ($config->blur > 0) {
            $layoutStyles[] = "--ad-blur: {$config->blur}px; --blur-overlay: {$config->getBlurOverlay()}; --blur-content: {$config->getBlurContent()}";
        }
    @endphp

    <div class="fi-auth-layout {{ $hasMedia ? 'has-media' : 'no-media' }} {{ $position ? 'media-' . $position->value : '' }}"
        @if (count($layoutStyles)) style="{{ implode(';', $layoutStyles) }}" @endif>
        @if ($hasMedia)
            <div class="fi-auth-media-section">
                <div class="fi-auth-media-wrapper">
                    @include('filament-auth-designer::components.partials.media', [
                        'config' => $config,
                        'imageClass' => 'fi-auth-media',
                        'videoClass' => 'fi-auth-media',
                    ])
                    <div class="fi-auth-media-overlay"></div>
                </div>
                @if ($config->hasRenderHook(AuthDesignerRenderHook::MediaOverlay))
                    <div class="fi-auth-media-content">
                        {!! $config->renderHook(AuthDesignerRenderHook::MediaOverlay) !!}
                    </div>
                @endif
            </div>
        @endif

        <div class="fi-auth-content-section">
            @if ($isCover)
                {!! $config->renderHook(AuthDesignerRenderHook::CardBefore) !!}
                <x-filament::section class="fi-auth-card">
                    {{ $slot }}
                </x-filament::section>
                {!! $config->renderHook(AuthDesignerRenderHook::CardAfter) !!}
            @else
                <div class="fi-auth-form-container">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::layout.base>
