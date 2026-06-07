#!/usr/bin/env bash
set -e

echo "🚀 Starting post-deployment setup..."

# Clear caches
echo "📦 Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Set correct permissions
echo "🔐 Setting directory permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 storage/logs/*

# Optimize for production
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "✅ Post-deployment setup completed!"
