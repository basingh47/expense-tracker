FROM php:8.2-fpm

# Install system dependencies (Node.js, npm, and PostgreSQL)
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel source code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build frontend (Vite)
RUN npm install && npm run build

# Set proper permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
