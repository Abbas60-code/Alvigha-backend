FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    a2dismod mpm_event || true; \
    a2dismod mpm_worker || true; \
    a2dismod mpm_prefork || true; \
    a2enmod mpm_prefork rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

CMD bash -lc 'set -eux; \
    rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf || true; \
    rm -f /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf || true; \
    rm -f /etc/apache2/mods-enabled/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.conf || true; \
    a2dismod mpm_event || true; \
    a2dismod mpm_worker || true; \
    a2dismod mpm_prefork || true; \
    a2enmod mpm_prefork rewrite; \
    apache2ctl -M | grep mpm || true; \
    sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf; \
    php artisan config:clear || true; \
    php artisan migrate --force || true; \
    apache2-foreground'