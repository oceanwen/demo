FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

#COPY .env.example .env

COPY ./docker/nginx.conf /etc/nginx/nginx.conf

COPY ./docker/supervisord.conf /etc/supervisord.conf

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && chmod -R 777 storage bootstrap/cache

#RUN php artisan key:generate

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]