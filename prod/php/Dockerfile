FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    curl git zip unzip libxml2-dev libpng-dev libjpeg-turbo-dev libwebp-dev \
    libzip-dev oniguruma-dev bash icu-dev imagemagick-dev pcre-dev supervisor \
    autoconf gcc g++ make

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN docker-php-ext-install pdo pdo_mysql zip mbstring exif intl pcntl xml gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

COPY ./prod/php/supervisord-app.conf /etc/supervisord.conf

EXPOSE 9000

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
