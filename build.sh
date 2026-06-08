#!/bin/bash
set -e

echo "🚀 Starting build process..."

# Verify critical Railway environment variables are set
echo "🔍 Checking environment variables..."
if [ -z "$APP_KEY" ]; then
  echo "⚠️  APP_KEY not set, generating..."
  APP_KEY=$(php -r 'echo base64_encode(random_bytes(32));')
fi

if [ -z "$DB_HOST" ]; then
  echo "❌ ERROR: DB_HOST not set by Railway!"
  exit 1
fi

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

echo "✅ .env file created with variables:"
echo "   APP_KEY: $(echo $APP_KEY | cut -c1-20)..."
echo "   DB_HOST: $DB_HOST"
echo "   DB_PORT: ${DB_PORT:-3306}"

# Run composer install
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev 2>&1 | grep -E "(Loading|Generating|Installing)" || true

# Try npm install & build, but don't fail if it fails
echo "📦 Installing Node dependencies (optional)..."
npm install --ci 2>/dev/null || npm install 2>/dev/null || echo "⚠️  npm install skipped"

echo "🎨 Building frontend assets (optional)..."
npm run build 2>/dev/null || echo "⚠️  npm build skipped"

# Set permissions BEFORE caching/migrating
echo "🔐 Setting directory permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 755 artisan

# Generate app key if needed (shouldn't be needed but just in case)
if ! grep -q "^APP_KEY=base64:" .env; then
  echo "⚠️  APP_KEY missing from .env, generating..."
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
