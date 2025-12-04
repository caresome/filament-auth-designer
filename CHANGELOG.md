# Changelog

All notable changes to this project will be documented in this file.

## v2.0.0 - 2025-XX-XX

### Added

- New **closure-based API** for more flexible configuration
- **Global defaults** - Set defaults that apply to all auth pages with `->defaults()`
- **Custom page class support** - Use your own page classes with `->usingPage()` (solves GitHub Issue #1)
- New **Stacked layout** - Mobile-optimized layout with media at top and form below
- **Alt text support** for media (accessibility improvement)
- `AuthPageConfig` data class for configuration
- `AuthDesignerConfig` DTO for views
- `AuthDesignerConfigRepository` singleton for centralized configuration
- `MediaDetector` support class for improved media type detection
- Plugin Concerns traits (HasDefaults, HasPages, HasThemeSwitcher) following Filament patterns
- Separate Blade partials for each layout type (better maintainability)
- Helper methods on `AuthLayout` enum (`supportsBlur()`, `supportsDirection()`, `requiresMedia()`)

### Changed

- **BREAKING**: API changed from method parameters to closure-based configuration
  - Before: `->login(AuthLayout::Overlay, asset('bg.jpg'), MediaDirection::Left, 8)`
  - After: `->login(fn ($config) => $config->layout(AuthLayout::Overlay)->media(asset('bg.jpg'))->direction(MediaDirection::Left)->blur(8))`
- Refactored monolithic 175-line Blade template into separate layout partials
- View data now uses single `$authDesignerConfig` DTO instead of multiple variables
- Configuration storage moved from `app()->instance()` to dedicated repository

### Removed

- `ConfigKeys` class (replaced by `AuthDesignerConfigRepository`)
- Multiple view share variables (`$authDesignerMedia`, `$authDesignerPosition`, etc.) - now uses single `$authDesignerConfig`

### Fixed

- Plugin no longer overrides custom auth page classes (GitHub Issue #1)
- Media type detection now handles URLs with query strings

## v1.0.0 - 2025-10-12

### Added

- Initial release
- Five layout types for auth pages (None, Split, Overlay, Top Banner, Side Panel)
- Media background support for images and videos
- Customizable media positioning (left/right)
- Blur effects for overlay layouts with configurable intensity
- Light/dark/system theme toggle
- Theme toggle position control (top-right, top-left, bottom-right, bottom-left)
- Logout button on email verification page for easy account switching
- Comprehensive test suite with 19 tests and 75 assertions
- Full integration with Filament v4
- CSS variable-based styling for theme compatibility
- Centered media positioning with object-fit cover
