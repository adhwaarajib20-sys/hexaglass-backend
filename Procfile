release: php artisan migrate --force && php artisan config:cache && php artisan route:cache
web: php -S 0.0.0.0:${PORT:-8080} -t public/
