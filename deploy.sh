#!/usr/bin/env bash

echo "📦 Post-deployment optimization..."

# Clear all caches silently
php artisan config:clear --quiet 2>/dev/null || true
php artisan cache:clear --quiet 2>/dev/null || true
php artisan view:clear --quiet 2>/dev/null || true
php artisan route:clear --quiet 2>/dev/null || true

# Set permissions
chmod -R 755 storage/ 2>/dev/null || true
chmod -R 755 bootstrap/cache/ 2>/dev/null || true

# Re-optimize
php artisan config:cache --quiet 2>/dev/null || true
php artisan route:cache --quiet 2>/dev/null || true

echo "✅ Optimization completed"
