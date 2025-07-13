FROM php:8.2-alpine3.16

RUN apk update && apk add --no-cache \
    git curl libpng-dev libxml2-dev zip unzip oniguruma-dev

WORKDIR /var/www

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

CMD php artisan queue:work redis --sleep=3 --tries=3
