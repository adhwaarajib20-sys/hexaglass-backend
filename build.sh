#!/bin/bash
set -e

echo "🚀 Starting build process..."

# Run composer install
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm install --ci 2>/dev/null || npm install

# Build assets
echo "🎨 Building frontend assets..."
npm run build

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force || true

# Cache configuration
echo "⚡ Caching configuration..."
php artisan config:cache || true

# Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache || true

echo "✅ Build process completed!"
