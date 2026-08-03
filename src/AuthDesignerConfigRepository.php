<?php

declare(strict_types=1);

namespace Caresome\FilamentAuthDesigner;

use Caresome\FilamentAuthDesigner\Data\AuthDesignerConfig;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;

class AuthDesignerConfigRepository
{
    protected const GLOBAL_PANEL_KEY = '__global';

    protected const DEFAULT_THEME_POSITION = [
        'top' => '1.5rem',
        'end' => '1.5rem',
        'bottom' => 'auto',
        'start' => 'auto',
    ];

    protected array $defaults = [];

    protected array $pageConfigs = [];

    protected array $themeSwitcher = [];

    public function setDefaults(AuthPageConfig $config, ?string $panelId = null): void
    {
        $this->defaults[$this->resolvePanelKey($panelId)] = $config;
    }

    public function getDefaults(?string $panelId = null): ?AuthPageConfig
    {
        $panelKey = $this->resolvePanelKey($panelId);

        return $this->defaults[$panelKey]
            ?? ($panelId !== null ? ($this->defaults[self::GLOBAL_PANEL_KEY] ?? null) : null);
    }

    public function setPageConfig(string $page, AuthPageConfig $config, ?string $panelId = null): void
    {
        $this->pageConfigs[$this->resolvePanelKey($panelId)][$page] = $config;
    }

    public function getPageConfig(string $page, ?string $panelId = null): ?AuthPageConfig
    {
        $panelKey = $this->resolvePanelKey($panelId);

        return $this->pageConfigs[$panelKey][$page]
            ?? ($panelId !== null ? ($this->pageConfigs[self::GLOBAL_PANEL_KEY][$page] ?? null) : null);
    }

    public function hasPageConfig(string $page, ?string $panelId = null): bool
    {
        return $this->getPageConfig($page, $panelId) instanceof AuthPageConfig;
    }

    public function setThemeSwitcher(bool $enabled, array $position, ?string $panelId = null): void
    {
        $this->themeSwitcher[$this->resolvePanelKey($panelId)] = [
            'enabled' => $enabled,
            'position' => $position,
        ];
    }

    public function getConfig(string $page, ?string $panelId = null): AuthDesignerConfig
    {
        $pageConfig = $this->getMergedPageConfig($page, $panelId);
        $themeConfig = $this->getThemeSwitcherConfig($panelId);

        return AuthDesignerConfig::fromPageConfig(
            config: $pageConfig,
            showThemeSwitcher: $pageConfig->getShowThemeSwitcher() ?? $themeConfig['enabled'],
            themePosition: $pageConfig->getThemePosition() ?? $themeConfig['position'],
        );
    }

    protected function getMergedPageConfig(string $page, ?string $panelId = null): AuthPageConfig
    {
        $pageConfig = $this->getPageConfig($page, $panelId) ?? new AuthPageConfig;
        $defaults = $this->getDefaults($panelId);

        if ($defaults instanceof AuthPageConfig) {
            return $pageConfig->mergeWith($defaults);
        }

        return $pageConfig;
    }

    protected function getThemeSwitcherConfig(?string $panelId = null): array
    {
        $panelKey = $this->resolvePanelKey($panelId);
        $themeConfig = $this->themeSwitcher[$panelKey]
            ?? ($panelId !== null ? ($this->themeSwitcher[self::GLOBAL_PANEL_KEY] ?? null) : null);

        return $themeConfig ?? [
            'enabled' => false,
            'position' => self::DEFAULT_THEME_POSITION,
        ];
    }

    protected function resolvePanelKey(?string $panelId = null): string
    {
        return $panelId ?? self::GLOBAL_PANEL_KEY;
    }
}
