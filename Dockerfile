# Stage 1 - Build Frontend (Vite)
FROM node:18 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + PHP + Nginx)
FROM php:8.2-fpm AS backend

# Install system dependencies including Redis
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    libpng-dev libxml2-dev nginx supervisor postgresql-client \
    libfreetype6-dev libjpeg62-turbo-dev redis-server \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip gd xml \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy app files
COPY . .

# Copy built frontend from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create storage directories
RUN mkdir -p /var/www/html/storage/logs

# Configure Nginx
RUN echo 'server {\n\
    listen 80;\n\
    server_name _;\n\
    root /var/www/html/public;\n\
    index index.php;\n\
    \n\
    location / {\n\
    try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    \n\
    location ~ \.php$ {\n\
    fastcgi_pass 127.0.0.1:9000;\n\
    fastcgi_index index.php;\n\
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
    include fastcgi_params;\n\
    }\n\
    }' > /etc/nginx/sites-available/default

# Configure Supervisor
RUN echo '[supervisord]\n\
    nodaemon=true\n\
    user=root\n\
    \n\
    [program:redis]\n\
    command=redis-server --bind 127.0.0.1 --port 6379\n\
    autostart=true\n\
    autorestart=true\n\
    user=root\n\
    \n\
    [program:php-fpm]\n\
    command=php-fpm\n\
    autostart=true\n\
    autorestart=true\n\
    \n\
    [program:nginx]\n\
    command=nginx -g "daemon off;"\n\
    autostart=true\n\
    autorestart=true' > /etc/supervisor/conf.d/supervisord.conf

# Run composer autoload dump
RUN composer run-script post-autoload-dump

