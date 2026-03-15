<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

use Caresome\FilamentAuthDesigner\AuthDesignerConfigRepository;
use Caresome\FilamentAuthDesigner\Data\AuthDesignerConfig;
use Filament\Facades\Filament;

trait HasAuthDesignerLayout
{
    public function boot(): void
    {
        if (method_exists(parent::class, 'boot')) {
            parent::boot();
        }

        static::$layout = 'filament-auth-designer::components.layouts.auth';
    }

    public function getAuthDesignerConfig(): AuthDesignerConfig
    {
        $repository = app(AuthDesignerConfigRepository::class);
        $panelId = app()->bound('filament') ? Filament::getCurrentPanel()?->getId() : null;

        return $repository->getConfig(
            $this->getAuthDesignerPageKey(),
            $panelId,
        );
    }

    abstract protected function getAuthDesignerPageKey(): string;
}
