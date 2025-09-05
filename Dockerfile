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
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy application code
COPY . .

# Run composer scripts now that artisan exists
RUN composer run-script post-autoload-dump

# Copy package.json and install Node dependencies (including dev for build)
COPY package.json package-lock.json* ./
RUN npm ci

# Build frontend assets
RUN npm run build

# Clean up dev dependencies to reduce image size
RUN npm ci --omit=dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create necessary directories
RUN mkdir -p /var/www/html/storage/logs

# Enable Apache rewrite module
RUN a2enmod rewrite

# Configure Apache
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    \n\
    <Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
    </VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Create a startup script for runtime setup
RUN echo '#!/bin/bash\n\
    if [ ! -f .env ]; then\n\
    echo "Creating .env file from environment variables"\n\
    echo "APP_NAME=${APP_NAME:-PokeCardArena}" > .env\n\
    echo "APP_ENV=${APP_ENV:-production}" >> .env\n\
    echo "APP_KEY=" >> .env\n\
    echo "APP_DEBUG=${APP_DEBUG:-false}" >> .env\n\
    echo "APP_URL=${APP_URL}" >> .env\n\
    echo "" >> .env\n\
    echo "DB_CONNECTION=${DB_CONNECTION:-pgsql}" >> .env\n\
    echo "DB_URL=${DATABASE_URL}" >> .env\n\
    fi\n\
    \n\
    # Generate app key if not set\n\
    if ! grep -q "APP_KEY=base64:" .env; then\n\
    php artisan key:generate --force\n\
    fi\n\
    \n\
    # Run migrations\n\
    php artisan migrate --force\n\
    \n\
    # Cache config\n\
    php artisan config:cache\n\
    php artisan route:cache\n\
    php artisan view:cache\n\
    \n\
    # Start Apache\n\
    apache2-foreground\n\
    ' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Expose port 80
EXPOSE 80

# Start with our custom script
CMD ["/usr/local/bin/start.sh"]
