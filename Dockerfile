# Base image PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install PostgreSQL client library dan driver pdo_pgsql
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy seluruh source code Jobsheet 13 ke DocumentRoot Apache
COPY jobsheet-13/ /var/www/html/

# Atur hak akses file ke user www-data Apache
RUN chown -R www-data:www-data /var/www/html

# Expose port HTTP default
EXPOSE 80

# Jalankan Apache di foreground
CMD ["apache2-foreground"]
