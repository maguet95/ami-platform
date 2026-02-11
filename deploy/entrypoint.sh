#!/bin/bash

echo "========================================="
echo "  AMI Platform — Container Starting"
echo "========================================="

cd /var/www/html

# ─── Debug: Show DB config ──────────────────────────────────────────────
echo "[0/5] Checking environment..."
echo "  DB_CONNECTION=${DB_CONNECTION:-not set (will default to sqlite!)}"
echo "  DATABASE_URL=${DATABASE_URL:+SET (hidden)}"
echo "  REDIS_URL=${REDIS_URL:+SET (hidden)}"
echo "  APP_ENV=${APP_ENV:-not set}"

if [ -z "$DB_CONNECTION" ]; then
    echo "  WARNING: DB_CONNECTION not set! Setting to pgsql..."
    export DB_CONNECTION=pgsql
fi

# ─── Run Migrations ─────────────────────────────────────────────────────
echo "[1/5] Running migrations..."
if ! php artisan migrate --force; then
    echo "  WARNING: Migrations had errors. Continuing anyway..."
fi

# ─── Cache Configuration ────────────────────────────────────────────────
echo "[2/5] Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan icons:cache 2>/dev/null || true

# ─── Storage Symlink ────────────────────────────────────────────────────
echo "[3/5] Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# ─── Fix Permissions ────────────────────────────────────────────────────
echo "[4/5] Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache

# ─── Start Supervisord ──────────────────────────────────────────────────
echo "[5/5] Starting services..."
echo "========================================="
echo "  AMI Platform — Ready!"
echo "========================================="

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
