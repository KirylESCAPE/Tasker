FROM php:8.4-fpm-alpine

ARG UID=1000
ARG GID=1000

RUN apk add --no-cache \
    git curl libpng-dev libxml2-dev libzip-dev \
    oniguruma-dev openssl-dev \
    zip unzip mysql-client procps \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip

RUN addgroup -g ${GID} -S appgroup && \
    adduser -u ${UID} -G appgroup -S -D -h /var/www prouser

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www


COPY . /var/www

RUN chown -R prouser:appgroup /var/www/bootstrap/cache /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache /var/www/storage \
    && chown -R prouser:appgroup /var/www

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"


COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

USER prouser

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]   