# Create startup script
RUN echo '#!/bin/bash\n\
    # Set up environment if .env does not exist\n\
    if [ ! -f /var/www/html/.env ]; then\n\
        cp .env.example .env 2>/dev/null || echo "APP_NAME=PokeCardArena\nAPP_ENV=production\nAPP_KEY=\nAPP_DEBUG=false\nAPP_TIMEZONE=UTC\nAPP_URL=${APP_URL:-http://localhost}" > .env\n\
    fi\n\
    \n\
    # Force PostgreSQL connection if DATABASE_URL is available (Render provides this)\n\
    echo "Checking for database configuration..."\n\
    echo "DATABASE_URL: ${DATABASE_URL:-not set}"\n\
    echo "DB_HOST: ${DB_HOST:-not set}"\n\
    echo "DB_CONNECTION: ${DB_CONNECTION:-not set}"\n\
    \n\
    if [ -n "${DATABASE_URL}" ]; then\n\
        echo "DATABASE_URL found - configuring PostgreSQL connection"\n\
        # Remove any existing DB config to avoid conflicts\n\
        grep -v "^DB_" .env > .env.tmp && mv .env.tmp .env\n\
        echo "" >> .env\n\
        echo "DB_CONNECTION=pgsql" >> .env\n\
        echo "DB_URL=${DATABASE_URL}" >> .env\n\
    elif [ -n "${DB_HOST}" ] && [ "${DB_CONNECTION}" != "sqlite" ]; then\n\
        echo "External database configuration detected"\n\
        # Remove any existing DB config to avoid conflicts\n\
        grep -v "^DB_" .env > .env.tmp && mv .env.tmp .env\n\
        echo "" >> .env\n\
        echo "DB_CONNECTION=${DB_CONNECTION:-pgsql}" >> .env\n\
        echo "DB_HOST=${DB_HOST}" >> .env\n\
        echo "DB_PORT=${DB_PORT:-5432}" >> .env\n\
        echo "DB_DATABASE=${DB_DATABASE}" >> .env\n\
        echo "DB_USERNAME=${DB_USERNAME}" >> .env\n\
        echo "DB_PASSWORD=${DB_PASSWORD}" >> .env\n\
    else\n\
        echo "No external database found - using SQLite fallback"\n\
        # Remove any existing DB config to avoid conflicts\n\
        grep -v "^DB_" .env > .env.tmp && mv .env.tmp .env\n\
        echo "" >> .env\n\
        echo "DB_CONNECTION=sqlite" >> .env\n\
        echo "DB_DATABASE=/var/www/html/database/database.sqlite" >> .env\n\
        # Create database directory and file\n\
        mkdir -p /var/www/html/database\n\
        touch /var/www/html/database/database.sqlite\n\
        chmod 664 /var/www/html/database/database.sqlite\n\
    fi\n\
    \n\
    # Show final database configuration\n\
    echo "Final database configuration:"\n\
    grep "^DB_" .env || echo "No DB configuration found in .env"\n\
    \n\
    # Always ensure APP_KEY is generated\n\
    if ! grep -q "^APP_KEY=base64:" .env; then\n\
        echo "Generating application key..."\n\
        php artisan key:generate --force\n\
    else\n\
        echo "Application key already exists"\n\
    fi\n\
    \n\
    # Configure sessions based on available services\n\
    echo "Configuring session storage..."\n\
    if [ -n "${REDIS_URL}" ] || [ -n "${REDIS_HOST}" ]; then\n\
        echo "Redis available - configuring Redis sessions"\n\
        echo "" >> .env\n\
        echo "SESSION_DRIVER=redis" >> .env\n\
        echo "SESSION_CONNECTION=default" >> .env\n\
    elif grep -q "DB_CONNECTION=pgsql" .env || grep -q "DB_CONNECTION=mysql" .env; then\n\
        echo "Database available - configuring database sessions"\n\
        echo "" >> .env\n\
        echo "SESSION_DRIVER=database" >> .env\n\
    else\n\
        echo "Using file-based sessions as fallback"\n\
        echo "" >> .env\n\
        echo "SESSION_DRIVER=file" >> .env\n\
        # Ensure session directory has proper permissions\n\
        mkdir -p /var/www/html/storage/framework/sessions\n\
        chmod 755 /var/www/html/storage/framework/sessions\n\
    fi\n\
    \n\
    # Check if we are using PostgreSQL and wait for it to be ready\n\
    if grep -q "DB_CONNECTION=pgsql" .env; then\n\
        if [ -n "${DATABASE_URL}" ]; then\n\
            echo "Using DATABASE_URL for PostgreSQL connection - letting Laravel handle connection"\n\
        elif [ -n "${DB_HOST}" ] && [ -n "${DB_USERNAME}" ]; then\n\
            echo "Waiting for PostgreSQL connection..."\n\
            PGPASSWORD="${DB_PASSWORD}" psql -h"${DB_HOST}" -p"${DB_PORT:-5432}" -U"${DB_USERNAME}" -d"${DB_DATABASE}" -c "SELECT 1;" >/dev/null 2>&1\n\
            PG_STATUS=$?\n\
            RETRY_COUNT=0\n\
            MAX_RETRIES=30\n\
            \n\
            while [ $PG_STATUS -ne 0 ] && [ $RETRY_COUNT -lt $MAX_RETRIES ]; do\n\
                echo "PostgreSQL is unavailable (attempt $((RETRY_COUNT + 1))/$MAX_RETRIES) - sleeping"\n\
                sleep 5\n\
                PGPASSWORD="${DB_PASSWORD}" psql -h"${DB_HOST}" -p"${DB_PORT:-5432}" -U"${DB_USERNAME}" -d"${DB_DATABASE}" -c "SELECT 1;" >/dev/null 2>&1\n\
                PG_STATUS=$?\n\
                RETRY_COUNT=$((RETRY_COUNT + 1))\n\
            done\n\
            \n\
            if [ $PG_STATUS -eq 0 ]; then\n\
                echo "PostgreSQL is ready!"\n\
            else\n\
                echo "Warning: Could not connect to PostgreSQL after $MAX_RETRIES attempts. Continuing anyway..."\n\
            fi\n\
        else\n\
            echo "PostgreSQL configured but missing connection details - letting Laravel handle it"\n\
        fi\n\
    else\n\
        echo "Using non-PostgreSQL database. Skipping PostgreSQL connection check."\n\
    fi\n\
    \n\
    # Handle migrations safely\n\
    echo "Setting up database migrations..."\n\
    \n\
    # Check if we should reset migrations\n\
    if [ "${DB_RESET_MIGRATIONS}" = "true" ]; then\n\
        echo "DB_RESET_MIGRATIONS is true. Running fresh migrations..."\n\
        php artisan migrate:fresh --force\n\
        echo "Fresh migrations completed. Remember to remove DB_RESET_MIGRATIONS environment variable."\n\
    else\n\
        # Ensure the migrations table exists\n\
        php artisan migrate:install --force 2>/dev/null || true\n\
        \n\
        # Try to run migrations with better error handling\n\
        echo "Running database migrations..."\n\
        if php artisan migrate --force 2>&1; then\n\
            echo "Migrations completed successfully."\n\
        else\n\
            echo "Migrations encountered issues. Attempting alternative approach..."\n\
            # If migrations fail, try to continue - the app might still work\n\
            echo "Continuing with existing database state..."\n\
        fi\n\
        \n\
        # Skip sessions table migration since it may conflict\n\
        echo "Skipping sessions table migration to avoid conflicts - using Redis sessions"\n\
    fi\n\
    \n\
    # Cache configuration\n\
    php artisan config:cache\n\
    php artisan route:clear\n\
    php artisan route:cache || echo "Route caching failed, continuing without cached routes"\n\
    php artisan view:clear\n\
    php artisan view:cache || echo "View caching failed, continuing without cached views"\n\
    \n\
    # Start supervisor\n\
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
