# Changelog

All notable changes to this project will be documented in this file.

## v2.0.0 - 2025-12-06

### Added

-   **Closure-based API** for flexible configuration
-   **Global defaults** via `->defaults()`
-   **Custom page classes** via `->usingPage()`
-   **Dynamic Media Positioning** - Replaces fixed layouts. Supports Left, Right, Top ("Stacked"), Bottom, and Cover
-   **Dynamic Theme Switcher** - Position the theme toggle button anywhere using CSS values, with support for per-page overrides
-   **Render Hooks** - Inject content into layouts
-   **Alt text support** for accessibility

### Changed

-   **BREAKING**: Replaced `AuthLayout` and `MediaDirection` enums with `MediaPosition`
-   **BREAKING**: Replaced `ThemePosition` enum with dynamic named arguments for `themeToggle()`
-   Refactored Blade views to use dynamic inline styles
-   Configuration now uses `AuthDesignerConfigRepository` singleton

### Removed

-   `AuthLayout`, `MediaDirection`, and `ThemePosition` enums
-   `config/auth-designer.php` file
-   `ConfigKeys` class

### Fixed

-   Custom auth page classes are no longer overridden (GitHub Issue #1)
-   Media detection handles URLs with query strings

## v1.0.0 - 2025-10-12

### Added

-   Initial release
-   Five layout types for auth pages (None, Split, Overlay, Top Banner, Side Panel)
-   Media background support for images and videos
-   Customizable media positioning (left/right)
-   Blur effects for overlay layouts with configurable intensity
-   Light/dark/system theme toggle
-   Theme toggle position control (top-right, top-left, bottom-right, bottom-left)
-   Logout button on email verification page for easy account switching
-   Comprehensive test suite with 19 tests and 75 assertions
-   Full integration with Filament v4
-   CSS variable-based styling for theme compatibility
-   Centered media positioning with object-fit cover
