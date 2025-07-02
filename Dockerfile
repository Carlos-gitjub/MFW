# Dockerfile
FROM php:8.2-apache

# Enable mod_rewrite (important for many PHP apps)
RUN a2enmod rewrite

# Install PHP extensions (mysqli for MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy app source code into Apache root
COPY ./app /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Set file permissions (optional, depends on app)
RUN chown -R www-data:www-data /var/www/html
