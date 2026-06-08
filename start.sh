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
    echo "⚠️  APP_KEY not set, generating one..."
    APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
  fi
  
  if [ -z "$DB_HOST" ]; then
    echo "❌ ERROR: DB_HOST not set by Railway!"
    exit 1
  fi
  
  cat > .env << EOF
APP_NAME=${APP_NAME:-MigasQueue}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-https://web-production-fc4fb.up.railway.app}
APP_TIMEZONE=${APP_TIMEZONE:-Asia/Jakarta}

APP_LOCALE=${APP_LOCALE:-id}
APP_FALLBACK_LOCALE=${APP_FALLBACK_LOCALE:-id}
APP_FAKER_LOCALE=${APP_FAKER_LOCALE:-id_ID}
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=${LOG_LEVEL:-warning}

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-railway}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

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
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@hexaglass.com}
MAIL_FROM_NAME=${MAIL_FROM_NAME:-MigasQueue}

VITE_APP_NAME=${VITE_APP_NAME:-MigasQueue}

SANCTUM_STATEFUL_DOMAINS=${SANCTUM_STATEFUL_DOMAINS:-web-production-fc4fb.up.railway.app,localhost:8000,localhost:3000,127.0.0.1:8000}
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

