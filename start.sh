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

