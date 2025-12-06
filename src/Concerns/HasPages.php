<?php

namespace Caresome\FilamentAuthDesigner\Concerns;

use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Pages\Auth\EditProfile;
use Caresome\FilamentAuthDesigner\Pages\Auth\EmailVerification;
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;
use Caresome\FilamentAuthDesigner\Pages\Auth\Register;
use Caresome\FilamentAuthDesigner\Pages\Auth\RequestPasswordReset;
use Caresome\FilamentAuthDesigner\Pages\Auth\ResetPassword;
use Closure;

trait HasPages
{
    protected bool $hasLogin = false;

    protected ?Closure $loginConfigurator = null;

    protected bool $hasRegistration = false;

    protected ?Closure $registrationConfigurator = null;

    protected bool $hasPasswordReset = false;

    protected ?Closure $passwordResetConfigurator = null;

    protected bool $hasEmailVerification = false;

    protected ?Closure $emailVerificationConfigurator = null;

    protected bool $hasProfile = false;

    protected ?Closure $profileConfigurator = null;

    public function login(?Closure $configure = null): static
    {
        $this->hasLogin = true;
        $this->loginConfigurator = $configure;

        return $this;
    }

    public function registration(?Closure $configure = null): static
    {
        $this->hasRegistration = true;
        $this->registrationConfigurator = $configure;

        return $this;
    }

    public function passwordReset(?Closure $configure = null): static
    {
        $this->hasPasswordReset = true;
        $this->passwordResetConfigurator = $configure;

        return $this;
    }

    public function emailVerification(?Closure $configure = null): static
    {
        $this->hasEmailVerification = true;
        $this->emailVerificationConfigurator = $configure;

        return $this;
    }

    public function profile(?Closure $configure = null): static
    {
        $this->hasProfile = true;
        $this->profileConfigurator = $configure;

        return $this;
    }

    public function hasLogin(): bool
    {
        return $this->hasLogin;
    }

    public function hasRegistration(): bool
    {
        return $this->hasRegistration;
    }

    public function hasPasswordReset(): bool
    {
        return $this->hasPasswordReset;
    }

    public function hasEmailVerification(): bool
    {
        return $this->hasEmailVerification;
    }

    public function hasProfile(): bool
    {
        return $this->hasProfile;
    }

    protected function buildPageConfig(?Closure $configurator): AuthPageConfig
    {
        $config = new AuthPageConfig;

        if ($configurator instanceof \Closure) {
            $configurator($config);
        }

        return $config;
    }

    protected function getLoginPageClass(): string
    {
        $config = $this->buildPageConfig($this->loginConfigurator);

        return $config->getPageClass() ?? Login::class;
    }

    protected function getRegistrationPageClass(): string
    {
        $config = $this->buildPageConfig($this->registrationConfigurator);

        return $config->getPageClass() ?? Register::class;
    }

    protected function getRequestPasswordResetPageClass(): string
    {
        $config = $this->buildPageConfig($this->passwordResetConfigurator);

        return $config->getPageClass() ?? RequestPasswordReset::class;
    }

    protected function getResetPasswordPageClass(): string
    {
        return ResetPassword::class;
    }

    protected function getEmailVerificationPageClass(): string
    {
        $config = $this->buildPageConfig($this->emailVerificationConfigurator);

        return $config->getPageClass() ?? EmailVerification::class;
    }

    protected function getProfilePageClass(): string
    {
        $config = $this->buildPageConfig($this->profileConfigurator);

        return $config->getPageClass() ?? EditProfile::class;
    }
}
