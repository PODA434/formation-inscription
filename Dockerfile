# Image de déploiement pour Render (ou tout hébergeur Docker).
# PHP seul en serveur (artisan serve) : suffisant pour un petit site, pas besoin de Nginx séparé.
FROM php:8.3-cli-alpine

# Dépendances système + extensions PHP nécessaires à Laravel + PostgreSQL
RUN apk add --no-cache \
        postgresql-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        pgsql \
        mbstring \
        bcmath \
        zip \
        intl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Installe d'abord les dépendances (mise en cache Docker si le code change sans que composer.json change)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --no-progress

# Puis copie le reste du code
COPY . .

RUN composer dump-autoload --optimize \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000
CMD ["/usr/local/bin/start.sh"]
