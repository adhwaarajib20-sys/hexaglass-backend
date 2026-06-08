#!/bin/bash

echo "🚀 Build: Preparing Laravel application..."

# Validate environment variables
if [ -z "$APP_KEY" ]; then
  echo "❌ ERROR: APP_KEY not set!"
  exit 1
fi

if [ -z "$DB_HOST" ]; then
  echo "❌ ERROR: DB_HOST not set!"
  exit 1
fi

set -e

# Generate .env file
echo "📝 Generating .env..."
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

echo "✅ .env created"

# Set permissions
chmod -R 755 storage/ bootstrap/cache/ 2>/dev/null || true

# Install composer dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --quiet 2>&1 | tail -1 || composer install --no-dev --optimize-autoloader

# Clear any cached config first
php artisan config:clear --quiet 2>/dev/null || true

# Cache Laravel config
echo "⚡ Caching config..."
php artisan config:cache --quiet 2>&1 || php artisan config:cache

# Try to run migrations with better error handling
echo "🗄️  Running database migrations..."
php artisan migrate --force 2>&1 || {
  echo "⚠️  Migration encountered an error";
  echo "Attempting to continue...";
}

# Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache --quiet 2>&1 || php artisan route:cache

echo "✅ Build complete"
