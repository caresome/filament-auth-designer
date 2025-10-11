<?php

namespace Caresome\FilamentAuthDesigner;

use Caresome\FilamentAuthDesigner\Enums\Layout;
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;
use Caresome\FilamentAuthDesigner\Enums\ThemePosition;
use Caresome\FilamentAuthDesigner\Pages\Auth\EmailVerification;
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;
use Caresome\FilamentAuthDesigner\Pages\Auth\Register;
use Caresome\FilamentAuthDesigner\Pages\Auth\RequestPasswordReset;
use Caresome\FilamentAuthDesigner\Pages\Auth\ResetPassword;
use Filament\Contracts\Plugin;
use Filament\Panel;

class AuthDesignerPlugin implements Plugin
{
    protected ?string $loginPageClass = null;

    protected ?string $registerPageClass = null;

    protected ?string $requestPasswordResetPageClass = null;

    protected ?string $resetPasswordPageClass = null;

    protected ?string $emailVerificationPageClass = null;

    protected bool $showThemeSwitcher = false;

    protected ThemePosition $themePosition = ThemePosition::TopRight;

    public function getId(): string
    {
        return 'auth-designer';
    }

    public function register(Panel $panel): void
    {
        if ($this->loginPageClass) {
            $panel->login($this->loginPageClass);
        }

        if ($this->registerPageClass) {
            $panel->registration($this->registerPageClass);
        }

        if ($this->requestPasswordResetPageClass && $this->resetPasswordPageClass) {
            $panel->passwordReset(
                $this->requestPasswordResetPageClass,
                $this->resetPasswordPageClass
            );
        }

        if ($this->emailVerificationPageClass) {
            $panel->emailVerification($this->emailVerificationPageClass);
        }
    }

    public function boot(Panel $panel): void
    {
        app()->instance(ConfigKeys::THEME_SWITCHER, $this->showThemeSwitcher);
        app()->instance(ConfigKeys::THEME_POSITION, $this->themePosition);
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

    public function login(Layout $layout = Layout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0): static
    {
        $this->configureAuthPage('login', $layout, $media, $direction, $blur);
        $this->loginPageClass = Login::class;

        return $this;
    }

    public function registration(Layout $layout = Layout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0): static
    {
        $this->configureAuthPage('registration', $layout, $media, $direction, $blur);
        $this->registerPageClass = Register::class;

        return $this;
    }

    public function passwordReset(Layout $layout = Layout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0): static
    {
        $this->configureAuthPage('password-reset', $layout, $media, $direction, $blur);
        $this->requestPasswordResetPageClass = RequestPasswordReset::class;
        $this->resetPasswordPageClass = ResetPassword::class;

        return $this;
    }

    public function emailVerification(Layout $layout = Layout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0): static
    {
        $this->configureAuthPage('email-verification', $layout, $media, $direction, $blur);
        $this->emailVerificationPageClass = EmailVerification::class;

        return $this;
    }

    public function themeToggle(?ThemePosition $position = null): static
    {
        $this->showThemeSwitcher = true;
        $this->themePosition = $position ?? ThemePosition::TopRight;

        return $this;
    }

    private function configureAuthPage(string $key, Layout $layout, ?string $media, MediaDirection $direction, bool|int $blur): void
    {
        app()->instance(ConfigKeys::media($key), $media);
        app()->instance(ConfigKeys::position($key), $layout);
        app()->instance(ConfigKeys::direction($key), $direction);
        app()->instance(ConfigKeys::blur($key), $blur);
    }
}
