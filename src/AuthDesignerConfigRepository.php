<?php

namespace Caresome\FilamentAuthDesigner;

use Caresome\FilamentAuthDesigner\Data\AuthDesignerConfig;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\ThemePosition;

class AuthDesignerConfigRepository
{
    protected ?AuthPageConfig $defaults = null;

    protected array $pageConfigs = [];

    protected bool $showThemeSwitcher = false;

    protected ThemePosition $themePosition = ThemePosition::TopRight;

    public function setDefaults(AuthPageConfig $config): void
    {
        $this->defaults = $config;
    }

    public function getDefaults(): ?AuthPageConfig
    {
        return $this->defaults;
    }

    public function setPageConfig(string $page, AuthPageConfig $config): void
    {
        $this->pageConfigs[$page] = $config;
    }

    public function getPageConfig(string $page): ?AuthPageConfig
    {
        return $this->pageConfigs[$page] ?? null;
    }

    public function hasPageConfig(string $page): bool
    {
        return isset($this->pageConfigs[$page]);
    }

    public function setThemeSwitcher(bool $enabled, ThemePosition $position): void
    {
        $this->showThemeSwitcher = $enabled;
        $this->themePosition = $position;
    }

    public function getConfig(string $page): AuthDesignerConfig
    {
        $pageConfig = $this->getMergedPageConfig($page);

        return AuthDesignerConfig::fromPageConfig(
            config: $pageConfig,
            showThemeSwitcher: $this->showThemeSwitcher,
            themePosition: $this->themePosition,
        );
    }

    protected function getMergedPageConfig(string $page): AuthPageConfig
    {
        $pageConfig = $this->pageConfigs[$page] ?? new AuthPageConfig;

        if ($this->defaults instanceof \Caresome\FilamentAuthDesigner\Data\AuthPageConfig) {
            return $pageConfig->mergeWith($this->defaults);
        }

        return $pageConfig;
    }
}
