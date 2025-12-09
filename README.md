# Filament Auth Designer

[![Latest Version](https://img.shields.io/packagist/v/caresome/filament-auth-designer.svg?style=flat-square)](https://packagist.org/packages/caresome/filament-auth-designer)

Transform Filament's default authentication pages into stunning, brand-ready experiences with customizable layouts, media backgrounds, and theme switching.

> **Note:** This package is designed exclusively for **Filament v4**. For changes and updates, see the [CHANGELOG](CHANGELOG.md).

![filament-auth-designer-preview](https://github.com/user-attachments/assets/441dba74-3817-4f27-9e9c-99006b77aa36)

## Table of Contents

-   [Features](#features)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Quick Start](#quick-start)
-   [Media Positioning](#media-positioning)
-   [Global Defaults](#global-defaults)
-   [Custom Page Classes](#custom-page-classes)
-   [Media Configuration](#media-configuration)
-   [Theme Toggle](#theme-toggle)
-   [Configuration Examples](#configuration-examples)
-   [Render Hooks](#render-hooks)
-   [Troubleshooting](#troubleshooting)
-   [Testing](#testing)
-   [License](#license)

## Features

-   🎨 **Flexible Media Positioning** - Place media on any side (Left, Right, Top, Bottom) or as a fullscreen cover
-   📐 **Custom Sizing** - Set media size with any CSS unit (%, px, vh, etc.)
-   🖼️ **Media Backgrounds** - Support for both images and videos with auto-detection
-   🌫️ **Blur Effects** - Configurable blur intensity (0-20) for Cover position
-   🌓 **Theme Toggle** - Built-in light/dark/system theme switcher
-   📍 **Positionable Theme Toggle** - Place theme switcher in any corner
-   🔧 **Global Defaults** - Set defaults that apply to all auth pages
-   🎯 **Per-Page Overrides** - Override defaults for specific pages
-   🔌 **Custom Page Classes** - Use your own page classes with the plugin's layouts
-   🪝 **Render Hooks** - Inject custom content at specific positions in layouts
-   ♿ **Accessibility** - Alt text support for media
-   🚪 **Email Verification Logout** - Easy account switching from verification page
-   ⚡ **Zero Configuration** - Works out of the box with sensible defaults

## Requirements

-   PHP 8.2+
-   Laravel 11.0 or 12.0
-   Filament 4.0

## Installation

```bash
composer require caresome/filament-auth-designer
```

## Quick Start

Add to your Panel Provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`):

```php
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(
            AuthDesignerPlugin::make()
                ->login(fn (AuthPageConfig $config) => $config
                    ->media(asset('assets/background.jpg'))
                    ->mediaPosition(MediaPosition::Cover)
                    ->blur(8)
                )
        );
}
```

## Media Positioning

Use `MediaPosition` to control where your media appears:

| Position   | Description                              | Size Applied As |
| ---------- | ---------------------------------------- | --------------- |
| **Left**   | Media on left, form on right             | Width           |
| **Right**  | Media on right, form on left             | Width           |
| **Top**    | Media at top, form below                 | Height          |
| **Bottom** | Media at bottom, form above              | Height          |
| **Cover**  | Fullscreen background with centered form | Ignored         |

### Default Behavior

-   **No media** → Minimal centered form
-   **Media without position** → Defaults to `Cover`
-   **Cover position** → `mediaSize()` is ignored (fullscreen)

### Position Examples

#### Left Position (Split-style)

```php
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;

->login(fn ($config) => $config
    ->media(asset('assets/image.jpg'))
    ->mediaPosition(MediaPosition::Left)
    ->mediaSize('50%') // Media takes 50% width
)
```

#### Right Position

```php
->login(fn ($config) => $config
    ->media(asset('assets/image.jpg'))
    ->mediaPosition(MediaPosition::Right)
    ->mediaSize('40%')
)
```

#### Top Position (Banner-style)

```php
->login(fn ($config) => $config
    ->media(asset('assets/banner.jpg'))
    ->mediaPosition(MediaPosition::Top)
    ->mediaSize('250px') // Banner height
)
```

#### Bottom Position

```php
->login(fn ($config) => $config
    ->media(asset('assets/footer.jpg'))
    ->mediaPosition(MediaPosition::Bottom)
    ->mediaSize('200px')
)
```

#### Cover Position (Overlay-style)

```php
->login(fn ($config) => $config
    ->media(asset('assets/fullscreen.jpg'))
    ->mediaPosition(MediaPosition::Cover)
    ->blur(8) // Optional: 0-20
)
```

### Size Units

Use any valid CSS unit for `mediaSize()`:

```php
->mediaSize('400px')   // Pixels
->mediaSize('30vh')    // Viewport height
->mediaSize('20rem')   // Rem units
```

## Global Defaults

Set defaults that apply to all auth pages, then override specific pages as needed:

```php
AuthDesignerPlugin::make()
    ->defaults(fn ($config) => $config
        ->media(asset('assets/default-bg.jpg'))
        ->mediaPosition(MediaPosition::Cover)
        ->blur(8)
    )
    ->login() // Uses defaults
    ->registration() // Uses defaults
    ->passwordReset(fn ($config) => $config
        ->mediaPosition(MediaPosition::Left) // Override position
        ->mediaSize('45%')
    )
    ->emailVerification() // Uses defaults
    ->themeToggle()
```

## Custom Page Classes

Use your own custom auth page classes while keeping the plugin's layout features. This is useful when you need to customize the form (e.g., using username instead of email).

### Option 1: Extend the Plugin's Page

```php
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;

class CustomLogin extends Login
{
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('username')->label('Username')->required(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }
}

// In your panel provider:
AuthDesignerPlugin::make()
    ->login(fn ($config) => $config
        ->media(asset('assets/login-bg.jpg'))
        ->mediaPosition(MediaPosition::Cover)
        ->usingPage(CustomLogin::class)
    )
```

### Option 2: Use the Trait Directly

```php
use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Pages\Auth\Login;

class CustomLogin extends Login
{
    use HasAuthDesignerLayout;

    protected function getAuthDesignerPageKey(): string
    {
        return 'login';
    }

    // Your customizations...
}

// In your panel provider:
AuthDesignerPlugin::make()
    ->login(fn ($config) => $config
        ->usingPage(CustomLogin::class)
    )
```

## Media Configuration

### Images

Supported formats: `.jpg`, `.jpeg`, `.png`, `.webp`, `.gif`, `.svg`, `.avif`

```php
->login(fn ($config) => $config
    ->media(asset('assets/background.jpg'))
)
```

### Videos

Supported formats: `.mp4`, `.webm`, `.mov`, `.ogg`

Videos auto-play, loop continuously, and are muted by default.

```php
->login(fn ($config) => $config
    ->media(asset('assets/video.mp4'))
)
```

https://github.com/user-attachments/assets/154006f8-91b6-4e6e-9ed9-854442fe6a49

### Alt Text (Accessibility)

```php
->login(fn ($config) => $config
    ->media(asset('assets/background.jpg'), alt: 'Company branding image')
)
```

## Theme Toggle

Enable light/dark/system theme switcher:

```php
->themeToggle() // Default: Top Right (1.5rem)
->themeToggle(bottom: '2rem', right: '2rem') // Custom position
->themeToggle(top: '1rem', left: '1rem') // Top Left
```

You can position the theme switcher anywhere on the screen by passing `top`, `bottom`, `left`, or `right` CSS values. Defaults to `auto` if not specified.

You can also override the theme switcher position for specific pages:

```php
->login(fn ($config) => $config
    ->themeToggle(bottom: '2rem', left: '2rem')
)
```

![theme-position](https://github.com/user-attachments/assets/07be8080-9733-49d7-bef7-123be1d98997)

## Configuration Examples

### Simple - Same Layout for All Pages

```php
AuthDesignerPlugin::make()
    ->defaults(fn ($config) => $config
        ->media(asset('assets/auth-bg.jpg'))
        ->mediaPosition(MediaPosition::Cover)
        ->blur(10)
    )
    ->login()
    ->registration()
    ->passwordReset()
    ->emailVerification()
    ->themeToggle()
```

### Advanced - Different Layout Per Page

```php
AuthDesignerPlugin::make()
    ->defaults(fn ($config) => $config
        ->media(asset('assets/default-bg.jpg'))
        ->mediaPosition(MediaPosition::Right)
        ->mediaSize('50%')
    )
    ->login(fn ($config) => $config
        ->media(asset('assets/login.jpg'), alt: 'Welcome back')
        ->mediaPosition(MediaPosition::Cover)
        ->blur(8)
    )
    ->registration(fn ($config) => $config
        ->media(asset('assets/register.jpg'))
        ->mediaPosition(MediaPosition::Left)
        ->mediaSize('45%')
    )
    ->passwordReset(fn ($config) => $config
        ->media(asset('assets/reset.jpg'))
        ->mediaPosition(MediaPosition::Top)
        ->mediaSize('200px')
    )
    ->emailVerification() // Uses defaults
    ->profile(fn ($config) => $config
        ->media(asset('assets/profile-bg.jpg'))
        ->mediaPosition(MediaPosition::Right)
    )
    ->themeToggle(bottom: '2rem', right: '2rem')
```

### Available Methods

| Method                  | Description                                                 |
| ----------------------- | ----------------------------------------------------------- |
| `->defaults()`          | Set global defaults for all pages                           |
| `->login()`             | Configure login page                                        |
| `->registration()`      | Configure registration page                                 |
| `->passwordReset()`     | Configure password reset pages                              |
| `->emailVerification()` | Configure email verification page                           |
| `->profile()`           | Configure profile page                                      |
| `->themeToggle()`       | Enable theme switcher (defaults to top-right, customizable) |

### Configuration Options

| Option              | Description                    | Notes                                  |
| ------------------- | ------------------------------ | -------------------------------------- |
| `->media()`         | Set background image/video URL | First param is URL, second is alt text |
| `->mediaPosition()` | Set media position             | Left, Right, Top, Bottom, Cover        |
| `->mediaSize()`     | Set media size                 | px/vh/rem; ignored for Cover           |
| `->blur()`          | Blur intensity (0-20)          | Applies to all positions               |
| `->usingPage()`     | Use custom page class          | For custom auth pages                  |
| `->themeToggle()`   | Set theme switcher position    | Per-page override                      |

## Render Hooks

Inject custom Blade content at specific positions within auth layouts:

```php
use Caresome\FilamentAuthDesigner\View\AuthDesignerRenderHook;

AuthDesignerPlugin::make()
    ->login(fn ($config) => $config
        ->media(asset('images/login-bg.jpg'))
        ->mediaPosition(MediaPosition::Cover)
        ->renderHook(AuthDesignerRenderHook::CardBefore, fn () => view('auth.branding'))
    )
```

### Available Hook Positions

> **Note:** `CardBefore` and `CardAfter` are specific to the **Cover** layout where the form is inside a card.
> For other layouts (Left, Right, etc.), where the form is not inside a card, use Filament's native render hooks:
>
> -   `PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE`
> -   `PanelsRenderHook::AUTH_LOGIN_FORM_AFTER`

| Hook           | Description                     | Available In           |
| -------------- | ------------------------------- | ---------------------- |
| `MediaOverlay` | Overlay content on top of media | All layouts with media |
| `CardBefore`   | Above the login card            | Cover position only    |
| `CardAfter`    | Below the login card            | Cover position only    |

### Hook Examples

**Add branding above the login card (Cover position):**

```php
->login(fn ($config) => $config
    ->renderHook(AuthDesignerRenderHook::CardBefore, fn () => view('auth.branding'))
)
```

**Add company logo overlay on media:**

```php
->login(fn ($config) => $config
    ->renderHook(AuthDesignerRenderHook::MediaOverlay, fn () => view('auth.logo-overlay'))
)
```

**Multiple hooks at the same position:**

```php
->login(fn ($config) => $config
    ->renderHook(AuthDesignerRenderHook::CardBefore, fn () => view('auth.logo'))
    ->renderHook(AuthDesignerRenderHook::CardBefore, fn () => view('auth.welcome-message'))
)
```

## Troubleshooting

**Images not displaying:**

-   Verify asset path: `asset('path/to/image.jpg')`
-   Ensure files are in `public/` directory
-   Clear cache: `php artisan cache:clear`
-   Check browser console for 404 errors

**Layout not applying:**

-   Clear view cache: `php artisan view:clear`
-   Verify enum usage: `MediaPosition::Cover` (not string)
-   Check plugin is registered in panel provider

**Videos not auto-playing:**

-   Ensure format is supported (mp4, webm, mov, ogg)
-   Check browser autoplay policies
-   Test in different browsers

**Blur effect not working:**

-   Value must be between 0-20
-   Some older browsers may not support backdrop-filter

**Custom page not using layout:**

-   Ensure your custom page uses `HasAuthDesignerLayout` trait
-   Or extend the plugin's page class
-   Verify you're using `->usingPage()` in the config

**Media size not applying:**

-   `mediaSize()` is ignored for `Cover` position
-   Ensure you're using a valid CSS unit

## Testing

```bash
composer test          # Run tests
composer analyse       # Run PHPStan
composer format        # Format code with Pint
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
