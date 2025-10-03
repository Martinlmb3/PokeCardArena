# PokeCardArena Deployment Guide for Render.com

## The Issue
The application was getting "Class \App\Models\Pokemaster not found" errors in production, despite working locally.

## Root Cause
The issue was related to autoloader optimization and caching in production environments. Laravel's authentication system couldn't find the `Pokemaster` model class.

## Solution

### 1. Updated composer.json
Added explicit classmap entry for the Models directory:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "classmap": [
        "app/Models"
    ]
},
```

### 2. Build Command for Render
Use this as your build command in Render.com settings:

```bash
./build.sh
```

Or if you prefer inline commands:

```bash
composer install --optimize-autoloader --no-dev && mkdir -p bootstrap/cache storage/framework/{cache,sessions,views} && chmod -R 755 storage bootstrap/cache && composer dump-autoload --optimize --classmap-authoritative --no-dev && php artisan migrate --force && php artisan package:discover --ansi && php artisan config:cache && php artisan route:cache
```

### 3. Environment Variables Required
Make sure these are set in your Render environment:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY=` (generate with `php artisan key:generate`)
- Database connection variables
- Any other app-specific variables

### 4. Verification
After deployment, you can verify the fix by checking the logs for:

```
✅ Pokemaster class loaded successfully
Authentication provider model: App\Models\Pokemaster
```

## Files Added for Deployment

1. **build.sh** - Main build script for Render
2. **verify-bootstrap.php** - Standalone verification script
3. **render-deploy.sh** - Alternative deployment script
4. **deploy-fix.sh** - Quick fix script for existing deployments

## Troubleshooting

If you still get the error:

1. Check that `app/Models/Pokemaster.php` exists in your repository
2. Verify the namespace is exactly `App\Models`
3. Ensure the class name is exactly `Pokemaster`
4. Run the verification script: `php verify-bootstrap.php`
5. Check that your auth.php config points to `App\Models\Pokemaster::class`

## Key Changes Made

1. ✅ Added explicit classmap to composer.json
2. ✅ Used `--classmap-authoritative` flag for production autoloader
3. ✅ Ensured proper cache directory permissions
4. ✅ Added verification steps to build process
5. ✅ Created comprehensive build script for Render

The deployment should now work correctly with the Pokemaster authentication model.
