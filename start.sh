#!/bin/bash

# Railway start script
PORT=${PORT:-8080}

echo "🌐 Starting Hexaglass Laravel on 0.0.0.0:$PORT"
echo "📂 Document root: $(pwd)/public"

# Generate .env if it doesn't exist (Railway injects environment variables at runtime)
if [ ! -f .env ]; then
  echo "📝 Generating .env from environment variables..."
  
  # Validate required variables
  if [ -z "$APP_KEY" ]; then
    echo "❌ ERROR: APP_KEY not set by Railway!"
    exit 1
  fi
  
  if [ -z "$DB_HOST" ]; then
    echo "❌ ERROR: DB_HOST not set by Railway!"
    exit 1
  fi
  
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

  echo "✅ .env created at runtime"
else
  echo "🔍 .env file already exists"
fi

# Show config
echo "🔍 Configuration loaded from .env"

# Set permissions
chmod -R 755 storage/ 2>/dev/null || true
chmod -R 755 bootstrap/cache/ 2>/dev/null || true

echo ""
echo "✅ Starting PHP server on port $PORT..."
echo ""

# Start PHP server - let it run in foreground
exec php -S 0.0.0.0:$PORT -t public/ 2>&1

