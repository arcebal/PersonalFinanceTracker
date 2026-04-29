#!/bin/bash

echo "=== Starting Laravel ==="
echo "DB_HOST: $DB_HOST"
echo "PORT: ${PORT:-8080}"

echo "--- storage:link ---"
php artisan storage:link --no-interaction 2>&1 || true

echo "--- migrate ---"
php artisan migrate --force --no-interaction 2>&1 || true

echo "--- serving on ${PORT:-8080} ---"
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}