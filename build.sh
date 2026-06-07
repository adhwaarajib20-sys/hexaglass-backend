#!/bin/bash
set -e

echo "🚀 Starting build process..."

# Generate .env file from Railway environment variables
echo "📝 Generating .env file..."
cat > .env << 'ENVEOF'
APP_NAME=Hexaglass
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=https://web-production-2598a.up.railway.app

APP_LOCALE=id
APP_FALLBACK_LOCALE=en

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@hexaglass.com"
MAIL_FROM_NAME="Hexaglass"

VITE_APP_NAME="Hexaglass"

SANCTUM_STATEFUL_DOMAINS=web-production-2598a.up.railway.app,localhost:8000,localhost:3000,127.0.0.1:8000
ENVEOF

echo "✅ .env file created"

# Run composer install
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev 2>&1 | grep -E "(Loading|Generating|Installing)" || true

# Try npm install & build, but don't fail if it fails
echo "📦 Installing Node dependencies (optional)..."
npm install --ci 2>/dev/null || npm install 2>/dev/null || echo "⚠️  npm install skipped"

echo "🎨 Building frontend assets (optional)..."
npm run build 2>/dev/null || echo "⚠️  npm build skipped"

# Cache config
echo "⚡ Caching configuration..."
php artisan config:cache 2>&1 | tail -1

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force 2>&1 | tail -3

# Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache 2>&1 | tail -1

# Clear view cache if exists
php artisan view:clear 2>/dev/null || true

echo ""
echo "✅ Build process completed!"
echo "🚀 Starting app on port 8080..."
