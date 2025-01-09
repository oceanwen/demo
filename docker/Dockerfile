FROM oceanwen/php:8.4

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    nginx \
    supervisor

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

#COPY .env.example .env

COPY ./docker/nginx.conf /etc/nginx/nginx.conf

COPY ./docker/supervisord.conf /etc/supervisord.conf

COPY ./docker/custom-php.ini /usr/local/etc/php/conf.d/

COPY ./docker/custom-php-fpm.conf /usr/local/etc/php-fpm.d/

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && chmod -R 777 storage bootstrap/cache

#RUN php artisan key:generate

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]