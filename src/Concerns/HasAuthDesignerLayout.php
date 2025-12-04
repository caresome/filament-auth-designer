<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

use Caresome\FilamentAuthDesigner\AuthDesignerConfigRepository;
use Illuminate\Support\Facades\View;

trait HasAuthDesignerLayout
{
    public function boot(): void
    {
        if (method_exists(parent::class, 'boot')) {
            parent::boot();
        }

        static::$layout = 'filament-auth-designer::components.layouts.auth';

        $this->shareAuthDesignerConfig();
    }

    protected function shareAuthDesignerConfig(): void
    {
        $repository = app(AuthDesignerConfigRepository::class);
        $config = $repository->getConfig($this->getAuthDesignerPageKey());

        View::share('authDesignerConfig', $config);
    }

    abstract protected function getAuthDesignerPageKey(): string;
}
