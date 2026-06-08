#!/bin/bash

echo "🚀 Starting Railway Deployment for Hexaglass Backend"
echo ""

# Step 1: Clear caches
echo "📦 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Step 2: Optimize for production
echo "⚡ Optimizing application for production..."
php artisan optimize
php artisan config:cache

# Step 3: Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Step 4: Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Step 5: Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache

# Step 6: Cache permission and roles if spatie permission is used
echo "📋 Caching permissions..."
php artisan permission:cache-reset || true

# Step 7: Build front-end assets
if [ -f "package.json" ]; then
    echo "🎨 Building front-end assets..."
    npm install --no-save --prefer-offline
    npm run build
fi

echo ""
echo "✅ Deployment preparation complete!"
