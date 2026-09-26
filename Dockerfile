# Self-contained demo image (used by render.yaml for the free Render demo; works on any Docker host).
# Apache + PHP 8.3, SQLite database, demo photos downloaded at build time so the container
# starts fast and needs no external database or disk. With DEMO_MODE=true the store is
# re-seeded on every start (see docker/start.sh).
FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd pdo_mysql zip opcache \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache: serve public/, allow Laravel's .htaccess, listen on $PORT (Render sets it; 80 locally).
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    PORT=80 \
    COMPOSER_ALLOW_SUPERUSER=1
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!Listen 80!Listen ${PORT}!' /etc/apache2/ports.conf \
    && sed -ri 's!<VirtualHost \*:80>!<VirtualHost *:${PORT}>!' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory ${APACHE_DOCUMENT_ROOT}>\n    AllowOverride All\n    Require all granted\n</Directory>\nServerName localhost\n' \
        > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

WORKDIR /var/www/html

# Dependencies first (cached layer), then the app.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --no-progress

COPY . .
RUN composer dump-autoload --optimize --no-dev --no-interaction \
    && mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views \
        storage/logs bootstrap/cache database

# Demo defaults (override in the host's environment settings).
ENV APP_NAME=Sistrella \
    APP_ENV=production \
    APP_DEBUG=false \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/var/www/html/database/demo.sqlite \
    SESSION_DRIVER=database \
    CACHE_STORE=database \
    QUEUE_CONNECTION=sync \
    FILESYSTEM_DISK=local \
    LOG_CHANNEL=stderr \
    MAIL_MAILER=log \
    DEMO_MODE=true \
    DEMO_ADMIN_READ_ONLY=true \
    SEED_ADMIN_PASSWORD=password
# ^ Public demo defaults: the login is shown on /admin/login and the admin panel
#   is read-only, so this password is intentionally public. Override any of them
#   in the host's environment settings (e.g. set DEMO_MODE=false for a real store).

# Seed once at build time only to download the free-licence demo photos into the image;
# the database itself is rebuilt when the container starts.
RUN touch database/demo.sqlite \
    && export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)" \
    && php artisan migrate --force --no-interaction \
    && php artisan db:seed --force --no-interaction \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 80
CMD ["sh", "/var/www/html/docker/start.sh"]
