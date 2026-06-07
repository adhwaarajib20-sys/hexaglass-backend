#!/bin/bash
set -e

# Railway sets PORT as environment variable
PORT=${PORT:-8080}

echo "🌐 Starting PHP server on 0.0.0.0:$PORT"
echo "📂 Document root: $(pwd)/public"
echo "🔍 App environment: $(cat .env | grep APP_ENV)"

# Start PHP built-in server
exec php -S 0.0.0.0:$PORT -t public/
