<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

trait HasThemeSwitcher
{
    protected bool $showThemeSwitcher = false;

    protected array $themePosition = [
        'top' => '1.5rem',
        'end' => '1.5rem',
        'bottom' => 'auto',
        'start' => 'auto',
    ];

    public function themeToggle(?string $top = null, ?string $end = null, ?string $bottom = null, ?string $start = null, ?string $right = null, ?string $left = null): static
    {
        $this->showThemeSwitcher = true;

        if ($top === null && $end === null && $bottom === null && $start === null && $right === null && $left === null) {
            $this->themePosition = ['top' => '1.5rem', 'end' => '1.5rem', 'bottom' => 'auto', 'start' => 'auto'];

            return $this;
        }

        $this->themePosition = [
            'top' => $top ?? 'auto',
            'end' => $end ?? 'auto',
            'bottom' => $bottom ?? 'auto',
            'start' => $start ?? 'auto',
            'right' => $right ?? 'auto',
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
