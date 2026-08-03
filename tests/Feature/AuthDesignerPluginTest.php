<?php

use Caresome\FilamentAuthDesigner\AuthDesignerConfigRepository;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;

beforeEach(function (): void {
    app()->forgetInstance(AuthDesignerConfigRepository::class);
    app()->singleton(AuthDesignerConfigRepository::class);
});

it('stores login configuration with closure-based API', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/images/login-bg.jpg')
            ->blur(10)
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('login');

    expect($config->media)->toBe('/images/login-bg.jpg')
        ->and($config->position)->toBe(MediaPosition::Cover)
        ->and($config->blur)->toBe(10);
});

it('stores registration configuration with closure-based API', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->registration(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::End)
            ->media('/images/register-bg.jpg')
            ->mediaSize('50%')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('registration');

    expect($config->media)->toBe('/images/register-bg.jpg')
        ->and($config->position)->toBe(MediaPosition::End)
        ->and($config->mediaSize)->toBe('50%');
});

it('stores password reset configuration with closure-based API', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->passwordReset(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Top)
            ->media('/images/reset-bg.jpg')
            ->mediaSize('250px')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('password-reset');

    expect($config->media)->toBe('/images/reset-bg.jpg')
        ->and($config->position)->toBe(MediaPosition::Top)
        ->and($config->mediaSize)->toBe('250px');
});

it('stores email verification configuration with closure-based API', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->emailVerification(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Start)
            ->media('/images/verify-bg.jpg')
            ->mediaSize('40%')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('email-verification');

    expect($config->media)->toBe('/images/verify-bg.jpg')
        ->and($config->position)->toBe(MediaPosition::Start)
        ->and($config->mediaSize)->toBe('40%');
});

it('uses default values when no closure is provided', function (): void {
    $plugin = AuthDesignerPlugin::make()->login();

    expect($plugin->hasLogin())->toBeTrue();
});

it('enables theme switcher with default position', function (): void {
    $plugin = AuthDesignerPlugin::make()->themeToggle();

    expect($plugin->hasThemeSwitcher())->toBeTrue()
        ->and($plugin->getThemePosition())->toBe(['top' => '1.5rem', 'end' => '1.5rem', 'bottom' => 'auto', 'start' => 'auto']);
});

it('enables theme switcher with custom position', function (): void {
    $plugin = AuthDesignerPlugin::make()->themeToggle(bottom: '1.5rem', start: '1.5rem');

    expect($plugin->hasThemeSwitcher())->toBeTrue()
        ->and($plugin->getThemePosition())->toBe(['top' => 'auto', 'end' => 'auto', 'bottom' => '1.5rem', 'start' => '1.5rem', 'right' => 'auto', 'left' => 'auto']);
});

it('allows different configurations for different auth pages', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/login.jpg')
        )
        ->registration(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Left)
            ->media('/register.jpg')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);

    expect($repository->getConfig('login')->media)->toBe('/login.jpg')
        ->and($repository->getConfig('login')->position)->toBe(MediaPosition::Cover)
        ->and($repository->getConfig('registration')->media)->toBe('/register.jpg')
        ->and($repository->getConfig('registration')->position)->toBe(MediaPosition::Left);
});

it('applies global defaults to all pages', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->defaults(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/default-bg.jpg')
            ->blur(5)
        )
        ->login()
        ->registration();

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);

    expect($repository->getConfig('login')->media)->toBe('/default-bg.jpg')
        ->and($repository->getConfig('login')->position)->toBe(MediaPosition::Cover)
        ->and($repository->getConfig('login')->blur)->toBe(5)
        ->and($repository->getConfig('registration')->media)->toBe('/default-bg.jpg')
        ->and($repository->getConfig('registration')->position)->toBe(MediaPosition::Cover);
});

it('allows per-page overrides of global defaults', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->defaults(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->media('/default-bg.jpg')
        )
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Start)
            ->media('/login-bg.jpg')
        )
        ->registration();

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);

    expect($repository->getConfig('login')->media)->toBe('/login-bg.jpg')
        ->and($repository->getConfig('login')->position)->toBe(MediaPosition::Start)
        ->and($repository->getConfig('registration')->media)->toBe('/default-bg.jpg')
        ->and($repository->getConfig('registration')->position)->toBe(MediaPosition::Cover);
});

