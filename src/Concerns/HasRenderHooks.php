<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

use Closure;

trait HasRenderHooks
{
    protected array $renderHooks = [];

    public function renderHook(string $name, Closure $hook): static
    {
        $this->renderHooks[$name][] = $hook;

        return $this;
    }

    public function getRenderHooks(): array
    {
        return $this->renderHooks;
    }
}
