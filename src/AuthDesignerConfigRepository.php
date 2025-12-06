<?php

namespace Caresome\FilamentAuthDesigner;

use Caresome\FilamentAuthDesigner\Data\AuthDesignerConfig;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;

class AuthDesignerConfigRepository
{
    protected ?AuthPageConfig $defaults = null;

    protected array $pageConfigs = [];

    protected bool $showThemeSwitcher = false;

    protected array $themePosition = [
        'top' => '1.5rem',
        'right' => '1.5rem',
        'bottom' => 'auto',
        'left' => 'auto',
    ];

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

    public function setThemeSwitcher(bool $enabled, array $position): void
    {
        $this->showThemeSwitcher = $enabled;
        $this->themePosition = $position;
    }

    public function getConfig(string $page): AuthDesignerConfig
    {
        $pageConfig = $this->getMergedPageConfig($page);

        return AuthDesignerConfig::fromPageConfig(
            config: $pageConfig,
            showThemeSwitcher: $pageConfig->getShowThemeSwitcher() ?? $this->showThemeSwitcher,
            themePosition: $pageConfig->getThemePosition() ?? $this->themePosition,
        );
    }

    protected function getMergedPageConfig(string $page): AuthPageConfig
    {
        $pageConfig = $this->pageConfigs[$page] ?? new AuthPageConfig;

        if ($this->defaults instanceof AuthPageConfig) {
            return $pageConfig->mergeWith($this->defaults);
        }

        return $pageConfig;
    }
}
