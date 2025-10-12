# Filament Auth Designer

[![Latest Version](https://img.shields.io/packagist/v/caresome/filament-auth-designer.svg?style=flat-square)](https://packagist.org/packages/caresome/filament-auth-designer)

Transform Filament's default authentication pages into stunning, brand-ready experiences with customizable layouts, media backgrounds, and theme switching.

> **Note:** This package is designed exclusively for **Filament v4**. For changes and updates, see the [CHANGELOG](CHANGELOG.md).

![Thumbnail](https://github.com/user-attachments/assets/9cbb32f1-d73c-4962-b73a-123a48f0efc2)

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Layout Types](#layout-types)
- [Media Configuration](#media-configuration)
- [Theme Toggle](#theme-toggle)
- [Configuration Examples](#configuration-examples)
- [Troubleshooting](#troubleshooting)
- [Testing](#testing)
- [License](#license)

## Features

- 🎨 **Five Layout Types** - None, Split, Overlay, Top Banner, and Side Panel layouts
- 🖼️ **Media Backgrounds** - Support for both images and videos with auto-detection
- 📐 **Flexible Positioning** - Place media on left or right side (Split/Panel layouts)
- 🌫️ **Blur Effects** - Configurable blur intensity (0-20) for Overlay layout
- 🌓 **Theme Toggle** - Built-in light/dark/system theme switcher
- 📍 **Positionable Theme Toggle** - Place theme switcher in any corner
- 🚪 **Email Verification Logout** - Easy account switching from verification page
- ⚡ **Zero Configuration** - Works out of the box with sensible defaults
- 🎯 **Per-Page Configuration** - Different layouts for login, registration, password reset, and email verification

## Requirements

- PHP 8.2+
- Laravel 11.0 or 12.0
- Filament 4.0

## Installation

```bash
composer require caresome/filament-auth-designer
```

## Quick Start

Add to your Panel Provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`):

```php
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\AuthLayout;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(
            AuthDesignerPlugin::make()
                ->login(
                    layout: AuthLayout::Overlay,
                    media: asset('assets/background.jpg')
                )
        );
}
```

## Layout Types

| Layout | Description | Media Position | Blur Support |
|--------|-------------|----------------|--------------|
| **None** | Standard Filament page | N/A | No |
| **Split** | Side-by-side split screen | Left or Right | No |
| **Overlay** | Fullscreen background with centered form | Fullscreen | Yes (0-20) |
| **Top** | Banner at top, form below | Top | No |
| **Panel** | Fullscreen background with side panel | Left or Right | No |

### 1. None (Default)

```php
->login(layout: AuthLayout::None)
```

![none-layout](https://github.com/user-attachments/assets/2bd34599-2578-474e-a47b-26e13f6b8fbb)

### 2. Split

```php
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;

->login(
    layout: AuthLayout::Split,
    media: asset('assets/image.jpg'),
    direction: MediaDirection::Right // or MediaDirection::Left
)
```

![split-layout](https://github.com/user-attachments/assets/7e16dbc4-b968-4334-a17b-3a59d1b47135)

### 3. Overlay

```php
->login(
    layout: AuthLayout::Overlay,
    media: asset('assets/image.jpg'),
    blur: 8 // Optional: 0-20, default is 0
)
```

![overlay-layout](https://github.com/user-attachments/assets/da60d0d8-bfec-44a4-9299-0978f656ba04)

### 4. Top

```php
->login(
    layout: AuthLayout::Top,
    media: asset('assets/banner.jpg')
)
```

![top-layout](https://github.com/user-attachments/assets/6e5004b2-7433-40b3-ae27-1fb780e14024)

### 5. Panel

```php
->login(
    layout: AuthLayout::Panel,
    media: asset('assets/image.jpg'),
    direction: MediaDirection::Left // or MediaDirection::Right
)
```

![panel-layout](https://github.com/user-attachments/assets/93556a94-d55f-474e-9476-993201521ced)

## Media Configuration

### Images

Supported formats: `.jpg`, `.jpeg`, `.png`, `.webp`, `.gif`, `.svg`

```php
media: asset('assets/background.jpg')
```

### Videos

Supported formats: `.mp4`, `.webm`, `.mov`, `.ogg`

Videos auto-play, loop continuously, and are muted by default.

```php
media: asset('assets/video.mp4')
```

https://github.com/user-attachments/assets/154006f8-91b6-4e6e-9ed9-854442fe6a49

## Theme Toggle

Enable light/dark/system theme switcher:

```php
use Caresome\FilamentAuthDesigner\Enums\ThemePosition;

->themeToggle() // Default: TopRight
->themeToggle(ThemePosition::BottomLeft) // Custom position
```

Available positions: `TopRight`, `TopLeft`, `BottomRight`, `BottomLeft`

![theme-position](https://github.com/user-attachments/assets/12d891f8-85fd-4a30-be5f-a986a151f9e4)

## Configuration Examples

### Multiple Auth Pages

Configure each auth page independently:

```php
AuthDesignerPlugin::make()
    ->login(
        layout: AuthLayout::Overlay,
        media: asset('assets/login-bg.jpg'),
        blur: 10
    )
    ->registration(
        layout: AuthLayout::Split,
        media: asset('assets/register-bg.jpg'),
        direction: MediaDirection::Left
    )
    ->passwordReset(
        layout: AuthLayout::Top,
        media: asset('assets/reset-banner.jpg')
    )
    ->emailVerification(
        layout: AuthLayout::Panel,
        media: asset('assets/verify-bg.jpg'),
        direction: MediaDirection::Right
    )
    ->themeToggle(ThemePosition::TopRight)
```

### Available Methods

- `->login()` - Login page
- `->registration()` - Registration page
- `->passwordReset()` - Password reset pages
- `->emailVerification()` - Email verification page
- `->themeToggle()` - Theme switcher (applies to all pages)


## Troubleshooting

**Images not displaying:**
- Verify asset path: `asset('path/to/image.jpg')`
- Ensure files are in `public/` directory
- Clear cache: `php artisan cache:clear`
- Check browser console for 404 errors

**Layout not applying:**
- Clear view cache: `php artisan view:clear`
- Verify enum usage: `AuthLayout::Overlay` (not string)
- Check plugin is registered in panel provider

**Videos not auto-playing:**
- Ensure format is supported (mp4, webm, mov, ogg)
- Check browser autoplay policies
- Test in different browsers

**Blur effect not working:**
- Only works with `AuthLayout::Overlay`
- Value must be between 0-20
- Some older browsers may not support backdrop-filter

## Testing

```bash
composer test          # Run tests
composer analyse       # Run PHPStan
composer format        # Format code with Pint
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
