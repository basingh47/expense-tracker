# Start with PHP FPM image
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev gnupg \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Install Node.js (16.x or higher is required by Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel app files
COPY . .

# Install backend dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies and build Vite assets
RUN npm install && npm run build

# Set correct permissions
RUN chmod -R 775 storage bootstrap/cache

# Generate key (optional if APP_KEY already set via .env)
# RUN php artisan key:generate

# Expose port
EXPOSE 8000

# Run Laravel with the internal PHP server
CMD php artisan serve --host=0.0.0.0 --port=8000
