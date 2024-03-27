#!/bin/bash

echo "Loading package lists from package-list.sh..."

# PHP Packages
phpPackages=(
    "pusher/pusher-php-server"
    "livewire/livewire"
    "laravel/ui"
    "spatie/laravel-medialibrary"
    "spatie/laravel-activitylog"
    "spatie/laravel-permission"
    "spatie/laravel-backup"
    "maatwebsite/excel"
)

# Development only PHP Packages
devPhpPackages=(
    "barryvdh/laravel-debugbar"
)

# Node Packages
nodePackages=(
    "bootstrap-icons"
    "alpinejs"
    "sass"
    "laravel-echo"
    "pusher-js"
)

# Application setup commands
setupCommands=(
    "php artisan ui bootstrap"
    "npm install"
    "php artisan optimize:clear"
    "php artisan migrate:fresh --seed"
    "php artisan panel:seed Authy"
    "php artisan panel:seed Panel"
    "php artisan storage:link"
    "php artisan backup:run"
    "php artisan activitylog:clean"
    "php artisan serve"
)
