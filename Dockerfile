FROM php:8.2-apache

# Install required packages
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install zip \

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set permissions for the /var/www/html directory
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Ensure the web server has write permissions to the data directory
USER www-data
RUN mkdir -p /var/www/html/data
RUN chmod -R 775 /var/www/html/data

# Install dependencies
RUN composer install