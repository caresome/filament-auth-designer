<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

use Caresome\FilamentAuthDesigner\ConfigKeys;
use Illuminate\Support\Facades\View;

trait HasAuthDesignerLayout
{
    public function boot(): void
    {
        if (method_exists(parent::class, 'boot')) {
            parent::boot();
        }

        $this->shareAuthDesignerConfig();
    }

    protected function shareAuthDesignerConfig(): void
    {
        $pageKey = $this->getAuthDesignerPageKey();

        View::share('authDesignerMedia', $this->getConfig(ConfigKeys::media($pageKey)));
        View::share('authDesignerPosition', $this->getConfig(ConfigKeys::position($pageKey)));
        View::share('authDesignerDirection', $this->getConfig(ConfigKeys::direction($pageKey)));
        View::share('authDesignerBlur', $this->getConfig(ConfigKeys::blur($pageKey), 0));
    }

    private function getConfig(string $key, mixed $default = null): mixed
    {
        return app()->has($key) ? app($key) : $default;
    }

    abstract protected function getAuthDesignerPageKey(): string;
}
