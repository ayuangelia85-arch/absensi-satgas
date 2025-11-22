FROM php:8.2-fpm

# Install system deps
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . .

# Install composer deps (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Generate app key if not present (optional)
# RUN php artisan key:generate

# Make storage writable
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
