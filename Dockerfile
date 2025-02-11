# Step 1: Set up the base image for PHP
FROM php:8.2-fpm-alpine

# Step 2: Install dependencies for Laravel
RUN apk add --no-cache \
    bash \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Step 3: Set working directory
WORKDIR /var/www

# Step 4: Install Composer (for PHP dependency management)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Step 5: Copy the Laravel application files
COPY . .

# Step 6: Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

# Step 7: Set the correct file permissions
RUN chown -R www-data:www-data /var/www

# Step 8: Copy the Nginx configuration
COPY nginx/laravel.conf /etc/nginx/sites-available/laravel
RUN ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/

# Step 9: Expose the port for Nginx
EXPOSE 80

# Step 10: Run Nginx and PHP-FPM
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]