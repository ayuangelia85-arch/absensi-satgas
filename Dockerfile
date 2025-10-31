# Gunakan image PHP 8.2 dengan Apache bawaan
FROM php:8.2-apache

# Install ekstensi yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file project ke dalam container
COPY . .

# Install semua dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Generate key Laravel
RUN php artisan key:generate

# Expose port 10000 (biar Render bisa akses)
EXPOSE 10000

# Jalankan Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000
