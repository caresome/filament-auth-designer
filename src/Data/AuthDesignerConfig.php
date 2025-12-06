<?php

declare(strict_types=1);

namespace Caresome\FilamentAuthDesigner\Data;

use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Caresome\FilamentAuthDesigner\Support\MediaDetector;

final readonly class AuthDesignerConfig
{
    public function __construct(
        public ?MediaPosition $position,
        public ?string $media,
        public ?string $mediaSize,
        public int $blur,
        public ?string $mediaAlt,
        public bool $showThemeSwitcher,
        public array $themePosition,
        public bool $isVideo = false,
        public ?string $mediaMimeType = null,
        public array $renderHooks = [],
    ) {}

    public function hasMedia(): bool
    {
        return $this->media !== null && $this->media !== '';
    }

    public function isVideo(): bool
    {
        return $this->isVideo;
    }

    public function isCover(): bool
    {
        return $this->position?->isCover() ?? false;
    }

    public function isHorizontal(): bool
    {
        return $this->position?->isHorizontal() ?? false;
    }

    private const BLUR_CONTENT_MULTIPLIER = 2.5;

    public function isVertical(): bool
    {
        return $this->position?->isVertical() ?? false;
    }

    public function getBlurOverlay(): string
    {
        return $this->blur.'px';
    }

    public function getBlurContent(): string
    {
        return ($this->blur * self::BLUR_CONTENT_MULTIPLIER).'px';
    }

    public function getMediaSizeStyle(): string
    {
        if (! $this->mediaSize || $this->isCover()) {
            return '';
        }

        if ($this->isHorizontal()) {
            return '--media-size: '.$this->mediaSize;
        }

        if ($this->isVertical()) {
            return '--media-size: '.$this->mediaSize;
        }

        return '';
    }

    public function hasRenderHook(string $name): bool
    {
        return isset($this->renderHooks[$name]) && count($this->renderHooks[$name]) > 0;
    }

    public function renderHook(string $name): string
    {
        if (! $this->hasRenderHook($name)) {
            return '';
        }

        $output = '';
        foreach ($this->renderHooks[$name] as $hook) {
            $result = $hook();
            $output .= $result instanceof \Illuminate\Contracts\View\View ? $result->render() : $result;
        }

        return $output;
    }

    public static function fromPageConfig(
        AuthPageConfig $config,
        bool $showThemeSwitcher,
        array $themePosition,
    ): static {
        $media = $config->getMedia();
        $hasMedia = $media !== null && $media !== '';
        $mediaDetector = app(MediaDetector::class);

        return new self(
            position: $config->getEffectivePosition(),
            media: $media,
            mediaSize: $config->getMediaSize(),
            blur: $config->getBlur(),
            mediaAlt: $config->getMediaAlt(),
            showThemeSwitcher: $showThemeSwitcher,
            themePosition: $themePosition,
            isVideo: $hasMedia && $mediaDetector->isVideo($media),
            mediaMimeType: $hasMedia ? $mediaDetector->getMimeType($media) : null,
            renderHooks: $config->getRenderHooks(),
        );
    }
}
