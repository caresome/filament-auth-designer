<?php

namespace Caresome\FilamentAuthDesigner;

use Caresome\FilamentAuthDesigner\Enums\AuthLayout;
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;
use Caresome\FilamentAuthDesigner\Enums\ThemePosition;
use Caresome\FilamentAuthDesigner\Pages\Auth\EditProfile;
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

    protected ?string $editProfilePageClass = null;

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

        if ($this->editProfilePageClass) {
            $panel->profile($this->editProfilePageClass);
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

    public function login(AuthLayout $layout = AuthLayout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0, ?string $pageClass = null): static
    {
        if (is_null($pageClass)) {
            $pageClass = Login::class;
        }
        $this->configureAuthPage('login', $layout, $media, $direction, $blur);
        $this->loginPageClass = $pageClass;

        return $this;
    }

    public function registration(AuthLayout $layout = AuthLayout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0, ?string $pageClass = null): static
    {
        if (is_null($pageClass)) {
            $pageClass = Register::class;
        }
        $this->configureAuthPage('registration', $layout, $media, $direction, $blur);
        $this->registerPageClass = $pageClass;

        return $this;
    }

    public function passwordReset(AuthLayout $layout = AuthLayout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0, ?string $pageClass = null, ?string $requestPageClass = null): static
    {
        if (is_null($pageClass)) {
            $pageClass = ResetPassword::class;
        }

        if (is_null($requestPageClass)) {
            $requestPageClass = RequestPasswordReset::class;
        }

        $this->configureAuthPage('password-reset', $layout, $media, $direction, $blur);
        $this->requestPasswordResetPageClass = $requestPageClass;
        $this->resetPasswordPageClass = $pageClass;

        return $this;
    }

    public function emailVerification(AuthLayout $layout = AuthLayout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0, ?string $pageClass = null): static
    {
        if (is_null($pageClass)) {
            $pageClass = EmailVerification::class;
        }
        $this->configureAuthPage('email-verification', $layout, $media, $direction, $blur);
        $this->emailVerificationPageClass = $pageClass;

        return $this;
    }

    public function editProfile(AuthLayout $layout = AuthLayout::None, ?string $media = null, MediaDirection $direction = MediaDirection::Right, bool|int $blur = 0, ?string $pageClass = null): static
    {
        if (is_null($pageClass)) {
            $pageClass = EditProfile::class;
        }
        $this->configureAuthPage('profile', $layout, $media, $direction, $blur);
        $this->editProfilePageClass = $pageClass;

        return $this;
    }

    public function themeToggle(?ThemePosition $position = null): static
    {
        $this->showThemeSwitcher = true;
        $this->themePosition = $position ?? ThemePosition::TopRight;

        return $this;
    }

    private function configureAuthPage(string $key, AuthLayout $layout, ?string $media, MediaDirection $direction, bool|int $blur): void
    {
        app()->instance(ConfigKeys::media($key), $media);
        app()->instance(ConfigKeys::position($key), $layout);
        app()->instance(ConfigKeys::direction($key), $direction);
        app()->instance(ConfigKeys::blur($key), $blur);
    }
}
