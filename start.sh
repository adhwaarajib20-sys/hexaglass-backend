#!/bin/bash

# Railway start script

PORT=${PORT:-8080}

echo "🌐 Starting Hexaglass Laravel on 0.0.0.0:$PORT"
echo "📂 Document root: $(pwd)/public"

# Verify .env exists
if [ ! -f .env ]; then
  echo "❌ ERROR: .env file not found!"
  exit 1
fi

# Show relevant .env values
echo "🔍 Configuration:"
grep "APP_ENV\|DB_HOST\|DB_DATABASE" .env || true

echo ""
echo "✅ Starting PHP server..."
echo "📊 Ready for requests on 0.0.0.0:$PORT"
echo ""

# Start PHP server (don't use exec so we can see if it crashes)
php -S 0.0.0.0:$PORT -t public/ 2>&1

# If we reach here, server exited
echo "❌ PHP server stopped!"
exit 1

