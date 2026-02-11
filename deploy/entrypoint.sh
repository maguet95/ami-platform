#!/bin/bash
set -e

echo "========================================="
echo "  AMI Platform — Container Starting"
echo "========================================="

cd /var/www/html

# ─── Run Migrations ─────────────────────────────────────────────────────
echo "[1/5] Running migrations..."
php artisan migrate --force

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
