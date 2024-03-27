#!/bin/bash

# Publish assets for Laravel packages

echo "Publishing assets for Laravel packages..."

# Laravel Livewire
echo "Publishing for Laravel Livewire..."
php artisan vendor:publish --provider="Livewire\LivewireServiceProvider"

# Spatie Media Library
echo "Publishing for Spatie Media Library..."
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-config"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"

# Spatie Activity Log
echo "Publishing for Spatie Activity Log..."
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"

# Spatie Permission
echo "Publishing for Spatie Permission..."
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Spatie Backup
echo "Publishing for Spatie Backup..."
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"

# Laravel Debugbar (Barryvdh)
echo "Publishing for Laravel Debugbar..."
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"

# Maatwebsite Excel
echo "Publishing for Maatwebsite Excel..."
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config

php artisan optimize:clear

echo "Asset publishing complete."
