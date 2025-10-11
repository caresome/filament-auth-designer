<?php

namespace Caresome\FilamentAuthDesigner;

class ConfigKeys
{
    const THEME_SWITCHER = 'filament-auth-designer.theme-switcher';

    const THEME_POSITION = 'filament-auth-designer.theme-position';

    public static function media(string $pageKey): string
    {
        return "filament-auth-designer.{$pageKey}.media";
    }

    public static function position(string $pageKey): string
    {
        return "filament-auth-designer.{$pageKey}.position";
    }

    public static function direction(string $pageKey): string
    {
        return "filament-auth-designer.{$pageKey}.direction";
    }

    public static function blur(string $pageKey): string
    {
        return "filament-auth-designer.{$pageKey}.blur";
    }
}
