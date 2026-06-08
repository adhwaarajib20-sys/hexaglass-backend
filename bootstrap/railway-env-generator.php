<?php
/**
 * Bootstrap: Generate .env file from Railway environment variables
 * This runs before Laravel boots to ensure environment is properly configured
 * Can be executed as: php bootstrap/railway-env-generator.php
 */

// Railway doesn't pass env vars to getenv(), but they ARE in /proc/self/environ
// Let's read them from there instead
function getEnvFromProc($varName) {
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        foreach ($vars as $var) {
            if (strpos($var, $varName . '=') === 0) {
                return substr($var, strlen($varName) + 1);
            }
        }
    }
    return getenv($varName); // Fallback
}

// Get Database variables from Railway (using DB_* format, not MYSQL_*)
$dbHost = getEnvFromProc('DB_HOST');
$dbPort = getEnvFromProc('DB_PORT') ?: '3306';
$dbDatabase = getEnvFromProc('DB_DATABASE');
$dbUsername = getEnvFromProc('DB_USERNAME');
$dbPassword = getEnvFromProc('DB_PASSWORD');
$appKey = getEnvFromProc('APP_KEY');

error_log("🔧 Env Generator: Checking Railway environment variables from /proc/self/environ...");

// If not on Railway, skip (local .env will be used)
if (!$dbHost) {
    error_log("⚠️  Not on Railway (DB_HOST empty) - using local .env");
    exit(0);
}

error_log("✅ Railway detected - will generate .env");
error_log("   DB_HOST: " . ($dbHost ? '✓' : '✗'));
error_log("   DB_DATABASE: " . ($dbDatabase ? '✓' : '✗'));
error_log("   DB_USERNAME: " . ($dbUsername ? '✓' : '✗'));
error_log("   DB_PASSWORD: " . ($dbPassword ? '✓ (hidden)' : '✗'));
error_log("   APP_KEY: " . ($appKey ? '✓' : '✗'));

// Validate required variables
$missing = [];
if (!$dbHost) $missing[] = 'DB_HOST';
if (!$dbDatabase) $missing[] = 'DB_DATABASE';
if (!$dbUsername) $missing[] = 'DB_USERNAME';
if (!$dbPassword) $missing[] = 'DB_PASSWORD';

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
DB_HOST=%DB_HOST%
DB_PORT=%DB_PORT%
DB_DATABASE=%DB_DATABASE%
DB_USERNAME=%DB_USERNAME%
DB_PASSWORD=%DB_PASSWORD%

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
$envContent = str_replace('%DB_HOST%', $dbHost, $envContent);
$envContent = str_replace('%DB_PORT%', $dbPort, $envContent);
$envContent = str_replace('%DB_DATABASE%', $dbDatabase, $envContent);
$envContent = str_replace('%DB_USERNAME%', $dbUsername, $envContent);
$envContent = str_replace('%DB_PASSWORD%', $dbPassword, $envContent);

// Write .env file
if (file_put_contents($envPath, $envContent) === false) {
    error_log("❌ FATAL: Could not write .env file to $envPath");
    exit(1);
}

// Log success
$envSize = filesize($envPath);
error_log("✅ .env file generated successfully at $envPath (size: $envSize bytes)");
error_log("   DB_HOST: " . $dbHost);
error_log("   DB_PORT: " . $dbPort);
error_log("   DB_DATABASE: " . $dbDatabase);
error_log("   DB_USERNAME: " . $dbUsername);

// Verify the file was written correctly by reading it back
$written = file_get_contents($envPath);
if (strpos($written, $dbHost) === false) {
    error_log("❌ ERROR: .env file was written but DB host not found in content!");
    exit(1);
}

// Define constant to prevent regeneration
if (!defined('RAILWAY_ENV_GENERATED')) {
    define('RAILWAY_ENV_GENERATED', true);
}
