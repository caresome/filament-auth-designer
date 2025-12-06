<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

trait HasThemeSwitcher
{
    protected bool $showThemeSwitcher = false;

    protected array $themePosition = [
        'top' => '1.5rem',
        'right' => '1.5rem',
        'bottom' => 'auto',
        'left' => 'auto',
    ];

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

    public function hasThemeSwitcher(): bool
    {
        return $this->showThemeSwitcher;
    }

    public function getThemePosition(): array
    {
        return $this->themePosition;
    }
}
