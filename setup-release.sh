#!/bin/bash
set -e

echo "🔧 Running release tasks..."

# Generate .env file from environment variables
echo "📝 Generating .env file..."
cat > .env << 'EOF'
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

# Try to substitute environment variables
sed -i "s|\$APP_KEY|$APP_KEY|g" .env
sed -i "s|\$DB_HOST|$DB_HOST|g" .env
sed -i "s|\$DB_PORT|${DB_PORT:-3306}|g" .env
sed -i "s|\$DB_DATABASE|$DB_DATABASE|g" .env
sed -i "s|\$DB_USERNAME|$DB_USERNAME|g" .env
sed -i "s|\$DB_PASSWORD|$DB_PASSWORD|g" .env

echo "✅ .env created"

# Run Laravel setup commands
echo "📦 Running migrations..."
php artisan migrate --force

echo "⚙️  Caching configuration..."
php artisan config:cache

echo "🛣️  Caching routes..."
php artisan route:cache

echo "👁️  Caching views..."
php artisan view:cache

echo "🚀 Running optimization..."
php artisan optimize

echo "✅ Release tasks complete!"
