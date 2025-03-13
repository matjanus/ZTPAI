FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y libpq-dev


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

CMD php -S 0.0.0.0:8000 -t public