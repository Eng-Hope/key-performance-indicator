FROM alpine:latest

# Install necessary packages, including curl, PHP 8.3 and its extensions, Composer, and Laravel installer
RUN apk add --no-cache curl php83 php83-cli php83-fpm php83-phar php83-bcmath php83-curl php83-fileinfo php83-intl php83-mbstring php83-openssl php83-mysqlnd php83-tokenizer php83-session php83-sodium php83-dom php83-pgsql php83-pdo php83-pdo_pgsql nodejs npm \
    && curl -fsSL https://getcomposer.org/installer | php83 -- --install-dir=/usr/local/bin --filename=composer \
    && composer global require laravel/installer

# Create app directory
WORKDIR /app

# Copy application files (excluding node_modules)
COPY . /app

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader



# Install Node dependencies and build assets
RUN npm install && npm run build

# Expose port
EXPOSE 8000

# Start the application.  You'll likely need a web server (like Nginx) in front of php-fpm
CMD ["php83", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
