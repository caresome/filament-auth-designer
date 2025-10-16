# Filament Auth Designer

[![Latest Version](https://img.shields.io/packagist/v/caresome/filament-auth-designer.svg?style=flat-square)](https://packagist.org/packages/caresome/filament-auth-designer)

Transform Filament's default authentication pages into stunning, brand-ready experiences with customizable layouts, media backgrounds, and theme switching.

> **Note:** This package is designed exclusively for **Filament v4**. For changes and updates, see the [CHANGELOG](CHANGELOG.md).

![caresome-auth-designer](https://github.com/user-attachments/assets/e8f14d21-a1b8-4929-a5fc-653b7563dfe1)


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

![none-layout](https://github.com/user-attachments/assets/533a317a-7f39-4858-a643-2ec4332ca6ce)


### 2. Split

```php
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;

->login(
    layout: AuthLayout::Split,
    media: asset('assets/image.jpg'),
    direction: MediaDirection::Right // or MediaDirection::Left
)
```

![split-layout](https://github.com/user-attachments/assets/1f0defb2-b3c3-44f0-b8d7-34976ad04e40)


### 3. Overlay

```php
->login(
    layout: AuthLayout::Overlay,
    media: asset('assets/image.jpg'),
    blur: 8 // Optional: 0-20, default is 0
)
```

![overlay-layout](https://github.com/user-attachments/assets/de07e38d-0914-48e2-b37a-35beef1573c4)


### 4. Top

```php
->login(
    layout: AuthLayout::Top,
    media: asset('assets/banner.jpg')
)
```

![top-layout](https://github.com/user-attachments/assets/28ff06e9-d9b5-40a0-9e0a-9817c06496ed)


### 5. Panel

```php
->login(
    layout: AuthLayout::Panel,
    media: asset('assets/image.jpg'),
    direction: MediaDirection::Left // or MediaDirection::Right
)
```

![panel-layout](https://github.com/user-attachments/assets/76c02571-725e-40f2-884c-1463a85431de)


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

![theme-position](https://github.com/user-attachments/assets/07be8080-9733-49d7-bef7-123be1d98997)


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
