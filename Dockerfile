FROM php:8.2-fpm-alpine

# Pasang dependencies sistem & extension MySQL
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql intl bcmath opcache

# Pasang Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Salin kod projek
COPY . .

# Pasang dependencies composer
RUN composer install --no-dev --optimize-autoloader

# Konfigurasi Nginx
RUN echo 'server { \
    listen 80; \
    root /var/www/public; \
    index index.php; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}' > /etc/nginx/http.d/default.conf

# Kebenaran folder storage & cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

# Script run database migration & start server
CMD php artisan config:clear && \
    php artisan migrate --force --seed --seeder=MasterDataSeeder && \
    php-fpm -D && \
    nginx -g "daemon off;"