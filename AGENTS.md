# AGENTS.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

AMI (Alpha Markets Institute) is a trading education platform built with Laravel 12 using the TALL stack (Tailwind CSS, Alpine.js, Livewire, Laravel). It provides educational courses with memberships and an optional Trading Journal module.

## Tech Stack

- **Backend:** Laravel 12 / PHP 8.2+
- **Frontend:** Blade + Livewire 4 + Alpine.js + Tailwind CSS 4
- **Admin Panel:** Filament 5
- **Database:** PostgreSQL 16
- **Cache/Sessions/Queues:** Redis 7
- **Payments:** Stripe via Laravel Cashier
- **Build Tool:** Vite 7
- **Permissions:** spatie/laravel-permission

## Commands

### Development

```bash
# Start all dev servers (Laravel, queue, logs, Vite)
composer dev

# Setup project from scratch
composer setup
```

### Testing

```bash
# Run all tests
composer test

# Run a single test file
php artisan test tests/Feature/ExampleTest.php

# Run tests with filter
php artisan test --filter=testName
```

### Linting & Analysis

```bash
# Format PHP code with Pint
composer lint

# Check formatting without applying
composer lint:check

# Run PHPStan static analysis
composer analyse

# Run all checks (lint + analyse + test)
composer check
```

### Database

```bash
php artisan migrate
php artisan migrate:fresh --seed
```

### Artisan

```bash
php artisan serve
php artisan queue:listen
php artisan tinker
```

## Architecture

### Modular Structure

The application follows a modular monolith pattern with domain separation in `app/Modules/`:

- **Public/** — Public institutional website
- **Education/** — LMS core (courses, modules, lessons)
- **Payments/** — Stripe subscriptions and memberships
- **Journal/** — Trading Journal (optional premium module, read-only)

### Key Models

Located in `app/Models/`:
- `User` — Uses Cashier for subscriptions and spatie permissions
- `Course` → `Module` → `Lesson` — Hierarchical educational content
- `Enrollment` — User course enrollments
- `LessonProgress` — Tracks lesson completion
- `Plan` — Subscription plans

### Filament Admin

Two admin panels in `app/Filament/`:
- **Admin Panel** (`/admin`) — Full platform management
- **Instructor Panel** — Course content management

Resources: `Courses`, `Modules`, `Lessons`, `Enrollments`, `Plans`, `Users`

### External Workers

Python workers run on a separate VPS for batch processing (trade imports, stats analysis). They communicate with Laravel via authenticated internal API endpoints. **Workers never access the database directly** — they always go through Laravel's API.

## Database

Uses PostgreSQL. Tests use a separate `ami_platform_test` database (configured in phpunit.xml).

## Code Style

- **PHP:** Laravel preset via Pint (`pint.json`)
- **PHPStan:** Level 5 with Larastan (`phpstan.neon`)

## Key Patterns

- Livewire components for reactive UI without custom JavaScript
- Filament for all admin CRUD operations
- Laravel Cashier handles Stripe webhooks and subscription logic
- Roles/permissions via spatie/laravel-permission (admin, instructor, student)
- Sessions and cache stored in Redis, not filesystem
