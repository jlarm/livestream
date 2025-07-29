# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Laravel/PHP Commands
- `composer dev` - Start development server with concurrently running: Laravel server, queue worker, log viewer (Pail), and Vite dev server
- `composer dev:ssr` - Development with Server-Side Rendering enabled
- `composer test` - Run all tests (clears config cache and runs PHPUnit via Pest)
- `php artisan test` - Run tests directly
- `php artisan serve` - Start Laravel development server only
- `php artisan queue:listen --tries=1` - Start queue worker
- `php artisan pail --timeout=0` - Start log viewer
- `vendor/bin/pint` - Format PHP code using Laravel Pint

### Frontend Commands
- `npm run dev` - Start Vite development server
- `npm run build` - Build for production
- `npm run build:ssr` - Build with SSR support
- `npm run lint` - Lint and fix TypeScript/Vue files with ESLint
- `npm run format` - Format code with Prettier
- `npm run format:check` - Check code formatting

## Architecture Overview

This is a Laravel application with an Inertia.js + Vue 3 frontend stack:

### Backend Architecture
- **Framework**: Laravel 12 with PHP 8.2+
- **Database**: SQLite (development), configured in `database/database.sqlite`
- **Testing**: Pest PHP testing framework
- **Authentication**: Laravel Breeze-style authentication with email verification
- **Queue**: Database-backed queues for background jobs
- **Logging**: Laravel Pail for real-time log viewing

### Frontend Architecture
- **Framework**: Vue 3 with TypeScript
- **Routing**: Inertia.js for SPA-like experience without separate API
- **Styling**: Tailwind CSS v4 with custom UI components
- **UI Components**: Custom component library in `resources/js/components/ui/` (Avatar, Button, Card, Dialog, etc.)
- **Build Tool**: Vite with Laravel plugin
- **State Management**: Vue 3 composition API with composables

### Key Directory Structure
- `app/Http/Controllers/` - Laravel controllers (Auth, Settings)
- `resources/js/pages/` - Inertia page components
- `resources/js/components/` - Vue components (including `/ui/` component library)
- `resources/js/layouts/` - Layout components (App, Auth, Settings)
- `resources/js/composables/` - Vue composables (useAppearance, useInitials)
- `routes/` - Route definitions split by purpose (web.php, auth.php, settings.php)
- `tests/Feature/` and `tests/Unit/` - Pest test files

### Routing Structure
- Authentication routes: `/login`, `/register`, `/forgot-password`, etc.
- Settings routes: `/settings/profile`, `/settings/password`, `/settings/appearance`
- Main routes: `/` (Welcome), `/dashboard` (authenticated)

### Component Architecture
- UI components use Reka UI (headless components) with Tailwind styling
- Appearance system supports light/dark mode via `useAppearance` composable
- Components are organized by feature (auth, settings) and include proper TypeScript typing

### Testing
- Uses Pest PHP for backend testing
- Feature tests cover authentication flows and settings
- Tests use SQLite in-memory database for speed
- Frontend testing not currently configured

## Key Files to Understand
- `resources/js/app.ts` - Main frontend entry point
- `resources/js/composables/useAppearance.ts` - Theme management
- `app/Http/Middleware/HandleInertiaRequests.php` - Inertia middleware for shared data
- `routes/web.php` - Main route definitions
- `vite.config.ts` - Build configuration
- `composer.json` - Contains helpful development scripts