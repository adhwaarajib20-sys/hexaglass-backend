#!/bin/bash
# Health check script to verify Laravel is ready

echo "🔍 Health check: Verifying Laravel setup..."

# Check if .env exists
if [ ! -f .env ]; then
  echo "❌ .env file not found"
  exit 1
fi

echo "✅ .env file exists"

# Check if APP_KEY is set in .env
if grep -q "^APP_KEY=base64:" .env; then
  echo "✅ APP_KEY is set"
else
  echo "❌ APP_KEY not properly set in .env"
  exit 1
fi

# Check database connection variables
for var in DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD; do
  if grep -q "^$var=" .env; then
    echo "✅ $var is set"
  else
    echo "❌ $var not set in .env"
    exit 1
  fi
done

# Check if storage directory is writable
if [ -w storage ]; then
  echo "✅ storage directory is writable"
else
  echo "❌ storage directory is not writable"
  chmod -R 755 storage 2>/dev/null || true
fi

# Check if bootstrap/cache is writable
if [ -w bootstrap/cache ]; then
  echo "✅ bootstrap/cache is writable"
else
  echo "❌ bootstrap/cache is not writable"
  chmod -R 755 bootstrap/cache 2>/dev/null || true
fi

echo ""
echo "✅ Health check passed! Laravel should be ready to start."
