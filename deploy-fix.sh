#!/bin/bash

# Deployment fix script for PokeCardArena
# This script fixes the common issues that cause the "Class Pokemaster not found" error

echo "Starting deployment fixes..."

# Fix permissions for cache directories
echo "Setting correct permissions..."
chmod -R 775 bootstrap/cache storage
chown -R www-data:www-data bootstrap/cache storage 2>/dev/null || echo "Note: Could not set www-data ownership (not critical in dev)"

# Clear all caches
echo "Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild autoloader
echo "Rebuilding autoloader..."
composer dump-autoload --optimize --no-dev

# Cache configurations for production
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache

echo "Deployment fixes completed!"
echo "Testing class loading..."
php -r "require 'vendor/autoload.php'; echo 'Pokemaster class: ' . (class_exists('App\\Models\\Pokemaster') ? 'FOUND' : 'NOT FOUND') . PHP_EOL;"
