
# Eats Backend project

## New Commands


- to generate a new application use this command

  - php artisan make:app {AppName} {namespace}
  - this command create application module 
  - application path app/application       
  - namespace Application
  - requests/providers/middlweare/resources/controllers

- to generate domain user this command

  - php artisan make:domain {domainName}
  - this command create domain 
  - namespace Domain
  - actions/models/repositories/events/migrations

- to generate a new repository use this command

  - php artisan make:repo {repoName} {domainName}
  - repo path will be app/DomainName/Repositories/repoName
  

 "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi",
            "@php artisan vendor:publish --force --tag=livewire:assets --ansi"
        ],

composer install  --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-posix --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip
composer reinstall * --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-posix --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip



icacls ./storage/framework /grant:r username:(RX,W)

## make user as admin 
```ps
takeown /f ./storage/framework /a
takeown /f D:\EatsProject\App\storage\framework /a
takeown /f D:\EatsProject\App\bootstrap\cache /a
```
php artisan config:clear php artisan view:clear


cd storage/ && mkdir -p framework/{sessions,views,cache} && chmod -R 775 framework
```bash
composer install  --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-posix --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip
takeown /f D:\EatsProject\abc\storage /a
takeown /f D:\EatsProject\abc\bootstrap /a
takeown /f D:\EatsProject\abc\vendor /a
mkdir -p bootstrap/cache/
mkdir -p storage/framework/sessions/
mkdir -p storage/framework/views/
mkdir -p storage/framework/cache/
takeown /f D:\EatsProject\abc\storage\framework /a
takeown /f D:\EatsProject\abc\bootstrap\cache /a

composer install --optimize-autoloader --ignore-platform-reqs
takeown /f D:\EatsProject\backend\storage /a
takeown /f D:\EatsProject\backend\bootstrap /a
takeown /f D:\EatsProject\backend\vendor /a
mkdir -p bootstrap/cache/
mkdir -p storage/framework/sessions/
mkdir -p storage/framework/views/
mkdir -p storage/framework/cache/
takeown /f D:\EatsProject\backend\storage\framework /a
takeown /f D:\EatsProject\backend\bootstrap\cache /a


php artisan key:generate
php artisan storage:link
php artisan cache:clear
php artisan config:clear
php artisan view:clear
composer install --optimize-autoloader --ignore-platform-reqs
npm i
php artisan migrate
```

```bash
php artisan migrate
 php artisan clear-compiled
composer install --no-scripts
cd bootstrap/cache/
composer dump-autoload
php composer.phar install
php artisan package:discover --ansi
test
```


```sql
alter table `merchant_orders` add `driver_id` bigint unsigned null after `employee_id`, add `has_assign_driver` tinyint(1) not null default '0' after `driver_id`, add `assign_driver_id` bigint unsigned null after `has_assign_driver`)
```