it('allows per-page blur value of zero to override global defaults', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->defaults(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->blur(8)
        )
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->blur(0)
        )
        ->registration();

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);

    expect($repository->getConfig('login')->blur)->toBe(0)
        ->and($repository->getConfig('registration')->blur)->toBe(8);
});

it('supports custom page classes via usingPage', function (): void {
    $customPageClass = 'App\\Filament\\Pages\\Auth\\CustomLogin';

    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Cover)
            ->usingPage($customPageClass)
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $pageConfig = $repository->getPageConfig('login');

    expect($pageConfig->getPageClass())->toBe($customPageClass)
        ->and($pageConfig->hasCustomPage())->toBeTrue();
});

it('supports custom reset password page class via usingResetPage', function (): void {
    $customResetPasswordPageClass = 'App\\Filament\\Pages\\Auth\\CustomResetPassword';

    $plugin = new class extends AuthDesignerPlugin
    {
        public function exposedResetPasswordPageClass(): string
        {
            return $this->getResetPasswordPageClass();
        }
    };

    $plugin->passwordReset(fn (AuthPageConfig $config): AuthPageConfig => $config
        ->usingResetPage($customResetPasswordPageClass)
    );

    expect($plugin->exposedResetPasswordPageClass())->toBe($customResetPasswordPageClass);
});

it('scopes configuration by panel id', function (): void {
    $adminPlugin = (new AuthDesignerPlugin)
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->media('/admin-login.jpg')
        );

    $memberPlugin = (new AuthDesignerPlugin)
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->media('/member-login.jpg')
        );

    $adminPlugin->configureRepository('admin');
    $memberPlugin->configureRepository('member');

    $repository = app(AuthDesignerConfigRepository::class);

    expect($repository->getConfig('login', 'admin')->media)->toBe('/admin-login.jpg')
        ->and($repository->getConfig('login', 'member')->media)->toBe('/member-login.jpg');
});

it('falls back to global configuration when panel-specific config is missing', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->media('/global-login.jpg')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);

    expect($repository->getConfig('login', 'admin')->media)->toBe('/global-login.jpg');
});

it('supports bottom position', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->mediaPosition(MediaPosition::Bottom)
            ->media('/bottom-bg.jpg')
            ->mediaSize('200px')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('login');

    expect($config->position)->toBe(MediaPosition::Bottom)
        ->and($config->mediaSize)->toBe('200px');
});

it('stores theme switcher settings in repository', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login()
        ->themeToggle(end: '1.5rem', bottom: '1.5rem');

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('login');

    expect($config->showThemeSwitcher)->toBeTrue()
        ->and($config->themePosition)->toBe(['top' => 'auto', 'end' => '1.5rem', 'bottom' => '1.5rem', 'start' => 'auto', 'right' => 'auto', 'left' => 'auto']);
});

it('registers render hooks on plugin', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->renderHook('auth-designer::test.hook', fn (): string => 'test content');

    expect($plugin->getRenderHooks())->toHaveKey('auth-designer::test.hook')
        ->and($plugin->getRenderHooks()['auth-designer::test.hook'])->toHaveCount(1);
});

it('allows multiple hooks for same position', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->renderHook('auth-designer::test.hook', fn (): string => 'first')
        ->renderHook('auth-designer::test.hook', fn (): string => 'second');

    expect($plugin->getRenderHooks()['auth-designer::test.hook'])->toHaveCount(2);
});

it('chains render hooks with other plugin methods', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config->mediaPosition(MediaPosition::Cover))
        ->renderHook('auth-designer::card.before', fn (): string => 'branding')
        ->themeToggle();

    expect($plugin->hasLogin())->toBeTrue()
        ->and($plugin->hasThemeSwitcher())->toBeTrue()
        ->and($plugin->getRenderHooks())->toHaveKey('auth-designer::card.before');
});

