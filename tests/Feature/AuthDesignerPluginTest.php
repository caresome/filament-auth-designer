<?php

use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\ConfigKeys;
use Caresome\FilamentAuthDesigner\Enums\AuthLayout;
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;
use Caresome\FilamentAuthDesigner\Enums\ThemePosition;
use Caresome\FilamentAuthDesigner\Pages\Auth\EditProfile;
use Caresome\FilamentAuthDesigner\Pages\Auth\EmailVerification;
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;
use Caresome\FilamentAuthDesigner\Pages\Auth\Register;
use Caresome\FilamentAuthDesigner\Pages\Auth\RequestPasswordReset;
use Caresome\FilamentAuthDesigner\Pages\Auth\ResetPassword;

it('stores login configuration in container', function () {
    $plugin = AuthDesignerPlugin::make()
        ->login(
            layout: AuthLayout::Overlay,
            media: '/images/login-bg.jpg',
            direction: MediaDirection::Left,
            blur: 10
        );

    expect(app(ConfigKeys::media('login')))->toBe('/images/login-bg.jpg')
        ->and(app(ConfigKeys::position('login')))->toBe(AuthLayout::Overlay)
        ->and(app(ConfigKeys::direction('login')))->toBe(MediaDirection::Left)
        ->and(app(ConfigKeys::blur('login')))->toBe(10);
});

it('stores registration configuration in container', function () {
    $plugin = AuthDesignerPlugin::make()
        ->registration(
            layout: AuthLayout::Split,
            media: '/images/register-bg.jpg',
            direction: MediaDirection::Right,
            blur: 0
        );

    expect(app(ConfigKeys::media('registration')))->toBe('/images/register-bg.jpg')
        ->and(app(ConfigKeys::position('registration')))->toBe(AuthLayout::Split)
        ->and(app(ConfigKeys::direction('registration')))->toBe(MediaDirection::Right)
        ->and(app(ConfigKeys::blur('registration')))->toBe(0);
});

it('stores password reset configuration in container', function () {
    $plugin = AuthDesignerPlugin::make()
        ->passwordReset(
            layout: AuthLayout::Top,
            media: '/images/reset-bg.jpg',
            direction: MediaDirection::Left,
            blur: 5
        );

    expect(app(ConfigKeys::media('password-reset')))->toBe('/images/reset-bg.jpg')
        ->and(app(ConfigKeys::position('password-reset')))->toBe(AuthLayout::Top)
        ->and(app(ConfigKeys::direction('password-reset')))->toBe(MediaDirection::Left)
        ->and(app(ConfigKeys::blur('password-reset')))->toBe(5);
});

it('stores email verification configuration in container', function () {
    $plugin = AuthDesignerPlugin::make()
        ->emailVerification(
            layout: AuthLayout::Panel,
            media: '/images/verify-bg.jpg',
            direction: MediaDirection::Right,
            blur: 8
        );

    expect(app(ConfigKeys::media('email-verification')))->toBe('/images/verify-bg.jpg')
        ->and(app(ConfigKeys::position('email-verification')))->toBe(AuthLayout::Panel)
        ->and(app(ConfigKeys::direction('email-verification')))->toBe(MediaDirection::Right)
        ->and(app(ConfigKeys::blur('email-verification')))->toBe(8);
});

it('uses default values when parameters are omitted', function () {
    $plugin = AuthDesignerPlugin::make()->login();

    $reflection = new ReflectionClass($plugin);
    $loginPageClass = $reflection->getProperty('loginPageClass');
    $loginPageClass->setAccessible(true);

    expect($loginPageClass->getValue($plugin))->toBe(Login::class);
});

it('enables theme switcher with default position', function () {
    $plugin = AuthDesignerPlugin::make()->themeToggle();

    $reflection = new ReflectionClass($plugin);
    $showThemeSwitcher = $reflection->getProperty('showThemeSwitcher');
    $showThemeSwitcher->setAccessible(true);
    $themePosition = $reflection->getProperty('themePosition');
    $themePosition->setAccessible(true);

    expect($showThemeSwitcher->getValue($plugin))->toBeTrue()
        ->and($themePosition->getValue($plugin))->toBe(ThemePosition::TopRight);
});

it('enables theme switcher with custom position', function () {
    $plugin = AuthDesignerPlugin::make()->themeToggle(ThemePosition::BottomLeft);

    $reflection = new ReflectionClass($plugin);
    $showThemeSwitcher = $reflection->getProperty('showThemeSwitcher');
    $showThemeSwitcher->setAccessible(true);
    $themePosition = $reflection->getProperty('themePosition');
    $themePosition->setAccessible(true);

    expect($showThemeSwitcher->getValue($plugin))->toBeTrue()
        ->and($themePosition->getValue($plugin))->toBe(ThemePosition::BottomLeft);
});

it('allows different configurations for different auth pages', function () {
    $plugin = AuthDesignerPlugin::make()
        ->login(layout: AuthLayout::Overlay, media: '/login.jpg')
        ->registration(layout: AuthLayout::Split, media: '/register.jpg');

    expect(app(ConfigKeys::media('login')))->toBe('/login.jpg')
        ->and(app(ConfigKeys::position('login')))->toBe(AuthLayout::Overlay)
        ->and(app(ConfigKeys::media('registration')))->toBe('/register.jpg')
        ->and(app(ConfigKeys::position('registration')))->toBe(AuthLayout::Split);
});

it('sets correct page classes when configuring auth pages', function () {
    $plugin = AuthDesignerPlugin::make()
        ->login()
        ->registration()
        ->passwordReset()
        ->emailVerification();

    $reflection = new ReflectionClass($plugin);

    $loginPageClass = $reflection->getProperty('loginPageClass');
    $loginPageClass->setAccessible(true);
    expect($loginPageClass->getValue($plugin))->toBe(Login::class);

    $registerPageClass = $reflection->getProperty('registerPageClass');
    $registerPageClass->setAccessible(true);
    expect($registerPageClass->getValue($plugin))->toBe(Register::class);

    $requestPasswordResetPageClass = $reflection->getProperty('requestPasswordResetPageClass');
    $requestPasswordResetPageClass->setAccessible(true);
    expect($requestPasswordResetPageClass->getValue($plugin))->toBe(RequestPasswordReset::class);

    $resetPasswordPageClass = $reflection->getProperty('resetPasswordPageClass');
    $resetPasswordPageClass->setAccessible(true);
    expect($resetPasswordPageClass->getValue($plugin))->toBe(ResetPassword::class);

    $emailVerificationPageClass = $reflection->getProperty('emailVerificationPageClass');
    $emailVerificationPageClass->setAccessible(true);
    expect($emailVerificationPageClass->getValue($plugin))->toBe(EmailVerification::class);

    $editProfilePageClass = $reflection->getProperty('editProfilePageClass');
    $editProfilePageClass->setAccessible(true);
    expect($editProfilePageClass->getValue($plugin))->toBe(EditProfile::class);
});
