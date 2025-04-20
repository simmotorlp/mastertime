#!/bin/bash

# Set correct permissions for Laravel directories
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Create storage symbolic link if it doesn't exist
if [ ! -L "/var/www/html/public/storage" ]; then
  cd /var/www/html && php artisan storage:link
fi

# Generate application key if not set
APP_KEY=$(grep -E '^APP_KEY=' /var/www/html/.env | cut -d '=' -f2)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" == "" ]; then
  cd /var/www/html && php artisan key:generate
fi

echo "Permissions fixed, symlinks created, and app key generated (if needed)."