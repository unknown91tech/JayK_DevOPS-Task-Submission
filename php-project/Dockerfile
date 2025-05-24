# Use a minimalist PHP base image
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install dependencies and PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql gd zip \
    && apk del libpng-dev libzip-dev

# Copy application code
COPY . /var/www/html

# Set permissions and ownership
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Create a non-root user
RUN adduser -u 1000 -D -S -G www-data blumexuser

# Configure PHP-FPM to run as non-root
RUN sed -i 's/user = www-data/user = blumexuser/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/group = www-data/group = blumexuser/' /usr/local/etc/php-fpm.d/www.conf

# Expose port for PHP-FPM
EXPOSE 9000

# Run as non-root user
USER blumexuser

# Start PHP-FPM
CMD ["php-fpm"]