#!/bin/bash

# Railway Debug Script
# Run with: railway exec bash debug-railway.sh

echo "🔍 RAILWAY DEPLOYMENT DEBUG"
echo "=============================="
echo ""

echo "1️⃣ PHP Version"
php --version
echo ""

echo "2️⃣ Check .env file"
if [ -f .env ]; then
    echo "✅ .env exists"
    echo "APP_ENV=$(grep APP_ENV .env | cut -d= -f2)"
    echo "APP_DEBUG=$(grep APP_DEBUG .env | cut -d= -f2)"
    echo "DB_DATABASE=$(grep DB_DATABASE .env | cut -d= -f2)"
else
    echo "❌ .env NOT FOUND"
    exit 1
fi
echo ""

echo "3️⃣ Check if migrations ran"
php artisan migrate:status 2>&1 | head -30
echo ""

echo "4️⃣ Database connection test"
php artisan tinker -q <<'EOF'
try {
    DB::connection()->getPdo();
    echo "✅ Database connected\n";
    
    $tables = DB::select("SHOW TABLES");
    $tableCount = count($tables);
    echo "📊 Tables found: $tableCount\n";
    
    if ($tableCount > 0) {
        echo "✅ Migrations appear to have run\n";
    } else {
        echo "❌ No tables - migrations NOT run\n";
    }
} catch (\Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
EOF
echo ""

echo "5️⃣ Check Laravel cache"
ls -la bootstrap/cache/
echo ""

echo "6️⃣ Check public directory"
ls -la public/
echo ""

echo "7️⃣ Test artisan health"
php artisan health 2>&1
echo ""

echo "8️⃣ Routes registered?"
php artisan route:list 2>&1 | grep -E "health|/up|GET"
echo ""

echo "✅ Debug complete"
