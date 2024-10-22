FROM php:8.2-apache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set permissions for the /var/www/html directory
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html


# Install dependencies
RUN composer install