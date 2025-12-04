<?php

use Caresome\FilamentAuthDesigner\AuthDesignerConfigRepository;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthDesignerConfig;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;
use Caresome\FilamentAuthDesigner\Pages\Auth\Register;
use Illuminate\Support\Facades\View;

beforeEach(function (): void {
    View::getFinder()->flush();
    app()->forgetInstance(AuthDesignerConfigRepository::class);
    app()->singleton(AuthDesignerConfigRepository::class);
});

it('login page shares configuration to view', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): \Caresome\FilamentAuthDesigner\Data\AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/images/login-bg.jpg')
            ->blur(10)
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $shared = View::getShared();
    expect($shared)->toHaveKey('authDesignerConfig')
        ->and($shared['authDesignerConfig'])->toBeInstanceOf(AuthDesignerConfig::class)
        ->and($shared['authDesignerConfig']->media)->toBe('/images/login-bg.jpg')
        ->and($shared['authDesignerConfig']->position)->toBe(MediaPosition::Cover)
        ->and($shared['authDesignerConfig']->blur)->toBe(10);
});

it('registration page shares configuration to view', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->registration(fn (AuthPageConfig $config): \Caresome\FilamentAuthDesigner\Data\AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Right)
            ->media('/images/register-bg.jpg')
            ->mediaSize('50%')
        );

    $plugin->configureRepository();

    $registerPage = new Register;
    $registerPage->boot();

    $shared = View::getShared();
    expect($shared)->toHaveKey('authDesignerConfig')
        ->and($shared['authDesignerConfig']->media)->toBe('/images/register-bg.jpg')
        ->and($shared['authDesignerConfig']->position)->toBe(MediaPosition::Right)
        ->and($shared['authDesignerConfig']->mediaSize)->toBe('50%');
});

it('auth page uses default values when no configuration provided', function (): void {
    $loginPage = new Login;
    $loginPage->boot();

    $shared = View::getShared();
    expect($shared)->toHaveKey('authDesignerConfig')
        ->and($shared['authDesignerConfig']->media)->toBeNull()
        ->and($shared['authDesignerConfig']->position)->toBeNull()
        ->and($shared['authDesignerConfig']->blur)->toBe(0);
});

it('different auth pages have isolated configurations', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): \Caresome\FilamentAuthDesigner\Data\AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/login.jpg')
            ->blur(10)
        )
        ->registration(fn (AuthPageConfig $config): \Caresome\FilamentAuthDesigner\Data\AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Left)
            ->media('/register.jpg')
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $shared = View::getShared();
    expect($shared['authDesignerConfig']->media)->toBe('/login.jpg')
        ->and($shared['authDesignerConfig']->position)->toBe(MediaPosition::Cover)
        ->and($shared['authDesignerConfig']->blur)->toBe(10);

    View::share('authDesignerConfig');

    $registerPage = new Register;
    $registerPage->boot();

    $shared = View::getShared();
    expect($shared['authDesignerConfig']->media)->toBe('/register.jpg')
        ->and($shared['authDesignerConfig']->position)->toBe(MediaPosition::Left)
        ->and($shared['authDesignerConfig']->blur)->toBe(0);
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

    $shared = View::getShared();
    expect($shared['authDesignerConfig']->showThemeSwitcher)->toBeTrue();
});

it('shares media size style for horizontal positions', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): \Caresome\FilamentAuthDesigner\Data\AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Left)
            ->media('/login.jpg')
            ->mediaSize('40%')
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $shared = View::getShared();
    expect($shared['authDesignerConfig']->getMediaSizeStyle())->toBe('--media-size: 40%');
});

it('shares media size style for vertical positions', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): \Caresome\FilamentAuthDesigner\Data\AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Top)
            ->media('/login.jpg')
            ->mediaSize('300px')
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $shared = View::getShared();
    expect($shared['authDesignerConfig']->getMediaSizeStyle())->toBe('--media-size: 300px');
});

it('returns empty size style for cover position', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): \Caresome\FilamentAuthDesigner\Data\AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/login.jpg')
            ->mediaSize('50%')
        );

    $plugin->configureRepository();

    $loginPage = new Login;
    $loginPage->boot();

    $shared = View::getShared();
    expect($shared['authDesignerConfig']->getMediaSizeStyle())->toBe('');
});
