<?php

declare(strict_types=1);

use Caresome\FilamentAuthDesigner\AuthDesignerConfigRepository;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthDesignerConfig;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;
use Caresome\FilamentAuthDesigner\Pages\Auth\Register;

beforeEach(function (): void {
    app()->forgetInstance(AuthDesignerConfigRepository::class);
    app()->singleton(AuthDesignerConfigRepository::class);
});

it('login page returns configuration', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/images/login-bg.jpg')
            ->blur(10)
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $config = $loginPage->getAuthDesignerConfig();
    expect($config)->toBeInstanceOf(AuthDesignerConfig::class)
        ->and($config->media)->toBe('/images/login-bg.jpg')
        ->and($config->position)->toBe(MediaPosition::Cover)
        ->and($config->blur)->toBe(10);
});

it('registration page returns configuration', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->registration(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::End)
            ->media('/images/register-bg.jpg')
            ->mediaSize('50%')
        );

    $plugin->configureRepository();

    $registerPage = new Register;
    $registerPage->boot();

    $config = $registerPage->getAuthDesignerConfig();
    expect($config->media)->toBe('/images/register-bg.jpg')
        ->and($config->position)->toBe(MediaPosition::End)
        ->and($config->mediaSize)->toBe('50%');
});

it('auth page uses default values when no configuration provided', function (): void {
    $loginPage = new Login;
    $loginPage->boot();

    $config = $loginPage->getAuthDesignerConfig();
    expect($config->media)->toBeNull()
        ->and($config->position)->toBeNull()
        ->and($config->blur)->toBe(0);
});

it('different auth pages have isolated configurations', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/login.jpg')
            ->blur(10)
        )
        ->registration(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Start)
            ->media('/register.jpg')
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $loginConfig = $loginPage->getAuthDesignerConfig();
    expect($loginConfig->media)->toBe('/login.jpg')
        ->and($loginConfig->position)->toBe(MediaPosition::Cover)
        ->and($loginConfig->blur)->toBe(10);

    $registerPage = new Register;
    $registerPage->boot();

    $registrationConfig = $registerPage->getAuthDesignerConfig();
    expect($registrationConfig->media)->toBe('/register.jpg')
        ->and($registrationConfig->position)->toBe(MediaPosition::Start)
        ->and($registrationConfig->blur)->toBe(0);
});

it('login page returns correct page key', function (): void {
    $loginPage = new Login;
    $reflection = new ReflectionClass($loginPage);
    $method = $reflection->getMethod('getAuthDesignerPageKey');

    expect($method->invoke($loginPage))->toBe('login');
});

it('registration page returns correct page key', function (): void {
    $registerPage = new Register;
    $reflection = new ReflectionClass($registerPage);
    $method = $reflection->getMethod('getAuthDesignerPageKey');

    expect($method->invoke($registerPage))->toBe('registration');
});

it('config includes theme switcher settings', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login()
        ->themeToggle();

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $config = $loginPage->getAuthDesignerConfig();
    expect($config->showThemeSwitcher)->toBeTrue();
});

it('shares media size style for horizontal positions', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Start)
            ->media('/login.jpg')
            ->mediaSize('40%')
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $config = $loginPage->getAuthDesignerConfig();
    expect($config->getMediaSizeStyle())->toBe('--media-size: 40%');
});

it('shares media size style for vertical positions', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Top)
            ->media('/login.jpg')
            ->mediaSize('300px')
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $config = $loginPage->getAuthDesignerConfig();
    expect($config->getMediaSizeStyle())->toBe('--media-size: 300px');
});

it('returns empty size style for cover position', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/login.jpg')
            ->mediaSize('50%')
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $config = $loginPage->getAuthDesignerConfig();
    expect($config->getMediaSizeStyle())->toBe('');
});
