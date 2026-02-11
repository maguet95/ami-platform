# =============================================================================
# AMI Platform — Production Dockerfile (Render)
# =============================================================================
# Multi-stage: Node (build assets) → PHP 8.3-FPM + Nginx
# =============================================================================

# ─── Stage 1: Build frontend assets ────────────────────────────────────────
FROM node:20-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/
RUN npm run build

# ─── Stage 2: PHP application ──────────────────────────────────────────────
FROM php:8.3-fpm-bookworm

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libicu-dev \
    libxml2-dev \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        zip \
        gd \
        mbstring \
        bcmath \
        intl \
        opcache \
        pcntl \
        xml \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy application code
COPY . .

# Copy built assets from Stage 1
COPY --from=assets /app/public/build public/build

# Run post-install scripts
RUN composer dump-autoload --optimize

# Copy deploy configs
COPY deploy/nginx-render.conf /etc/nginx/sites-available/default
COPY deploy/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY deploy/php.ini /usr/local/etc/php/conf.d/99-production.ini
COPY deploy/php-fpm.conf /usr/local/etc/php-fpm.d/zz-render.conf

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create log directories
RUN mkdir -p /var/log/supervisor /var/log/nginx

# Copy entrypoint
COPY deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/entrypoint.sh"]
