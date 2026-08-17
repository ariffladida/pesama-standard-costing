FROM php:8.2-fpm-alpine

# Pasang kelengkapan sistem & pustaka grafik/arkib
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql intl bcmath opcache zip gd

# Pasang Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Salin fail projek
COPY . .

# Pasang pakej Composer tanpa sekatan keperluan platform
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Konfigurasi Nginx ringkas
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

# Kebenaran folder storage & cache Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

# Jalankan seeder & lancarkan Nginx + PHP-FPM
CMD php artisan config:clear && \
    php artisan migrate --force --seed --seeder=MasterDataSeeder && \
    php-fpm -D && \
    nginx -g "daemon off;"