FROM php:8.4-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache \
    bash \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 复制 Laravel 项目文件
COPY . .

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

COPY ./nginx.conf /etc/nginx/nginx.conf

COPY ./supervisord.conf /etc/supervisord.conf

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]