it('defaults to cover position when media is set without position', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->media('/images/bg.jpg')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('login');

    expect($config->position)->toBe(MediaPosition::Cover);
});

it('has null position when no media is set', function (): void {
    $plugin = AuthDesignerPlugin::make()->login();

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('login');

    expect($config->position)->toBeNull();
});

it('supports all media positions', function (): void {
    $positions = [
        MediaPosition::Start,
        MediaPosition::End,
        MediaPosition::Top,
        MediaPosition::Bottom,
        MediaPosition::Left,
        MediaPosition::Right,
        MediaPosition::Cover,
    ];

    foreach ($positions as $position) {
        app()->forgetInstance(AuthDesignerConfigRepository::class);
        app()->singleton(AuthDesignerConfigRepository::class);

        $plugin = AuthDesignerPlugin::make()
            ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
                ->media('/bg.jpg')
                ->mediaPosition($position)
            );

        $plugin->configureRepository();

        $repository = app(AuthDesignerConfigRepository::class);
        $config = $repository->getConfig('login');

        expect($config->position)->toBe($position);
    }
});

it('supports per-page render hooks', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->media('/bg.jpg')
            ->mediaPosition(MediaPosition::Start)
            ->renderHook('auth-designer::media.overlay', fn (): string => '<div>Overlay Content</div>')
            ->renderHook('auth-designer::card.before', fn (): string => '<div>Branding</div>')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $config = $repository->getConfig('login');

    expect($config->hasRenderHook('auth-designer::media.overlay'))->toBeTrue()
        ->and($config->hasRenderHook('auth-designer::card.before'))->toBeTrue()
        ->and($config->hasRenderHook('auth-designer::non-existent'))->toBeFalse()
        ->and($config->renderHook('auth-designer::media.overlay'))->toBe('<div>Overlay Content</div>')
        ->and($config->renderHook('auth-designer::card.before'))->toBe('<div>Branding</div>');
});

it('per-page render hooks are isolated between pages', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->media('/bg.jpg')
            ->renderHook('auth-designer::media.overlay', fn (): string => 'Login Overlay')
        )
        ->registration(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->media('/bg.jpg')
            ->renderHook('auth-designer::media.overlay', fn (): string => 'Registration Overlay')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $loginConfig = $repository->getConfig('login');
    $registrationConfig = $repository->getConfig('registration');

    expect($loginConfig->renderHook('auth-designer::media.overlay'))->toBe('Login Overlay')
        ->and($registrationConfig->renderHook('auth-designer::media.overlay'))->toBe('Registration Overlay');
});

it('supports per-page theme toggle configuration', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->themeToggle(top: '1rem', end: '1rem') // Global default
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->themeToggle(bottom: '2rem', start: '2rem') // Override for login
        )
        ->registration(); // Use global default

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $loginConfig = $repository->getConfig('login');
    $registrationConfig = $repository->getConfig('registration');

    expect($loginConfig->showThemeSwitcher)->toBeTrue()
        ->and($loginConfig->themePosition)->toBe(['top' => 'auto', 'end' => 'auto', 'bottom' => '2rem', 'start' => '2rem', 'right' => 'auto', 'left' => 'auto'])
        ->and($registrationConfig->showThemeSwitcher)->toBeTrue()
        ->and($registrationConfig->themePosition)->toBe(['top' => '1rem', 'end' => '1rem', 'bottom' => 'auto', 'start' => 'auto', 'right' => 'auto', 'left' => 'auto']);
});

it('supports global render hooks merged with page hooks', function (): void {
    $plugin = AuthDesignerPlugin::make()
        ->defaults(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->renderHook('auth-designer::global', fn (): string => 'Global')
        )
        ->login(fn (AuthPageConfig $config): AuthPageConfig => $config
            ->renderHook('auth-designer::page', fn (): string => 'Page')
        );

    $plugin->configureRepository();

    $repository = app(AuthDesignerConfigRepository::class);
    $loginConfig = $repository->getConfig('login');

    expect($loginConfig->renderHook('auth-designer::global'))->toBe('Global')
        ->and($loginConfig->renderHook('auth-designer::page'))->toBe('Page');
});
