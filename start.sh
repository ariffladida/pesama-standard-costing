#!/bin/sh

# Pastikan port mendengar PORT dinamik dari Render jika wujud, lalai ke 80
PORT=${PORT:-80}
sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf 2>/dev/null || true

# Pastikan kebenaran direktori cache Laravel sentiasa betul
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Bersihkan cache dan jalankan migrasi database automatik di Render
echo "Clearing application cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Running database migrations..."
php artisan migrate --force

# Mulakan PHP-FPM di latar belakang (background)
echo "Starting PHP-FPM..."
php-fpm -D

# Mulakan Nginx di latar hadapan (foreground)
echo "Starting Nginx on port $PORT..."
exec nginx -g "daemon off;"