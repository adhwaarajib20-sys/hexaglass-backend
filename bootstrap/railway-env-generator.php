<?php
/**
 * Bootstrap: Generate .env file from Railway environment variables
 * This runs before Laravel boots to ensure environment is properly configured
 */

// Only proceed if running on Railway (has MYSQL_HOST set)
$mysqlHost = getenv('MYSQL_HOST');
$appKey = getenv('APP_KEY');

// If not on Railway AND .env already exists, skip generator
if (!$mysqlHost && file_exists(dirname(__DIR__) . '/.env')) {
    return; // Local development, .env exists
}

// If not on Railway AND no .env exists, error
if (!$mysqlHost) {
    error_log("⚠️ Warning: Not on Railway (MYSQL_HOST not set) and no .env file exists");
    return;
}

// Get Railway MySQL variables
$railwayVars = [
    'APP_KEY' => $appKey,
    'MYSQL_HOST' => $mysqlHost,
    'MYSQL_PORT' => getenv('MYSQL_PORT') ?: '3306',
    'MYSQL_NAME' => getenv('MYSQL_NAME'),
    'MYSQL_USER' => getenv('MYSQL_USER'),
    'MYSQL_PASSWORD' => getenv('MYSQL_PASSWORD'),
];

// Output what we're reading (for debugging)
error_log("🔧 Railway Env Generator: Checking environment variables...");
error_log("   MYSQL_HOST: " . ($railwayVars['MYSQL_HOST'] ? '✓ set' : '✗ empty'));
error_log("   MYSQL_PORT: " . ($railwayVars['MYSQL_PORT'] ? '✓ set' : '✗ empty'));
error_log("   MYSQL_NAME: " . ($railwayVars['MYSQL_NAME'] ? '✓ set' : '✗ empty'));
error_log("   MYSQL_USER: " . ($railwayVars['MYSQL_USER'] ? '✓ set' : '✗ empty'));
error_log("   MYSQL_PASSWORD: " . ($railwayVars['MYSQL_PASSWORD'] ? '✓ set (***hidden***)' : '✗ empty'));
error_log("   APP_KEY: " . ($railwayVars['APP_KEY'] ? '✓ set' : '✗ empty'));

// Validate required variables
$required = ['MYSQL_HOST', 'MYSQL_NAME', 'MYSQL_USER', 'MYSQL_PASSWORD'];
$missing = [];
foreach ($required as $var) {
    if (empty($railwayVars[$var])) {
        $missing[] = $var;
    }
}

if (!empty($missing)) {
    error_log("❌ FATAL: Missing required Railway environment variables: " . implode(', ', $missing));
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
$envContent = str_replace('%APP_KEY%', $railwayVars['APP_KEY'], $envContent);
$envContent = str_replace('%MYSQL_HOST%', $railwayVars['MYSQL_HOST'], $envContent);
$envContent = str_replace('%MYSQL_PORT%', $railwayVars['MYSQL_PORT'], $envContent);
$envContent = str_replace('%MYSQL_NAME%', $railwayVars['MYSQL_NAME'], $envContent);
$envContent = str_replace('%MYSQL_USER%', $railwayVars['MYSQL_USER'], $envContent);
$envContent = str_replace('%MYSQL_PASSWORD%', $railwayVars['MYSQL_PASSWORD'], $envContent);

// Write .env file
if (file_put_contents($envPath, $envContent) === false) {
    error_log("❌ FATAL: Could not write .env file to $envPath");
    exit(1);
}

// Log success
error_log("✅ .env file generated successfully");
error_log("   DB_HOST: " . $railwayVars['MYSQL_HOST']);
error_log("   DB_DATABASE: " . $railwayVars['MYSQL_NAME']);

// Define constant to prevent regeneration
if (!defined('RAILWAY_ENV_GENERATED')) {
    define('RAILWAY_ENV_GENERATED', true);
}
