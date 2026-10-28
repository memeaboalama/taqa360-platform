#!/bin/sh
set -e
php-fpm --nodaemonize
composer install --optimize-autoloader --ignore-platform-reqs
chmod -R 777 ./storage
chmod -R 777 ./bootstrap/cache
chmod -R 777 ./vendor
php artisan key:generate
php artisan storage:link
service cron start --nodaemonize
service supervisor start
cp /var/www/public/assets/wkhtmltopdf-arm64 /var/www/storage/app/public/fonts/
exec "$@"
