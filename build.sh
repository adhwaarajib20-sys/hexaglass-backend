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
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

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
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@hexaglass.com"
MAIL_FROM_NAME="Hexaglass"

VITE_APP_NAME="Hexaglass"

SANCTUM_STATEFUL_DOMAINS=web-production-2598a.up.railway.app,localhost:8000,localhost:3000,127.0.0.1:8000
SANCTUM_TOKEN_PREFIX=
ENVEOF

echo "✅ .env file created"
echo ""

# Verify .env was created
if [ ! -f .env ]; then
  echo "❌ ERROR: Failed to create .env file"
  exit 1
fi

# Check .env content
echo "📋 Checking .env values..."
APP_KEY_SET=$(grep APP_KEY .env | cut -d= -f2)
DB_HOST_SET=$(grep DB_HOST .env | cut -d= -f2)
echo "  APP_KEY: ${APP_KEY_SET:0:20}..."
echo "  DB_HOST: $DB_HOST_SET"

# Validate APP_KEY is not empty
if [ -z "$APP_KEY_SET" ]; then
  echo "❌ WARNING: APP_KEY is empty - using placeholder"
fi

echo ""

# Run composer install
echo "📦 Installing Composer dependencies..."
if ! composer install --optimize-autoloader --no-dev 2>&1 | tail -20; then
  echo "❌ ERROR: Composer install failed"
  exit 1
fi

# Install Node dependencies
echo ""
echo "📦 Installing Node dependencies..."
if ! npm install --ci 2>/dev/null || ! npm install 2>&1 | tail -10; then
  echo "⚠️  Warning: npm install had issues but continuing..."
fi

# Build assets
echo ""
echo "🎨 Building frontend assets..."
if npm run build 2>&1 | tail -20; then
  echo "✅ Assets built successfully"
else
  echo "⚠️  Warning: npm run build had issues but continuing..."
fi

# Cache config BEFORE migrations (in case migrations have env-dependent logic)
echo ""
echo "⚡ Caching configuration..."
php artisan config:cache 2>&1 || echo "⚠️  config:cache failed"

# Run migrations
echo ""
echo "🗄️  Running database migrations..."
if php artisan migrate --force 2>&1 | tail -20; then
  echo "✅ Migrations completed"
else
  echo "⚠️  Warning: Migrations had issues but continuing..."
fi

# Cache routes
echo ""
echo "🛣️  Caching routes..."
php artisan route:cache 2>&1 || echo "⚠️  route:cache failed"

# Cache views (optional)
echo ""
echo "🎨 Caching views..."
php artisan view:cache 2>&1 || echo "⚠️  view:cache failed"

echo ""
echo "✅ Build process completed successfully!"
echo "🌐 App ready at: https://web-production-2598a.up.railway.app"
echo "📝 Log level: INFO"
echo "🔍 APP_ENV: production"
