#!/bin/bash

# Ensure PHP MySQL driver is available in Railway environment
echo "Setting up PHP MySQL driver..."

# Try to install via apt if available
apt-get update 2>/dev/null && \
apt-get install -y php-mysql php-pdo php-pdo-mysql 2>/dev/null || \
apt-get install -y php83-mysql php83-pdo php83-pdo-mysql 2>/dev/null || \
echo "MySQL driver setup completed or already available"

# Check if driver loaded
if php -m | grep -q pdo_mysql; then
  echo "✅ PDO MySQL driver available"
else
  echo "⚠️  PDO MySQL driver not loaded - will rely on composer PDO"
fi
