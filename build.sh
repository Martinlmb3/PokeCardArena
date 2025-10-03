#!/bin/bash

# Render.com Build Script for PokeCardArena
# Place this file as 'build.sh' in your project root
# Set this as your build command in Render

set -e  # Exit on any error

echo "🚀 Starting PokeCardArena build process..."

# 1. Install dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# 2. Ensure cache directories exist with proper permissions
echo "📁 Setting up cache directories..."
mkdir -p bootstrap/cache
mkdir -p storage/logs
mkdir -p storage/framework/{cache,sessions,views}
chmod -R 755 storage bootstrap/cache

# 3. Clear any existing caches
echo "🧹 Clearing existing caches..."
php artisan cache:clear --no-interaction || true
php artisan config:clear --no-interaction || true
php artisan route:clear --no-interaction || true
php artisan view:clear --no-interaction || true

# 4. Generate optimized autoloader with authoritative classmap
echo "🔧 Generating optimized autoloader..."
composer dump-autoload --optimize --classmap-authoritative --no-dev

# 5. Verify class loading
echo "🔍 Verifying class loading..."
php -r "
require_once 'vendor/autoload.php';
if (class_exists('App\\Models\\Pokemaster')) {
    echo '✅ Pokemaster class loaded successfully' . PHP_EOL;
} else {
    echo '❌ ERROR: Pokemaster class not found!' . PHP_EOL;
    exit(1);
}
"

# 6. Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction

# 7. Package discovery and caching
echo "🔄 Discovering packages..."
php artisan package:discover --ansi

echo "💾 Caching configurations..."
php artisan config:cache
php artisan route:cache

# 8. Build frontend assets (if needed)
if [ -f "package.json" ]; then
    echo "🎨 Building frontend assets..."
    npm ci --only=production
    npm run build
fi

# 9. Final verification
echo "✅ Final verification..."
php artisan tinker --execute="
echo 'Authentication provider model: ' . auth()->getProvider()->getModel() . PHP_EOL;
echo 'Pokemaster class exists: ' . (class_exists('App\\Models\\Pokemaster') ? 'YES' : 'NO') . PHP_EOL;
"

echo "🎉 Build completed successfully!"
