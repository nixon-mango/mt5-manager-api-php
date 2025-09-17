#!/bin/bash
set -e

# Docker entrypoint script for MT5 Manager API
echo "Starting MT5 Manager API container..."

# Enable Apache modules if available
echo "Enabling Apache modules..."
a2enmod rewrite headers expires deflate 2>/dev/null || echo "Some modules may not be available"

# Create necessary directories
mkdir -p /var/www/html/logs
mkdir -p /var/www/html/tmp

# Set proper permissions
chown -R www-data:www-data /var/www/html/logs
chown -R www-data:www-data /var/www/html/tmp
chmod -R 755 /var/www/html/logs
chmod -R 755 /var/www/html/tmp

# Configure PHP settings based on environment variables
if [ ! -z "$PHP_MEMORY_LIMIT" ]; then
    echo "memory_limit = $PHP_MEMORY_LIMIT" >> /usr/local/etc/php/conf.d/docker-php-custom.ini
fi

if [ ! -z "$PHP_MAX_EXECUTION_TIME" ]; then
    echo "max_execution_time = $PHP_MAX_EXECUTION_TIME" >> /usr/local/etc/php/conf.d/docker-php-custom.ini
fi

# Enable error logging
echo "log_errors = On" >> /usr/local/etc/php/conf.d/docker-php-custom.ini
echo "error_log = /var/www/html/logs/php_errors.log" >> /usr/local/etc/php/conf.d/docker-php-custom.ini

# Display configuration info
echo "PHP Configuration:"
echo "  Memory Limit: $(php -r 'echo ini_get("memory_limit");')"
echo "  Max Execution Time: $(php -r 'echo ini_get("max_execution_time");')"
echo "  Error Log: $(php -r 'echo ini_get("error_log");')"

# Check if composer dependencies are installed
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Validate MT5 API configuration
if [ ! -z "$MT5_API_HOST" ]; then
    echo "MT5 API Configuration:"
    echo "  Host: $MT5_API_HOST"
    echo "  Port: ${MT5_API_PORT:-443}"
    echo "  Username: ${MT5_API_USERNAME:-'(not set)'}"
    echo "  SSL: ${MT5_API_USE_SSL:-true}"
else
    echo "Warning: MT5_API_HOST not configured. Set environment variables for proper API connection."
fi

# Run the command passed to docker run
exec "$@"