#!/bin/bash

# Enhanced deployment script for Render.com
# This addresses autoloader issues in production environments

echo "=== PokeCardArena Deployment Fix ==="

# 1. Ensure proper directory structure and permissions
echo "Step 1: Setting up directories and permissions..."
mkdir -p bootstrap/cache storage/logs storage/framework/cache storage/framework/sessions storage/framework/views
chmod -R 755 bootstrap/cache storage
chown -R www-data:www-data bootstrap/cache storage 2>/dev/null || echo "Note: Running without www-data (acceptable for containers)"

# 2. Clean slate - remove all caches
echo "Step 2: Clearing all caches..."
php artisan cache:clear 2>/dev/null || echo "Cache clear: OK"
php artisan config:clear 2>/dev/null || echo "Config clear: OK"
php artisan route:clear 2>/dev/null || echo "Route clear: OK"
php artisan view:clear 2>/dev/null || echo "View clear: OK"

# 3. Remove potentially corrupted cache files
echo "Step 3: Removing cache files..."
rm -f bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*

# 4. Force regenerate Composer autoloader
echo "Step 4: Rebuilding Composer autoloader..."
composer dump-autoload --optimize --no-dev --classmap-authoritative

# 5. Verify class can be loaded
echo "Step 5: Testing class loading..."
php -r "
require_once 'vendor/autoload.php';
echo 'Testing class loading...' . PHP_EOL;
if (class_exists('App\\Models\\Pokemaster')) {
    echo '✓ Pokemaster class found' . PHP_EOL;
} else {
    echo '✗ Pokemaster class NOT FOUND' . PHP_EOL;
    echo 'Available classes in App\\Models:' . PHP_EOL;
    \$classes = get_declared_classes();
    foreach (\$classes as \$class) {
        if (strpos(\$class, 'App\\Models') === 0) {
            echo '  - ' . \$class . PHP_EOL;
        }
    }
}
"

# 6. Regenerate Laravel caches
echo "Step 6: Regenerating Laravel caches..."
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache

# 7. Final verification
echo "Step 7: Final verification..."
php artisan tinker --execute="echo 'Auth model: ' . auth()->getProvider()->getModel() . PHP_EOL;"

echo "=== Deployment Complete ==="
