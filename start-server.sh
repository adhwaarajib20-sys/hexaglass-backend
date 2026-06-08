#!/bin/bash
set -e

PORT=${PORT:-8080}

echo "🌐 Starting Hexaglass Laravel on 0.0.0.0:$PORT"

# Validate required environment variables
for var in APP_KEY DB_HOST DB_USERNAME DB_PASSWORD DB_DATABASE; do
  if [ -z "${!var}" ]; then
    echo "❌ ERROR: $var is not set!"
    exit 1
  fi
done

echo "✅ All required environment variables are set"

# Generate .env if doesn't exist
if [ ! -f .env ]; then
  echo "📝 Generating .env file..."
  cat > .env << EOF
APP_NAME=MigasQueue
APP_ENV=production
APP_KEY=$APP_KEY
APP_DEBUG=true
APP_URL=https://web-production-fc4fb.up.railway.app
APP_TIMEZONE=Asia/Jakarta

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.railway.app

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@hexaglass.com
MAIL_FROM_NAME=MigasQueue

VITE_APP_NAME=MigasQueue

SANCTUM_STATEFUL_DOMAINS=web-production-fc4fb.up.railway.app,localhost:8000,localhost:3000,127.0.0.1:8000
EOF
  echo "✅ .env created"
fi

# Create necessary directories
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Directories ready"

# Run one-time setup commands
echo "🔧 Running Laravel setup commands..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan migrate --force 2>/dev/null || true

echo "✅ Setup complete"
echo "🚀 Starting PHP server on port $PORT..."

# Run PHP development server
exec php -S 0.0.0.0:$PORT -t public/ index.php
