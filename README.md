# Filament Auth Designer

[![Latest Version](https://img.shields.io/packagist/v/caresome/filament-auth-designer.svg?style=flat-square)](https://packagist.org/packages/caresome/filament-auth-designer)

Transform Filament's default auth pages with customizable layouts, media backgrounds, and theme switcher.

## Features

- 🎨 **Five Layout Types** - Choose from None, Split, Overlay, Top Banner, or Side Panel
- 🖼️ **Media Backgrounds** - Support for both images and videos
- 📐 **Flexible Positioning** - Place media on left or right side
- 🌫️ **Blur Effects** - Configurable blur intensity for overlay layouts
- 🌓 **Theme Toggle** - Built-in light/dark/system theme switcher
- 📍 **Positionable Theme Toggle** - Place theme switcher in any corner
- 🚪 **Email Verification Logout** - Logout button on email verification page for easy account switching
- ⚡ **Zero Configuration** - Works out of the box with sensible defaults


![Thumbnail](https://github.com/user-attachments/assets/9cbb32f1-d73c-4962-b73a-123a48f0efc2)



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
use Caresome\FilamentAuthDesigner\Enums\AuthLayout;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configuration
        ->plugin(
            AuthDesignerPlugin::make()
                ->login(
                    layout: AuthLayout::Overlay,
                    media: asset('assets/image.jpg')
                )
        );
}
```

### Layout Types

#### 1. None (Default)
Standard Filament auth page with no media:

```php
->login(layout: AuthLayout::None)
```

![none-layout](https://github.com/user-attachments/assets/7b6b89a7-c245-43c1-935a-dd7e0a63e74e)


#### 2. Split Layout
Split-screen with media on one side:

```php
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;

->login(
    layout: AuthLayout::Split,
    media: asset('assets/image.jpg'),
    direction: MediaDirection::Right // or MediaDirection::Left
)
```

![split-layout](https://github.com/user-attachments/assets/a4d7de54-0703-4aac-9e78-ebbc2161bebb)


#### 3. Overlay Layout
Fullscreen background with form overlay:

```php
->login(
    layout: AuthLayout::Overlay,
    media: asset('assets/image.jpg'),
    blur: 8 // Blur intensity (0-20), default is 0
)
```

![overlay-layout](https://github.com/user-attachments/assets/84dba0d0-94e4-4aa6-8e86-e1c8702f392b)


#### 4. Top Banner Layout
Media banner at the top with form below:

```php
->login(
    layout: AuthLayout::Top,
    media: asset('assets/image.jpg')
)
```

![top-layout](https://github.com/user-attachments/assets/e781e0c5-f2d3-47fa-8ec4-15b756d3b8f5)


#### 5. Side Panel Layout
Fullscreen background with side panel form:

```php
->login(
    layout: AuthLayout::Panel,
    media: asset('assets/image.jpg'),
    direction: MediaDirection::Right // Panel position
)
```

![panel-layout](https://github.com/user-attachments/assets/4d08b7d8-5a3c-4c9e-b862-f33e0ed3bea7)


### Video Backgrounds

Simply provide a video URL instead of an image:

```php
->login(
    layout: AuthLayout::Overlay,
    media: asset('assets/video.mp4')
)
```


https://github.com/user-attachments/assets/154006f8-91b6-4e6e-9ed9-854442fe6a49



Supported formats: `.mp4`, `.webm`, `.mov`, `.ogg`

### Configuring Multiple Auth Pages

You can configure each auth page independently:

```php
AuthDesignerPlugin::make()
    ->login(
        layout: AuthLayout::Overlay,
        media: '/images/login-bg.jpg',
        blur: 10
    )
    ->registration(
        layout: AuthLayout::Split,
        media: asset('assets/image.jpg'),
        direction: MediaDirection::Left
    )
    ->passwordReset(
        layout: AuthLayout::Top,
        media: asset('assets/image.jpg')
    )
    ->emailVerification(
        layout: AuthLayout::Panel,
        media: asset('assets/image.jpg')
    )
```

### Theme Toggle

Enable the theme switcher for light/dark mode:

```php
use Caresome\FilamentAuthDesigner\Enums\ThemePosition;

AuthDesignerPlugin::make()
    ->login(layout: Layout::Overlay, media: asset('assets/image.jpg'))
    ->themeToggle() // Default position: top-right

// Or specify position:
->themeToggle(ThemePosition::BottomLeft)
```

Available positions:
- `ThemePosition::TopRight` (default)
- `ThemePosition::TopLeft`
- `ThemePosition::BottomRight`
- `ThemePosition::BottomLeft`


![theme-position](https://github.com/user-attachments/assets/748677ea-ebcf-4831-bf80-9ee03b42dfc9)


### Complete Example

```php
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\AuthLayout;
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
                    layout: AuthLayout::Overlay,
                    media: asset('assets/image.jpg'),
                    blur: 8
                )
                ->registration(
                    layout: AuthLayout::Split,
                    media: asset('assets/image.jpg'),
                    direction: MediaDirection::Left
                )
                ->emailVerification(
                    layout: AuthLayout::Panel,
                    media: asset('assets/video.mp4'),
                    direction: MediaDirection::Left
                )
                ->passwordReset(
                    layout: AuthLayout::Top,
                    media: asset('assets/video.mp4')
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
