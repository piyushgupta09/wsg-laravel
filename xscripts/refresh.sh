#!/bin/bash

# Clearing Laravel caches and optimizing autoload
commands=(
    "php artisan config:clear"
    "php artisan cache:clear"
    "php artisan route:clear"
    "php artisan view:clear"
    "composer dump-autoload"
    "php artisan optimize:clear"
)

# Running initial setup commands
echo "Running initial setup commands..."
for command in "${commands[@]}"; do
    echo "Executing: $command"
    eval "$command" || {
        echo "Failed to execute: $command"
        exit 1
    }
done

# Migrating and seeding - adjust the order as necessary for your module dependencies
migrateAndSeedCommands=(
    # Fresh migration and seeding
    # "php artisan db:clean myalt"
    "php artisan migrate:fresh --seed --force"
    # Follow up with module-specific seeders, if needed
    "php artisan panel:seed Authy"
    "php artisan panel:seed Panel"
    "php artisan panel:seed Prody"
    "php artisan panel:seed Shopy"
)

# Running migrations and seeds
echo "Running migrations and seeds..."
for command in "${migrateAndSeedCommands[@]}"; do
    echo "Executing: $command"
    eval "$command" || {
        echo "Failed to execute: $command"
        exit 1
    }
    sleep 1  # Adding a brief pause to ensure operations complete before moving to the next
done

echo "Database reset successfully."
