FROM php:8.2-alpine

WORKDIR /var/www

RUN apk update && apk add --no-cache \
    git \
    unzip \
    postgresql-dev \
    bash  \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

CMD bash -c "composer install"