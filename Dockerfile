# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy application code
COPY . .

# Install Node dependencies and build frontend
COPY package.json package-lock.json* ./
RUN npm ci && npm run build && npm ci --omit=dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create necessary directories
RUN mkdir -p /var/www/html/storage/logs

# Enable Apache rewrite module
RUN a2enmod rewrite

# Configure Apache for Laravel
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    \n\
    <Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Options Indexes FollowSymLinks\n\
    Require all granted\n\
    </Directory>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
    </VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Startup script
RUN echo '#!/bin/bash\n\
    echo "Creating .env file from environment variables"\n\
    echo "APP_NAME=${APP_NAME:-PokeCardArena}" > .env\n\
    echo "APP_ENV=${APP_ENV:-production}" >> .env\n\
    echo "APP_KEY=${APP_KEY}" >> .env\n\
    echo "APP_DEBUG=${APP_DEBUG:-false}" >> .env\n\
    echo "APP_URL=${APP_URL}" >> .env\n\
    echo "APP_TIMEZONE=UTC" >> .env\n\
    echo "" >> .env\n\
    echo "DB_CONNECTION=pgsql" >> .env\n\
    echo "DB_HOST=${DB_HOST}" >> .env\n\
    echo "DB_PORT=${DB_PORT:-5432}" >> .env\n\
    echo "DB_DATABASE=${DB_DATABASE}" >> .env\n\
    echo "DB_USERNAME=${DB_USERNAME}" >> .env\n\
    echo "DB_PASSWORD=${DB_PASSWORD}" >> .env\n\
    echo "" >> .env\n\
    echo "SESSION_DRIVER=database" >> .env\n\
    echo "SESSION_LIFETIME=120" >> .env\n\
    echo "SESSION_ENCRYPT=false" >> .env\n\
    echo "" >> .env\n\
    echo "QUEUE_CONNECTION=database" >> .env\n\
    echo "CACHE_DRIVER=database" >> .env\n\
    echo "" >> .env\n\
    echo "LOG_CHANNEL=stack" >> .env\n\
    echo "LOG_LEVEL=error" >> .env\n\
    \n\
    # Fix permissions at runtime\n\
    chown -R www-data:www-data /var/www/html/storage\n\
    chown -R www-data:www-data /var/www/html/bootstrap/cache\n\
    chmod -R 775 /var/www/html/storage\n\
    chmod -R 775 /var/www/html/bootstrap/cache\n\
    \n\
    # Verify APP_KEY is set\n\
    if [ -z "${APP_KEY}" ]; then\n\
    echo "ERROR: APP_KEY environment variable is required"\n\
    exit 1\n\
    fi\n\
    \n\
    # Wait for database to be ready and run migrations\n\
    until php artisan migrate --force; do\n\
    echo "Retrying migrations in 5s..."\n\
    sleep 5\n\
    done\n\
    \n\
    # Cache config/routes\n\
    php artisan config:clear\n\
    php artisan config:cache\n\
    php artisan route:cache\n\
    \n\
    # Start Apache\n\
    apache2-foreground\n\
    ' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Expose port 80
EXPOSE 80

# Start with custom script
CMD ["/usr/local/bin/start.sh"]
