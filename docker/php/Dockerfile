FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create www-data user if it doesn't exist
RUN groupadd -g 1000 www-data || true
RUN useradd -u 1000 -ms /bin/bash -g www-data www-data || true

# Copy custom php.ini
COPY php.ini /usr/local/etc/php/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
