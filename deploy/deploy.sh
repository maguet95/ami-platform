#!/bin/bash
# =============================================================================
# AMI Platform — Production Deploy Script
# =============================================================================
# Usage: bash deploy/deploy.sh
# Run from the project root directory
# =============================================================================

set -e

APP_DIR="/var/www/ami"
BRANCH="main"

echo "========================================="
echo "  AMI Platform — Deploy"
echo "========================================="

cd "$APP_DIR"

# ─── 1. Maintenance Mode ─────────────────────────────────────────────────
echo "[1/9] Activando modo mantenimiento..."
php artisan down --retry=60

# ─── 2. Pull Latest Code ─────────────────────────────────────────────────
echo "[2/9] Descargando codigo..."
git pull origin "$BRANCH"

# ─── 3. Install Dependencies ─────────────────────────────────────────────
echo "[3/9] Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "[4/9] Instalando dependencias Node..."
npm ci --production=false

# ─── 4. Build Assets ─────────────────────────────────────────────────────
echo "[5/9] Compilando assets..."
npm run build

# ─── 5. Run Migrations ───────────────────────────────────────────────────
echo "[6/9] Ejecutando migraciones..."
php artisan migrate --force

# ─── 6. Laravel Optimizations ────────────────────────────────────────────
echo "[7/9] Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan icons:cache

# ─── 7. Restart Queue Workers ────────────────────────────────────────────
echo "[8/9] Reiniciando queue workers..."
php artisan queue:restart

# ─── 8. Disable Maintenance Mode ─────────────────────────────────────────
echo "[9/9] Desactivando modo mantenimiento..."
php artisan up

echo ""
echo "========================================="
echo "  Deploy completado exitosamente!"
echo "========================================="
echo ""
