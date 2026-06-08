<?php
/**
 * Bootstrap: Generate .env file from Railway environment variables
 * This runs before Laravel boots to ensure environment is properly configured
 * Can be executed as: php bootstrap/railway-env-generator.php
 */

// Get all Railway MySQL variables
$mysqlHost = getenv('MYSQL_HOST');
$mysqlPort = getenv('MYSQL_PORT') ?: '3306';
$mysqlName = getenv('MYSQL_NAME');
$mysqlUser = getenv('MYSQL_USER');
$mysqlPassword = getenv('MYSQL_PASSWORD');
$appKey = getenv('APP_KEY');

error_log("🔧 Env Generator: Checking for Railway environment variables...");

// If not on Railway, skip (local .env will be used)
if (!$mysqlHost) {
    error_log("⚠️  Not on Railway (MYSQL_HOST empty) - using local .env");
    exit(0);
}

error_log("✅ Railway detected - will generate .env");
error_log("   MYSQL_HOST: " . ($mysqlHost ? '✓' : '✗'));
error_log("   MYSQL_NAME: " . ($mysqlName ? '✓' : '✗'));
error_log("   MYSQL_USER: " . ($mysqlUser ? '✓' : '✗'));
error_log("   MYSQL_PASSWORD: " . ($mysqlPassword ? '✓ (hidden)' : '✗'));
error_log("   APP_KEY: " . ($appKey ? '✓' : '✗'));

// Validate required variables
$missing = [];
if (!$mysqlHost) $missing[] = 'MYSQL_HOST';
if (!$mysqlName) $missing[] = 'MYSQL_NAME';
if (!$mysqlUser) $missing[] = 'MYSQL_USER';
if (!$mysqlPassword) $missing[] = 'MYSQL_PASSWORD';

if (!empty($missing)) {
    error_log("❌ FATAL: Missing Railway environment variables: " . implode(', ', $missing));
    exit(1);
}

// Generate .env file
$envPath = dirname(__DIR__) . '/.env';
$envContent = <<<'EOF'
APP_NAME=MigasQueue
APP_ENV=production
APP_KEY=%APP_KEY%
APP_DEBUG=true
APP_URL=https://web-production-fc4fb.up.railway.app
APP_TIMEZONE=Asia/Jakarta

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=%MYSQL_HOST%
DB_PORT=%MYSQL_PORT%
DB_DATABASE=%MYSQL_NAME%
DB_USERNAME=%MYSQL_USER%
DB_PASSWORD=%MYSQL_PASSWORD%

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
MAIL_FROM_ADDRESS=noreply@hexaglass.com
MAIL_FROM_NAME=MigasQueue

VITE_APP_NAME=MigasQueue

SANCTUM_STATEFUL_DOMAINS=web-production-fc4fb.up.railway.app,localhost:8000,localhost:3000,127.0.0.1:8000
EOF;

// Replace placeholders with actual values
$envContent = str_replace('%APP_KEY%', $appKey, $envContent);
$envContent = str_replace('%MYSQL_HOST%', $mysqlHost, $envContent);
$envContent = str_replace('%MYSQL_PORT%', $mysqlPort, $envContent);
$envContent = str_replace('%MYSQL_NAME%', $mysqlName, $envContent);
$envContent = str_replace('%MYSQL_USER%', $mysqlUser, $envContent);
$envContent = str_replace('%MYSQL_PASSWORD%', $mysqlPassword, $envContent);

// Write .env file
if (file_put_contents($envPath, $envContent) === false) {
    error_log("❌ FATAL: Could not write .env file to $envPath");
    exit(1);
}

// Log success
$envSize = filesize($envPath);
error_log("✅ .env file generated successfully at $envPath (size: $envSize bytes)");
error_log("   DB_HOST: " . $mysqlHost);
error_log("   DB_PORT: " . $mysqlPort);
error_log("   DB_DATABASE: " . $mysqlName);
error_log("   DB_USERNAME: " . $mysqlUser);

// Verify the file was written correctly by reading it back
$written = file_get_contents($envPath);
if (strpos($written, $mysqlHost) === false) {
    error_log("❌ ERROR: .env file was written but MySQL host not found in content!");
    exit(1);
}

// Define constant to prevent regeneration
if (!defined('RAILWAY_ENV_GENERATED')) {
    define('RAILWAY_ENV_GENERATED', true);
}
