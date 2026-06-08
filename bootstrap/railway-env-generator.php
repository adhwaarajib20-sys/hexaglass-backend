<?php
/**
 * Bootstrap: Generate .env file from Railway environment variables
 * This runs before Laravel boots to ensure environment is properly configured
 */

// Get Railway MySQL variables
$railwayVars = [
    'APP_KEY' => getenv('APP_KEY'),
    'MYSQL_HOST' => getenv('MYSQL_HOST'),
    'MYSQL_PORT' => getenv('MYSQL_PORT') ?: '3306',
    'MYSQL_NAME' => getenv('MYSQL_NAME'),
    'MYSQL_USER' => getenv('MYSQL_USER'),
    'MYSQL_PASSWORD' => getenv('MYSQL_PASSWORD'),
];

// Validate required variables
$required = ['APP_KEY', 'MYSQL_HOST', 'MYSQL_NAME', 'MYSQL_USER', 'MYSQL_PASSWORD'];
foreach ($required as $var) {
    if (empty($railwayVars[$var])) {
        error_log("❌ FATAL: Railway environment variable '$var' is empty or not set!");
        exit(1);
    }
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
