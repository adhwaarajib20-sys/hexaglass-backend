#!/bin/bash

# Hexaglass Laravel Startup Script for Railway
# This script generates .env and starts the PHP development server

PORT=${PORT:-8080}

echo "=========================================="
echo "🚀 Hexaglass Laravel Startup"
echo "=========================================="

# Step 1: Validate Railway MySQL environment variables
echo ""
echo "📋 Checking Railway MySQL Variables..."
echo "MYSQL_HOST: ${MYSQL_HOST:-NOT SET}"
echo "MYSQL_PORT: ${MYSQL_PORT:-NOT SET}"
echo "MYSQL_NAME: ${MYSQL_NAME:-NOT SET}"
echo "MYSQL_USER: ${MYSQL_USER:-NOT SET}"
echo "MYSQL_PASSWORD: ${MYSQL_PASSWORD:+***SET***}"
echo "APP_KEY: ${APP_KEY:0:20}..."

# Check if variables are set
if [ -z "$MYSQL_HOST" ] || [ -z "$MYSQL_NAME" ] || [ -z "$MYSQL_USER" ] || [ -z "$MYSQL_PASSWORD" ]; then
  echo ""
  echo "❌ ERROR: Railway MySQL variables not set!"
  echo "Make sure MySQL plugin is added to Railway project"
  exit 1
fi

if [ -z "$APP_KEY" ]; then
  echo ""
  echo "❌ ERROR: APP_KEY not set!"
  exit 1
fi

echo "✅ All required variables present"

# Step 2: Generate .env file
echo ""
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
DB_HOST=$MYSQL_HOST
DB_PORT=${MYSQL_PORT:-3306}
DB_DATABASE=$MYSQL_NAME
DB_USERNAME=$MYSQL_USER
DB_PASSWORD=$MYSQL_PASSWORD

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

echo "✅ .env generated:"
echo "   DB_HOST: $MYSQL_HOST"
echo "   DB_PORT: ${MYSQL_PORT:-3306}"
echo "   DB_DATABASE: $MYSQL_NAME"
echo "   DB_USERNAME: $MYSQL_USER"

# Step 3: Clear cache
echo ""
echo "🧹 Clearing cache..."
rm -rf bootstrap/cache/* 2>/dev/null || true
mkdir -p bootstrap/cache

# Step 4: Create necessary directories
echo "📁 Creating directories..."
mkdir -p storage/logs
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Setup complete!"
echo ""
echo "=========================================="
echo "🌐 Starting PHP server on 0.0.0.0:$PORT"
echo "=========================================="

# Step 5: Start PHP server
exec php -S 0.0.0.0:$PORT -t public/
