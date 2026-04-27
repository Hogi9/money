#!/bin/bash
set -e

WORKDIR=/var/www/laravel-money
cd $WORKDIR

if [ ! -f "artisan" ]; then
    echo "==> Creating new Laravel project..."
    composer create-project laravel/laravel /tmp/laravel-new --no-interaction --prefer-dist
    cp -a /tmp/laravel-new/. $WORKDIR/
    rm -rf /tmp/laravel-new

    cp .env.example .env
    sed -i "s/^APP_NAME=.*/APP_NAME=Money/" .env
    sed -i "s/^DB_HOST=.*/DB_HOST=${DB_HOST:-db-mysql}/" .env
    sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE:-money}/" .env
    sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME:-root}/" .env
    sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD:-}/" .env

    echo "==> Running key:generate..."
    php artisan key:generate --force

    echo "==> Running storage:link..."
    php artisan storage:link
fi

echo "==> Installing composer dependencies..."
composer install --no-interaction --optimize-autoloader

echo "==> Installing npm dependencies..."
npm install

echo "==> Building frontend assets..."
npm run build

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Changing ownership..."
chown -R 1000:1000 /var/www/laravel-money
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Starting php-fpm..."
exec php-fpm
