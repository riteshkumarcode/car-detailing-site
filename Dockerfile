# ==============================================================================
# Production Dockerfile for Laravel Backend (The Drive Clinic)
# Supports: Filament Admin, Staff Board, Livewire, Invoices, PDF Generation
# ==============================================================================

FROM php:8.2-cli-alpine

# Install system dependencies & libraries for GD, SQLite, ZIP, etc.
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    sqlite-dev \
    icu-dev \
    oniguruma-dev \
    nodejs \
    npm

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_sqlite \
        pdo_mysql \
        gd \
        zip \
        intl \
        bcmath \
        opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy dependency manifests first for Docker layer caching
COPY composer.json composer.lock package.json package-lock.json ./

# Install PHP and Node dependencies
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist
RUN npm ci

# Copy application source code
COPY . .

# Complete composer scripts and build frontend assets
RUN composer dump-autoload --optimize --no-dev
RUN npm run build

# Set permissions for Laravel storage and cache
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expose web port (Railway, Render, Fly.io, Cloud Run bind to PORT)
EXPOSE 8080
ENV PORT=8080

# Production startup script
CMD sh -c "touch database/database.sqlite && php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=\${PORT:-8080}"
