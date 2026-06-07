#!/bin/bash

# Debug script to test application status
# Run this with: railway exec bash debug.sh

echo "🔍 HEXAGLASS BACKEND - DEBUG SCRIPT"
echo "=================================="
echo ""

echo "1️⃣ Checking PHP version..."
php --version
echo ""

echo "2️⃣ Checking if .env exists..."
if [ -f .env ]; then
    echo "✅ .env file exists"
    echo "   APP_ENV=$(grep APP_ENV .env | cut -d= -f2)"
    echo "   APP_KEY=$(grep APP_KEY .env | cut -d= -f2 | head -c 20)..."
    echo "   DB_HOST=$(grep DB_HOST .env | cut -d= -f2)"
else
    echo "❌ .env file NOT found!"
    exit 1
fi
echo ""

echo "3️⃣ Checking database connection..."
php artisan tinker -q <<'EOF'
try {
    DB::connection()->getPdo();
    echo "✅ Database connection OK\n";
} catch (\Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    exit(1);
}
EOF
echo ""

echo "4️⃣ Checking routes..."
php artisan route:list 2>/dev/null | head -20
echo ""

echo "5️⃣ Testing /up endpoint..."
php artisan tinker -q <<'EOF'
$response = app(\Illuminate\Routing\Router::class)->getRoutes()->getByName('up');
if ($response) {
    echo "✅ /up route registered\n";
} else {
    echo "⚠️  /up route might not be registered\n";
}
EOF
echo ""

echo "6️⃣ Checking storage permissions..."
if [ -w "storage/" ]; then
    echo "✅ storage/ is writable"
else
    echo "⚠️  storage/ not writable"
fi

if [ -w "bootstrap/cache/" ]; then
    echo "✅ bootstrap/cache/ is writable"
else
    echo "⚠️  bootstrap/cache/ not writable"
fi
echo ""

echo "7️⃣ Testing application startup..."
php -l public/index.php
echo ""

echo "✅ Debug check completed!"
