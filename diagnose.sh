#!/bin/bash
# Hexaglass Backend - Railway Deployment Diagnostic Script
# Run this: railway exec bash < diagnose.sh

echo "╔════════════════════════════════════════════════════════════╗"
echo "║  HEXAGLASS BACKEND - RAILWAY DEPLOYMENT DIAGNOSTICS        ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "1️⃣  SYSTEM INFORMATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Current Directory:"
pwd
echo ""
echo "PHP Version:"
php --version
echo ""
echo "Node Version (if exists):"
node --version 2>/dev/null || echo "⚠️  Node not found"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "2️⃣  LARAVEL INSTALLATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Laravel Version:"
php artisan --version
echo ""
echo "Composer Version:"
composer --version
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "3️⃣  ENVIRONMENT VARIABLES"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
php artisan tinker -q <<'EOF'
echo "✓ APP_NAME: " . env('APP_NAME') . "\n";
echo "✓ APP_ENV: " . env('APP_ENV') . "\n";
echo "✓ APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "\n";
echo "✓ APP_URL: " . env('APP_URL') . "\n";
echo "✓ APP_KEY: " . (env('APP_KEY') ? '✓ SET' : '❌ NOT SET') . "\n";
echo "\n";
echo "Database:\n";
echo "  DB_CONNECTION: " . env('DB_CONNECTION') . "\n";
echo "  DB_HOST: " . env('DB_HOST') . "\n";
echo "  DB_PORT: " . env('DB_PORT') . "\n";
echo "  DB_DATABASE: " . env('DB_DATABASE') . "\n";
echo "  DB_USERNAME: " . env('DB_USERNAME') . "\n";
echo "  DB_PASSWORD: " . (env('DB_PASSWORD') ? '✓ SET' : '❌ NOT SET') . "\n";
EOF
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "4️⃣  DATABASE CONNECTION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
php artisan tinker -q <<'EOF'
try {
    $pdo = DB::connection()->getPdo();
    echo "✅ Database Connection: SUCCESS\n";
    
    $result = DB::select('SELECT 1');
    echo "✅ Query Test: SUCCESS\n";
} catch (\Exception $e) {
    echo "❌ Database Connection FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}
EOF
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "5️⃣  DATABASE MIGRATIONS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
php artisan migrate:status
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "6️⃣  FILE SYSTEM PERMISSIONS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Storage directory:"
ls -ld storage/ 2>/dev/null || echo "❌ storage/ not found"
echo ""
echo "Bootstrap cache directory:"
ls -ld bootstrap/cache/ 2>/dev/null || echo "❌ bootstrap/cache/ not found"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "7️⃣  ROUTES"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Total routes:"
php artisan route:list | wc -l
echo ""
echo "First 10 API routes:"
php artisan route:list --path=api | head -15
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "8️⃣  RECENT LOG ENTRIES"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Last 30 lines of storage/logs/laravel.log:"
tail -30 storage/logs/laravel.log 2>/dev/null || echo "❌ No log file found"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "9️⃣  CONFIGURATION CACHE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -f bootstrap/cache/config.php ]; then
    echo "✅ Config cache exists"
else
    echo "⚠️  Config cache NOT found"
    echo "   Run: php artisan config:cache"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🔟 ROUTE CACHE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -f bootstrap/cache/routes-api.php ]; then
    echo "✅ Route cache exists"
else
    echo "⚠️  Route cache NOT found"
    echo "   Run: php artisan route:cache"
fi
echo ""

echo "╔════════════════════════════════════════════════════════════╗"
echo "║                    DIAGNOSTICS COMPLETE                    ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo "✅ If all checks pass, application should work!"
echo "❌ If any failed, see error messages above for what to fix."
