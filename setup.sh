#!/bin/bash

# Rebuild containers with the simplified configuration
echo "Stopping current containers..."
docker-compose down

echo "Rebuilding containers..."
docker-compose up -d --build

# Wait for containers to start
echo "Waiting for containers to initialize..."
sleep 10

# Set up Laravel backend
echo "Setting up Laravel backend..."
docker-compose exec app bash -c "
    # Check if .env exists, create if not
    if [ ! -f .env ]; then
        cp .env.example .env
    fi

    # Install dependencies
    composer install --no-interaction

    # Set permissions
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache

    # Generate app key if needed
    php artisan key:generate --no-interaction

    # Create storage link
    php artisan storage:link --no-interaction

    # Run migrations
    php artisan migrate:fresh --seed --no-interaction

    # Clear cache
    php artisan config:clear
    php artisan route:clear
    php artisan cache:clear
    php artisan view:clear
"

# Set up frontend
echo "Setting up frontend..."
docker-compose exec frontend bash -c "
    # Check if node_modules exists
    if [ ! -d node_modules ]; then
        npm install
    fi
"

echo "Setup complete! You can now access:"
echo "- Backend API: http://localhost/api/test"
echo "- Frontend: http://localhost:5173"
echo "- PgAdmin: http://localhost:5050 (admin@example.com / admin)"
echo "- Mailhog: http://localhost:8025"