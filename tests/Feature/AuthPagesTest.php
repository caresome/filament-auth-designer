<?php

use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\AuthLayout;
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;
use Caresome\FilamentAuthDesigner\Pages\Auth\Register;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    View::getFinder()->flush();
});

it('login page shares configuration to view', function () {
    AuthDesignerPlugin::make()
        ->login(
            layout: AuthLayout::Overlay,
            media: '/images/login-bg.jpg',
            direction: MediaDirection::Left,
            blur: 10
        );

    $loginPage = new Login;
    $loginPage->boot();

    expect(View::getShared())->toHaveKey('authDesignerMedia', '/images/login-bg.jpg')
        ->toHaveKey('authDesignerPosition', AuthLayout::Overlay)
        ->toHaveKey('authDesignerDirection', MediaDirection::Left)
        ->toHaveKey('authDesignerBlur', 10);
});

it('registration page shares configuration to view', function () {
    AuthDesignerPlugin::make()
        ->registration(
            layout: AuthLayout::Split,
            media: '/images/register-bg.jpg',
            direction: MediaDirection::Right,
            blur: 5
        );

    $registerPage = new Register;
    $registerPage->boot();

    expect(View::getShared())->toHaveKey('authDesignerMedia', '/images/register-bg.jpg')
        ->toHaveKey('authDesignerPosition', AuthLayout::Split)
        ->toHaveKey('authDesignerDirection', MediaDirection::Right)
        ->toHaveKey('authDesignerBlur', 5);
});

it('auth page uses default values when no configuration provided', function () {
    $loginPage = new Login;
    $loginPage->boot();

    expect(View::getShared())->toHaveKey('authDesignerMedia', null)
        ->toHaveKey('authDesignerPosition', null)
        ->toHaveKey('authDesignerDirection', null)
        ->toHaveKey('authDesignerBlur', 0);
});

it('different auth pages have isolated configurations', function () {
    AuthDesignerPlugin::make()
        ->login(layout: AuthLayout::Overlay, media: '/login.jpg', blur: 10)
        ->registration(layout: AuthLayout::Split, media: '/register.jpg', blur: 0);

    $loginPage = new Login;
    $loginPage->boot();

    $shared = View::getShared();
    expect($shared['authDesignerMedia'])->toBe('/login.jpg')
        ->and($shared['authDesignerPosition'])->toBe(AuthLayout::Overlay)
        ->and($shared['authDesignerBlur'])->toBe(10);

    View::getFinder()->flush();

    $registerPage = new Register;
    $registerPage->boot();

    $shared = View::getShared();
    expect($shared['authDesignerMedia'])->toBe('/register.jpg')
        ->and($shared['authDesignerPosition'])->toBe(AuthLayout::Split)
        ->and($shared['authDesignerBlur'])->toBe(0);
});

it('login page returns correct page key', function () {
    $loginPage = new Login;
    $reflection = new ReflectionClass($loginPage);
    $method = $reflection->getMethod('getAuthDesignerPageKey');
    $method->setAccessible(true);

    expect($method->invoke($loginPage))->toBe('login');
});

it('registration page returns correct page key', function () {
    $registerPage = new Register;
    $reflection = new ReflectionClass($registerPage);
    $method = $reflection->getMethod('getAuthDesignerPageKey');
    $method->setAccessible(true);

    expect($method->invoke($registerPage))->toBe('registration');
});
