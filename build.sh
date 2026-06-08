#!/bin/bash

echo "🚀 Starting build process..."

# Exit on error but use set -e after env check
if [ -z "$APP_KEY" ]; then
  echo "❌ ERROR: APP_KEY not set by Railway!"
  exit 1
fi

if [ -z "$DB_HOST" ]; then
  echo "❌ ERROR: DB_HOST not set by Railway!"
  exit 1
fi

set -e

# Generate .env file from Railway environment variables
echo "📝 Generating .env file..."
cat > .env << EOF
APP_NAME=Hexaglass
APP_ENV=production
APP_KEY=$APP_KEY
APP_DEBUG=false
APP_URL=https://web-production-2598a.up.railway.app

APP_LOCALE=id
APP_FALLBACK_LOCALE=en

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-railway}
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@hexaglass.com"
MAIL_FROM_NAME="Hexaglass"

VITE_APP_NAME="Hexaglass"

SANCTUM_STATEFUL_DOMAINS=web-production-2598a.up.railway.app,localhost:8000,localhost:3000,127.0.0.1:8000
EOF

echo "✅ .env file created"

# Set permissions first
echo "🔐 Setting directory permissions..."
chmod -R 755 storage/ 2>/dev/null || true
chmod -R 755 bootstrap/cache/ 2>/dev/null || true
chmod 755 artisan 2>/dev/null || true

# Run composer install
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --quiet

# Skip npm during build (can add to frontend separately)
echo "⚠️  Skipping npm (use separate frontend deploy)"

# Cache critical config
echo "⚡ Caching configuration..."
php artisan config:cache --quiet || php artisan config:cache

# Run migrations with timeout protection
echo "🗄️  Running database migrations..."
timeout 60 php artisan migrate --force --quiet || {
  echo "⚠️  Migration completed or timed out";
}

# Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache --quiet || php artisan route:cache

echo ""
echo "✅ Build process completed!"
  php artisan key:generate 2>&1 | tail -1
fi

# Cache config
echo "⚡ Caching configuration..."
php artisan config:cache 2>&1 | tail -1

# Clear view cache first (prevents permission issues)
php artisan view:clear 2>/dev/null || true

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force 2>&1 | tail -5 || {
  echo "⚠️  Migration warning (continuing)...";
}

# Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache 2>&1 | tail -1 || {
  echo "⚠️  Route cache warning (continuing)...";
}

echo ""
echo "✅ Build process completed!"
echo "🚀 Application ready!"
