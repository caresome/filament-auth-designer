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

#### 1. None/Minimal (Default)
Standard Filament auth page with no media:

```php
->login(layout: AuthLayout::None)
```


![none-layout](https://github.com/user-attachments/assets/2bd34599-2578-474e-a47b-26e13f6b8fbb)


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

![split-layout](https://github.com/user-attachments/assets/7e16dbc4-b968-4334-a17b-3a59d1b47135)


#### 3. Overlay Layout
Fullscreen background with form overlay:

```php
->login(
    layout: AuthLayout::Overlay,
    media: asset('assets/image.jpg'),
    blur: 8 // Blur intensity (0-20), default is 0
)
```

![overlay-layout](https://github.com/user-attachments/assets/da60d0d8-bfec-44a4-9299-0978f656ba04)


#### 4. Top Banner Layout
Media banner at the top with form below:

```php
->login(
    layout: AuthLayout::Top,
    media: asset('assets/image.jpg')
)
```

![top-layout](https://github.com/user-attachments/assets/6e5004b2-7433-40b3-ae27-1fb780e14024)


#### 5. Side Panel Layout
Fullscreen background with side panel form:

```php
->login(
    layout: AuthLayout::Panel,
    media: asset('assets/image.jpg'),
    direction: MediaDirection::Right // Panel position
)
```

![panel-layout](https://github.com/user-attachments/assets/93556a94-d55f-474e-9476-993201521ced)


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


![theme-position](https://github.com/user-attachments/assets/12d891f8-85fd-4a30-be5f-a986a151f9e4)


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
