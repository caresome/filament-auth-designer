<?php

namespace Caresome\FilamentAuthDesigner;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AuthDesignerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'auth-designer';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews('filament-auth-designer');
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('auth-designer', __DIR__.'/../resources/css/auth-designer.css'),
        ], package: 'caresome/filament-auth-designer');
    }
}
