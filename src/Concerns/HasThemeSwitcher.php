<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

use Caresome\FilamentAuthDesigner\Enums\ThemePosition;

trait HasThemeSwitcher
{
    protected bool $showThemeSwitcher = false;

    protected ThemePosition $themePosition = ThemePosition::TopRight;

    public function themeToggle(?ThemePosition $position = null): static
    {
        $this->showThemeSwitcher = true;
        $this->themePosition = $position ?? ThemePosition::TopRight;

        return $this;
    }

    public function hasThemeSwitcher(): bool
    {
        return $this->showThemeSwitcher;
    }

    public function getThemePosition(): ThemePosition
    {
        return $this->themePosition;
    }
}
