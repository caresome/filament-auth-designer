<?php

declare(strict_types=1);

namespace Caresome\FilamentAuthDesigner\Data;

use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Caresome\FilamentAuthDesigner\Support\MediaDetector;

final class AuthPageConfig
{
    protected ?MediaPosition $position = null;

    protected ?string $media = null;

    protected ?string $mediaSize = null;

    protected int $blur = 0;

    protected ?string $mediaAlt = null;

    protected ?string $pageClass = null;

    protected array $renderHooks = [];

    public function media(?string $media, ?string $alt = null): static
    {
        $this->media = $media;
        $this->mediaAlt = $alt;

        return $this;
    }

    public function mediaPosition(?MediaPosition $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function mediaSize(?string $size): static
    {
        $this->mediaSize = $size;

        return $this;
    }

    public function blur(int $blur): static
    {
        $this->blur = max(0, min(20, $blur));

        return $this;
    }

    public function usingPage(string $pageClass): static
    {
        $this->pageClass = $pageClass;

        return $this;
    }

    public function renderHook(string $name, \Closure $hook): static
    {
        $this->renderHooks[$name][] = $hook;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function getPosition(): ?MediaPosition
    {
        return $this->position;
    }

    public function getEffectivePosition(): ?MediaPosition
    {
        if (! $this->hasMedia()) {
            return null;
        }

        return $this->position ?? MediaPosition::Cover;
    }

    public function getMediaSize(): ?string
    {
        return $this->mediaSize;
    }

    public function getBlur(): int
    {
        return $this->blur;
    }

    public function getMediaAlt(): ?string
    {
        return $this->mediaAlt;
    }

    public function getPageClass(): ?string
    {
        return $this->pageClass;
    }

    public function hasMedia(): bool
    {
        return $this->media !== null && $this->media !== '';
    }

    public function isVideo(): bool
    {
        if (! $this->hasMedia()) {
            return false;
        }

        return app(MediaDetector::class)->isVideo($this->media);
    }

    public function hasCustomPage(): bool
    {
        return $this->pageClass !== null;
    }

    public function getRenderHooks(): array
    {
        return $this->renderHooks;
    }

    protected ?bool $showThemeSwitcher = null;

    protected ?array $themePosition = null;

    public function themeToggle(?string $top = null, ?string $right = null, ?string $bottom = null, ?string $left = null): static
    {
        $this->showThemeSwitcher = true;

        if ($top === null && $right === null && $bottom === null && $left === null) {
            $this->themePosition = ['top' => '1.5rem', 'right' => '1.5rem', 'bottom' => 'auto', 'left' => 'auto'];

            return $this;
        }

        $this->themePosition = [
            'top' => $top ?? 'auto',
            'right' => $right ?? 'auto',
            'bottom' => $bottom ?? 'auto',
            'left' => $left ?? 'auto',
        ];

        return $this;
    }

    public function getShowThemeSwitcher(): ?bool
    {
        return $this->showThemeSwitcher;
    }

    public function getThemePosition(): ?array
    {
        return $this->themePosition;
    }

    public function mergeWith(AuthPageConfig $defaults): static
    {
        $merged = new self;

        $merged->media = $this->media ?? $defaults->media;
        $merged->mediaAlt = $this->mediaAlt ?? $defaults->mediaAlt;
        $merged->position = $this->position ?? $defaults->position;
        $merged->mediaSize = $this->mediaSize ?? $defaults->mediaSize;
        $merged->blur = $this->blur !== 0 ? $this->blur : $defaults->blur;
        $merged->pageClass = $this->pageClass ?? $defaults->pageClass;
        $merged->renderHooks = $this->mergeRenderHooks($this->renderHooks, $defaults->renderHooks);
        $merged->showThemeSwitcher = $this->showThemeSwitcher ?? $defaults->showThemeSwitcher;
        $merged->themePosition = $this->themePosition ?? $defaults->themePosition;

        return $merged;
    }

    protected function mergeRenderHooks(array $pageHooks, array $defaultHooks): array
    {
        $merged = $defaultHooks;

        foreach ($pageHooks as $name => $hooks) {
            foreach ($hooks as $hook) {
                $merged[$name][] = $hook;
            }
        }

        return $merged;
    }
}
