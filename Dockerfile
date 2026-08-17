FROM php:8.2-fpm-alpine

# Pasang kelengkapan sistem & library
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    ca-certificates \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    icu-dev \
    oniguruma-dev \
    && update-ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql intl bcmath opcache zip gd

# Pasang Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Salin fail projek & konfigurasi Nginx
COPY . .
COPY nginx.conf /etc/nginx/http.d/default.conf

# Pasang Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Tetapkan kebenaran folder Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

# Mulakan PHP-FPM dan Nginx terus (tanpa sekat startup jika DB lambat connect)
CMD php-fpm -D && nginx -g "daemon off;"