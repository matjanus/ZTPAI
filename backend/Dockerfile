FROM php:8.2-cli

WORKDIR /var/www

RUN apk update && apk add --no-cache \
    git \
    unzip \
    postgresql-dev \
    bash  \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

CMD bash -c "composer install && php -S 0.0.0.0:8000 -t public"