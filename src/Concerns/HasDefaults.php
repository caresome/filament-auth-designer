<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Closure;

trait HasDefaults
{
    protected ?Closure $defaultsConfigurator = null;

    public function defaults(?Closure $configure): static
    {
        $this->defaultsConfigurator = $configure;

        return $this;
    }

    public function getDefaultsConfigurator(): ?Closure
    {
        return $this->defaultsConfigurator;
    }

    protected function buildDefaultsConfig(): ?AuthPageConfig
    {
        if ($this->defaultsConfigurator === null) {
            return null;
        }

        $config = new AuthPageConfig;
        ($this->defaultsConfigurator)($config);

        return $config;
    }
}
