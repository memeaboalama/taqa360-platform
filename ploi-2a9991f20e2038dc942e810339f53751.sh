#!/bin/bash

set -e
cd /home/ploi/app.eats.sa
git pull origin main
php artisan migrate:fresh --seed
# composer install 
# php artisan telescope:clear
# php artisan telescope:prune
# php artisan migrate --force
# php artisan cache:forget spatie.permission.cache 
# php artisan db:seed --force
# php artisan app:clear-cart-items-command
echo "" | sudo -S service php8.2-fpm reload
  # composer update --no-interaction --prefer-dist --optimize-autoloader
 
# echo "" | sudo -S service php8.2-fpm reload
# php artisan db:seed --force
# php artisan app:set-default-roles
# php artisan migrate:rollback --step=1
# php artisan cache:clear 
# php artisan route:clear
# php artisan config:clear
# php artisan view:clear
# php artisan app:clear-cart-items-command
echo "🚀 Application deployed!"