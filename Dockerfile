FROM dunglas/frankenphp:php8.4-alpine

LABEL maintainer="CharmOnTi"
LABEL description="CharmOnTi E-Commerce Gelang Custom"

WORKDIR /app

# ─── Install PHP Extensions & System Dependencies ───────────────────────────
RUN install-php-extensions \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
    && apk add --no-cache \
        unzip \
        git \
        curl \
        nodejs \
        npm \
        postgresql-client

# ─── Copy Composer from official image (multi-stage trick) ──────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ─── Copy application source code ───────────────────────────────────────────
# .dockerignore akan exclude: vendor/, node_modules/, .env, .git/, dll.
COPY . /app

# ─── Buat direktori yang dibutuhkan Laravel SEBELUM composer install ─────────
# bootstrap/cache dibutuhkan oleh "php artisan package:discover" (post-install)
# storage/* dibutuhkan oleh Laravel untuk logs, cache, sessions, views
RUN mkdir -p \
        /app/bootstrap/cache \
        /app/storage/framework/cache/data \
        /app/storage/framework/sessions \
        /app/storage/framework/views \
        /app/storage/framework/testing \
        /app/storage/logs \
        /app/storage/app/public \
    && chmod -R 775 /app/bootstrap/cache /app/storage

# ─── Install PHP Dependencies ───────────────────────────────────────────────
RUN composer install \
        --no-dev \
        --optimize-autoloader \
        --no-interaction \
        --prefer-dist

# ─── Install Node Dependencies & Build Frontend Assets ──────────────────────
RUN npm ci \
    && npm run build \
    && rm -rf node_modules

# ─── Copy custom PHP config ─────────────────────────────────────────────────
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# ─── Set Permissions ────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# ─── Entrypoint Script ──────────────────────────────────────────────────────
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile", "--adapter", "caddyfile"]
