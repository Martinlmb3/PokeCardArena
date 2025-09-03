# Stage 1 - Build Frontend (Vite)
FROM node:18 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + PHP + Nginx)
FROM php:8.2-fpm AS backend

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    libpng-dev libxml2-dev nginx supervisor \
    libfreetype6-dev libjpeg62-turbo-dev default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd xml \
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
        php artisan key:generate\n\
    fi\n\
    \n\
    # Check if we are using MySQL and wait for it to be ready\n\
    if [ "${DB_CONNECTION}" = "mysql" ] && [ -n "${DB_HOST}" ] && [ -n "${DB_USERNAME}" ]; then\n\
        echo "Waiting for MySQL connection..."\n\
        MYSQL_PWD="${DB_PASSWORD}" mysql -h"${DB_HOST}" -P"${DB_PORT:-3306}" -u"${DB_USERNAME}" -e "SELECT 1" >/dev/null 2>&1\n\
        MYSQL_STATUS=$?\n\
        RETRY_COUNT=0\n\
        MAX_RETRIES=30\n\
        \n\
        while [ $MYSQL_STATUS -ne 0 ] && [ $RETRY_COUNT -lt $MAX_RETRIES ]; do\n\
            echo "MySQL is unavailable (attempt $((RETRY_COUNT + 1))/$MAX_RETRIES) - sleeping"\n\
            sleep 5\n\
            MYSQL_PWD="${DB_PASSWORD}" mysql -h"${DB_HOST}" -P"${DB_PORT:-3306}" -u"${DB_USERNAME}" -e "SELECT 1" >/dev/null 2>&1\n\
            MYSQL_STATUS=$?\n\
            RETRY_COUNT=$((RETRY_COUNT + 1))\n\
        done\n\
        \n\
        if [ $MYSQL_STATUS -eq 0 ]; then\n\
            echo "MySQL is ready!"\n\
        else\n\
            echo "Warning: Could not connect to MySQL after $MAX_RETRIES attempts. Continuing anyway..."\n\
        fi\n\
    else\n\
        echo "Using non-MySQL database or missing MySQL credentials. Skipping MySQL connection check."\n\
    fi\n\
    \n\
    # Run migrations\n\
    php artisan migrate --force\n\
    \n\
    # Cache configuration\n\
    php artisan config:cache\n\
    php artisan route:cache\n\
    php artisan view:cache\n\
    \n\
    # Start supervisor\n\
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
