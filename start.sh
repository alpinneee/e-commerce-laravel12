#!/bin/bash

# Generate app key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Start the server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}