@php
    use Caresome\FilamentAuthDesigner\ConfigKeys;
    use Caresome\FilamentAuthDesigner\Enums\AuthLayout;
    use Caresome\FilamentAuthDesigner\Enums\MediaDirection;
    use Caresome\FilamentAuthDesigner\Enums\ThemePosition;

    $media = $authDesignerMedia ?? null;
    $layout = $authDesignerPosition ?? AuthLayout::None;
    $direction = $authDesignerDirection ?? MediaDirection::Right;
    $blur = $authDesignerBlur ?? 0;
    $showThemeSwitcher = app()->has(ConfigKeys::THEME_SWITCHER)
        ? app(ConfigKeys::THEME_SWITCHER)
        : false;
    $themePosition = app()->has(ConfigKeys::THEME_POSITION)
        ? app(ConfigKeys::THEME_POSITION)
        : ThemePosition::TopRight;

    $hasMedia = !empty($media);
    $isVideo = false;
    if ($hasMedia) {
        $mediaLower = strtolower($media);
        $isVideo = str_ends_with($mediaLower, '.mp4')
            || str_ends_with($mediaLower, '.webm')
            || str_ends_with($mediaLower, '.mov')
            || str_ends_with($mediaLower, '.ogg');
    }

    $isMediaLeft = $direction === MediaDirection::Left;

    if ($hasMedia && $layout === AuthLayout::Overlay) {
        $blurOverlay = $blur === false || $blur === 0 ? '0px' :
                       ($blur === true ? '8px' : $blur . 'px');
        $blurContent = $blur === false || $blur === 0 ? '0px' :
                       ($blur === true ? '20px' : (is_int($blur) ? ($blur * 2.5) : 20) . 'px');
    } else {
        $blurOverlay = '0px';
        $blurContent = '0px';
    }

    $layoutClass = match($layout) {
        AuthLayout::None => 'fi-auth-minimal',
        AuthLayout::Split => 'fi-auth-split-screen',
        AuthLayout::Overlay => 'fi-auth-fullscreen',
        AuthLayout::Top => 'fi-auth-top-banner',
        AuthLayout::Panel => 'fi-auth-side-panel',
    };

    $mediaClass = $hasMedia ? 'has-media' : 'no-media';
    $directionClass = $isMediaLeft ? 'media-left' : 'media-right';
@endphp

<x-filament-panels::layout.base>
    @if($showThemeSwitcher && filament()->hasDarkMode() && !filament()->hasDarkModeForced())
        <div class="fi-auth-theme-switcher-wrapper fi-position-{{ $themePosition->value }}">
            <x-filament-panels::theme-switcher />
        </div>
    @endif

    @if($layout === AuthLayout::None || (!$hasMedia && $layout !== AuthLayout::Overlay))
        <div class="{{ $layoutClass }}">
            <div class="fi-auth-minimal-container">
                {{ $slot }}
            </div>
        </div>
    @elseif($layout === AuthLayout::Split)
        <div class="{{ $layoutClass }} {{ $mediaClass }} {{ $directionClass }}">
            <div class="fi-auth-split-form-side">
                <div class="fi-auth-split-form-container">
                    {{ $slot }}
                </div>
            </div>

            @if($hasMedia)
                <div class="fi-auth-split-image-side">
                    <div class="fi-auth-split-image-wrapper">
                        @if($isVideo)
                            <video
                                autoplay
                                muted
                                loop
                                playsinline
                                class="fi-auth-split-image"
                            >
                                <source src="{{ $media }}" type="video/{{ pathinfo($media, PATHINFO_EXTENSION) }}">
                            </video>
                        @else
                            <img
                                src="{{ $media }}"
                                alt="Authentication"
                                class="fi-auth-split-image"
                            />
                        @endif
                        <div class="fi-auth-split-image-overlay"></div>
                    </div>
                </div>
            @endif
        </div>
    @elseif($layout === AuthLayout::Overlay)
        <div class="{{ $layoutClass }} {{ $mediaClass }}" style="--blur-overlay: {{ $blurOverlay }}; --blur-content: {{ $blurContent }}">
            @if($hasMedia)
                <div class="fi-auth-fullscreen-background">
                    @if($isVideo)
                        <video
                            autoplay
                            muted
                            loop
                            playsinline
                            class="fi-auth-fullscreen-media"
                        >
                            <source src="{{ $media }}" type="video/{{ pathinfo($media, PATHINFO_EXTENSION) }}">
                        </video>
                    @else
                        <img
                            src="{{ $media }}"
                            alt="Authentication"
                            class="fi-auth-fullscreen-media"
                        />
                    @endif
                    <div class="fi-auth-fullscreen-overlay"></div>
                </div>
            @endif

            <div class="fi-auth-fullscreen-content">
                <x-filament::section class="fi-auth-fullscreen-section">
                    {{ $slot }}
                </x-filament::section>
            </div>
        </div>
    @elseif($layout === AuthLayout::Top)
        <div class="{{ $layoutClass }} {{ $mediaClass }}">
            @if($hasMedia)
                <div class="fi-auth-top-banner-media">
                    <div class="fi-auth-top-banner-wrapper">
                        @if($isVideo)
                            <video autoplay muted loop playsinline class="fi-auth-top-banner-video">
                                <source src="{{ $media }}" type="video/{{ pathinfo($media, PATHINFO_EXTENSION) }}">
                            </video>
                        @else
                            <img src="{{ $media }}" alt="Authentication" class="fi-auth-top-banner-image" />
                        @endif
                        <div class="fi-auth-top-banner-overlay"></div>
                    </div>
                </div>
            @endif

            <div class="fi-auth-top-banner-content">
                <div class="fi-auth-top-banner-form">
                    {{ $slot }}
                </div>
            </div>
        </div>
    @elseif($layout === AuthLayout::Panel)
        <div class="{{ $layoutClass }} {{ $mediaClass }} {{ $directionClass }}">
            @if($hasMedia)
                <div class="fi-auth-side-panel-media">
                    <div class="fi-auth-side-panel-media-wrapper">
                        @if($isVideo)
                            <video autoplay muted loop playsinline class="fi-auth-side-panel-video">
                                <source src="{{ $media }}" type="video/{{ pathinfo($media, PATHINFO_EXTENSION) }}">
                            </video>
                        @else
                            <img src="{{ $media }}" alt="Authentication" class="fi-auth-side-panel-image" />
                        @endif
                        <div class="fi-auth-side-panel-overlay"></div>
                    </div>
                </div>
            @endif

            <div class="fi-auth-side-panel-container">
                <div class="fi-auth-side-panel-form">
                    {{ $slot }}
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::layout.base>
