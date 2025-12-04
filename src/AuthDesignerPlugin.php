<?php

namespace Caresome\FilamentAuthDesigner;

use Caresome\FilamentAuthDesigner\Concerns\HasDefaults;
use Caresome\FilamentAuthDesigner\Concerns\HasPages;
use Caresome\FilamentAuthDesigner\Concerns\HasRenderHooks;
use Caresome\FilamentAuthDesigner\Concerns\HasThemeSwitcher;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;

class AuthDesignerPlugin implements Plugin
{
    use HasDefaults;
    use HasPages;
    use HasRenderHooks;
    use HasThemeSwitcher;

    public function getId(): string
    {
        return 'auth-designer';
    }

    public function register(Panel $panel): void
    {
        if ($this->hasLogin()) {
            $panel->login($this->getLoginPageClass());
        }

        if ($this->hasRegistration()) {
            $panel->registration($this->getRegistrationPageClass());
        }

        if ($this->hasPasswordReset()) {
            $panel->passwordReset(
                $this->getRequestPasswordResetPageClass(),
                $this->getResetPasswordPageClass()
            );
        }

        if ($this->hasEmailVerification()) {
            $panel->emailVerification($this->getEmailVerificationPageClass());
        }
    }

    public function boot(Panel $panel): void
    {
        $this->configureRepository();
        $this->registerRenderHooks();
    }

    protected function registerRenderHooks(): void
    {
        foreach ($this->renderHooks as $name => $hooks) {
            foreach ($hooks as $hook) {
                FilamentView::registerRenderHook($name, $hook);
            }
        }
    }

    public function configureRepository(): void
    {
        $repository = app(AuthDesignerConfigRepository::class);

        $defaults = $this->buildDefaultsConfig();
        if ($defaults instanceof \Caresome\FilamentAuthDesigner\Data\AuthPageConfig) {
            $repository->setDefaults($defaults);
        }

        if ($this->hasLogin()) {
            $repository->setPageConfig('login', $this->buildPageConfig($this->loginConfigurator));
        }

        if ($this->hasRegistration()) {
            $repository->setPageConfig('registration', $this->buildPageConfig($this->registrationConfigurator));
        }

        if ($this->hasPasswordReset()) {
            $repository->setPageConfig('password-reset', $this->buildPageConfig($this->passwordResetConfigurator));
        }

        if ($this->hasEmailVerification()) {
            $repository->setPageConfig('email-verification', $this->buildPageConfig($this->emailVerificationConfigurator));
        }

        $repository->setThemeSwitcher($this->showThemeSwitcher, $this->themePosition);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static */
        return filament(app(static::class)->getId());
    }
}
