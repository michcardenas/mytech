# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 e-commerce application with features including:
- Shopping cart functionality (anayarojo/shoppingcart package)
- User roles and permissions (spatie/laravel-permission)
- Square payment integration (Square Connect & Square APIs)
- Full-stack development with Laravel Breeze authentication
- Asset compilation with Vite and TailwindCSS

## Development Commands

### Starting Development Environment
```bash
composer run dev
```
This runs a comprehensive development environment with:
- PHP development server (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Log monitoring (`php artisan pail`)
- Vite dev server for frontend assets (`npm run dev`)

### Individual Services
```bash
# Backend only
php artisan serve

# Frontend assets (development)
npm run dev

# Frontend assets (production build)
npm run build

# Queue worker
php artisan queue:listen --tries=1

# Real-time logs
php artisan pail --timeout=0
```

### Testing
```bash
# Run all tests
composer run test
# Or directly:
php artisan test

# Clear config before testing
php artisan config:clear
```

### Code Quality
```bash
# Laravel Pint (code formatting)
./vendor/bin/pint

# PHPUnit tests
./vendor/bin/phpunit
```

## Architecture

### Backend Structure
- **Models**: Located in `app/Models/` - Eloquent models for database entities
- **Controllers**: Located in `app/Http/Controllers/` - HTTP request handling
- **Routes**:
  - `routes/web.php` - Web routes (heavily customized with 200+ lines of routes)
  - `routes/auth.php` - Authentication routes (Laravel Breeze)
- **Database**: Uses migrations in `database/migrations/`
- **Permissions**: Implements role-based permissions via Spatie package

### Frontend Structure
- **Views**: Blade templates in `resources/views/`
- **Assets**:
  - CSS in `resources/css/`
  - JavaScript in `resources/js/`
- **Build Tool**: Vite with Laravel plugin
- **Styling**: TailwindCSS with forms plugin and Alpine.js

### Key Dependencies
- **E-commerce**: anayarojo/shoppingcart for cart functionality
- **Payments**: Square Connect API integration
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **Frontend**: Alpine.js, TailwindCSS, Vite

### Configuration
- Uses `.env` file for environment configuration
- TailwindCSS configuration in `tailwind.config.js`
- Vite configuration in `vite.config.js`
- Editor configuration in `.editorconfig`

## Database

The application uses SQLite by default (based on composer.json post-create scripts). Database file location: `database/database.sqlite`

### Migrations
```bash
# Run migrations
php artisan migrate

# Fresh migration with seeders
php artisan migrate:fresh --seed
```