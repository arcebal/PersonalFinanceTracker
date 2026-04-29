FROM composer:2 AS composer
FROM php:8.4-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libxml2-dev libonig-dev \
    nodejs npm unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip xml

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN echo '#!/bin/bash\n\
    echo "=== Starting Laravel App ==="\n\
    echo "APP_ENV: $APP_ENV"\n\
    echo "DB_HOST: $DB_HOST"\n\
    echo "PORT: ${PORT:-8080}"\n\
    \n\
    echo "--- Running storage:link ---"\n\
    php artisan storage:link --no-interaction 2>&1 || echo "storage:link skipped"\n\
    \n\
    echo "--- Running migrations ---"\n\
    php artisan migrate --force --no-interaction 2>&1\n\
    \n\
    echo "--- Starting server on port ${PORT:-8080} ---"\n\
    exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}' > /app/start.sh

RUN chmod +x /app/start.sh

EXPOSE 8080
CMD ["/bin/bash", "/app/start.sh"]