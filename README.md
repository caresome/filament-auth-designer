# Filament Auth Designer

[![Latest Version](https://img.shields.io/packagist/v/caresome/filament-auth-designer.svg?style=flat-square)](https://packagist.org/packages/caresome/filament-auth-designer)

Transform Filament's default auth pages into stunning, brand-ready experiences with customizable layouts, media backgrounds, and theme switching.

## Features

- 🎨 **Five Layout Types** - Choose from None, Side, Overlay, Top Banner, or Side Panel
- 🖼️ **Media Backgrounds** - Support for both images and videos
- 📐 **Flexible Positioning** - Place media on left or right side
- 🌫️ **Blur Effects** - Configurable blur intensity for overlay layouts
- 🌓 **Theme Toggle** - Built-in light/dark/system theme switcher
- 📍 **Positionable Theme Toggle** - Place theme switcher in any corner
- ⚡ **Zero Configuration** - Works out of the box with sensible defaults

## Installation

Install the package via Composer:

```bash
composer require caresome/filament-auth-designer
```

## Usage

### Basic Setup

In your Filament Panel Provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`):

```php
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\Layout;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configuration
        ->plugin(
            AuthDesignerPlugin::make()
                ->login(
                    layout: Layout::Overlay,
                    media: '/images/auth-background.jpg'
                )
        );
}
```

### Layout Types

#### 1. None (Default)
Standard Filament auth page with no media:

```php
->login(layout: Layout::None)
```

#### 2. Side Layout
Split-screen with media on one side:

```php
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;

->login(
    layout: Layout::Side,
    media: '/images/login-bg.jpg',
    direction: MediaDirection::Right // or MediaDirection::Left
)
```

#### 3. Overlay Layout
Fullscreen background with form overlay:

```php
->login(
    layout: Layout::Overlay,
    media: '/images/login-bg.jpg',
    blur: 8 // Blur intensity (0-20), default is 0
)
```

#### 4. Top Banner Layout
Media banner at the top with form below:

```php
->login(
    layout: Layout::Top,
    media: '/images/login-banner.jpg'
)
```

#### 5. Side Panel Layout
Fullscreen background with side panel form:

```php
->login(
    layout: Layout::Panel,
    media: '/images/login-bg.jpg',
    direction: MediaDirection::Right // Panel position
)
```

### Video Backgrounds

Simply provide a video URL instead of an image:

```php
->login(
    layout: Layout::Overlay,
    media: '/videos/background.mp4'
)
```

Supported formats: `.mp4`, `.webm`, `.mov`, `.ogg`

### Configuring Multiple Auth Pages

You can configure each auth page independently:

```php
AuthDesignerPlugin::make()
    ->login(
        layout: Layout::Overlay,
        media: '/images/login-bg.jpg',
        blur: 10
    )
    ->registration(
        layout: Layout::Side,
        media: '/images/register-bg.jpg',
        direction: MediaDirection::Left
    )
    ->passwordReset(
        layout: Layout::Top,
        media: '/images/reset-banner.jpg'
    )
    ->emailVerification(
        layout: Layout::Panel,
        media: '/images/verify-bg.jpg'
    )
```

### Theme Toggle

Enable the theme switcher for light/dark mode:

```php
use Caresome\FilamentAuthDesigner\Enums\ThemePosition;

AuthDesignerPlugin::make()
    ->login(layout: Layout::Overlay, media: '/images/bg.jpg')
    ->themeToggle() // Default position: top-right

// Or specify position:
->themeToggle(ThemePosition::BottomLeft)
```

Available positions:
- `ThemePosition::TopRight` (default)
- `ThemePosition::TopLeft`
- `ThemePosition::BottomRight`
- `ThemePosition::BottomLeft`

### Complete Example

```php
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\Layout;
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;
use Caresome\FilamentAuthDesigner\Enums\ThemePosition;

public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->path('admin')
        ->plugin(
            AuthDesignerPlugin::make()
                ->login(
                    layout: Layout::Overlay,
                    media: '/images/auth/login-bg.jpg',
                    blur: 8
                )
                ->registration(
                    layout: Layout::Side,
                    media: '/images/auth/register-bg.jpg',
                    direction: MediaDirection::Left
                )
                ->passwordReset(
                    layout: Layout::Top,
                    media: '/videos/reset-banner.mp4'
                )
                ->themeToggle(ThemePosition::TopRight)
        );
}
```

## Testing

```bash
composer test
```

## Code Quality

```bash
composer analyse  # Run PHPStan
composer format   # Format code with Laravel Pint
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
