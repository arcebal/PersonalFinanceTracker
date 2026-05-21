#!/bin/bash

echo "=== Starting Laravel ==="
echo "DB_HOST: $DB_HOST"
echo "PORT: ${PORT}"

php artisan storage:link --no-interaction 2>&1 || true

php artisan migrate --force --no-interaction 2>&1 || true

echo "Starting server on port ${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}