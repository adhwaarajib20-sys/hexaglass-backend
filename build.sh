#!/bin/bash
set -e

echo "🚀 Starting build process..."

# Generate .env file from Railway environment variables
echo "📝 Generating .env file..."
cat > .env << EOF
APP_NAME=${APP_NAME:-Hexaglass}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-https://web-production-2598a.up.railway.app}

APP_LOCALE=${APP_LOCALE:-id}
APP_FALLBACK_LOCALE=${APP_FALLBACK_LOCALE:-en}
APP_FAKER_LOCALE=${APP_FAKER_LOCALE:-id_ID}

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_LEVEL=${LOG_LEVEL:-info}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-railway}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=${SESSION_DRIVER:-database}
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}

CACHE_STORE=${CACHE_STORE:-database}

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@hexaglass.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"

SANCTUM_STATEFUL_DOMAINS=${SANCTUM_STATEFUL_DOMAINS:-web-production-2598a.up.railway.app,localhost:8000,localhost:3000,127.0.0.1:8000}
SANCTUM_TOKEN_PREFIX=${SANCTUM_TOKEN_PREFIX:-}
EOF

echo "✅ .env file created"

# Verify critical env variables
echo "🔍 Validating environment variables..."
if [ -z "$APP_KEY" ]; then
  echo "❌ ERROR: APP_KEY not set!"
  exit 1
fi

if [ -z "$DB_PASSWORD" ]; then
  echo "❌ ERROR: DB_PASSWORD not set!"
  exit 1
fi

echo "✅ Environment variables validated"

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
php artisan migrate --force

# Cache configuration
echo "⚡ Caching configuration..."
php artisan config:cache

# Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache

# Cache views (optional but recommended)
echo "🎨 Caching views..."
php artisan view:cache || true

echo "✅ Build process completed successfully!"
echo "🌐 App ready at: ${APP_URL}"
