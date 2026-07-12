#!/bin/sh
set -e

cd /var/www

composer install --no-interaction --no-scripts

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$APP_KEY" ] || ! grep -q "^APP_KEY=" .env; then   
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan config:clear
php artisan cache:clear

exec "$@"
