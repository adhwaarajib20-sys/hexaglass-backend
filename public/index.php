<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// === WRITE TO LOG FILE ===
@file_put_contents('/tmp/php_request.log', '[' . date('Y-m-d H:i:s') . '] URI: ' . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

// === ENVIRONMENT SETUP - MUST RUN FIRST ===
// Delete cache files
$cachePath = __DIR__.'/../bootstrap/cache';
foreach (['config.php', 'routes-v7.php', 'routes-v7.php.gz', 'events.php', 'events.php.gz'] as $file) {
    $path = $cachePath . '/' . $file;
    if (file_exists($path)) {
        @unlink($path);
    }
}

// Generate .env from Railway environment
require __DIR__.'/../bootstrap/railway-env-generator.php';

// Load .env into $_SERVER and $_ENV superglobals for Laravel to access
$envPath = __DIR__.'/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (($value[0] ?? '') === '"' && ($value[-1] ?? '') === '"') {
                $value = substr($value, 1, -1);
            }
            
            $_SERVER[$key] = $value;
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// === ULTRA SIMPLE TEST ===
if ($_SERVER['REQUEST_URI'] === '/ping') {
    header('Content-Type: text/plain; charset=UTF-8');
    header('Content-Length: 4');
    echo "PONG";
    flush();
    exit(0);
}

if ($_SERVER['REQUEST_URI'] === '/status') {
    header('Content-Type: text/plain; charset=UTF-8');
    echo "STATUS_OK";
    flush();
    exit(0);
}

// === DIAGNOSTIC: Check users in database ===
if ($_SERVER['REQUEST_URI'] === '/check-users') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    try {
        // Debug: Show what's in $_SERVER
        echo "=== DEBUG: Environment Vars ===\n";
        echo "DB_HOST: " . ($_SERVER['DB_HOST'] ?? 'NOT SET') . "\n";
        echo "DB_PORT: " . ($_SERVER['DB_PORT'] ?? 'NOT SET') . "\n";
        echo "DB_DATABASE: " . ($_SERVER['DB_DATABASE'] ?? 'NOT SET') . "\n";
        echo "DB_USERNAME: " . ($_SERVER['DB_USERNAME'] ?? 'NOT SET') . "\n";
        echo "DB_PASSWORD: " . (isset($_SERVER['DB_PASSWORD']) ? '***SET***' : 'NOT SET') . "\n\n";
        
        // Get database credentials from environment
        $dbHost = $_SERVER['DB_HOST'] ?? 'mysql.railway.internal';
        $dbPort = $_SERVER['DB_PORT'] ?? '3306';
        $dbDatabase = $_SERVER['DB_DATABASE'] ?? 'railway';
        $dbUsername = $_SERVER['DB_USERNAME'] ?? 'root';
        $dbPassword = $_SERVER['DB_PASSWORD'] ?? '';
        
        echo "Using: $dbHost:$dbPort/$dbDatabase as $dbUsername\n";
        
        // Test raw PDO connection
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase";
        $pdo = new \PDO($dsn, $dbUsername, $dbPassword);
        
        $stmt = $pdo->query("SELECT id, name, email, status FROM users LIMIT 10");
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        echo "✓ Database connection successful\n";
        echo "Total users: " . count($users) . "\n\n";
        
        foreach ($users as $user) {
            echo "- ID: {$user['id']}, Email: {$user['email']}, Name: {$user['name']}, Status: {$user['status']}\n";
        }
        
        // Check admin user specifically
        $stmt = $pdo->prepare("SELECT id, name, email, status FROM users WHERE email = ?");
        $stmt->execute(['admin@hexaglass.com']);
        $admin = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        echo "\n=== Admin User Details ===\n";
        if ($admin) {
            echo "Found: " . $admin['email'] . "\n";
            echo "Name: " . $admin['name'] . "\n";
            echo "ID: " . $admin['id'] . "\n";
            echo "Status: " . $admin['status'] . "\n";
        } else {
            echo "NOT FOUND - Need to run seeders!\n";
        }
    } catch (\Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
    flush();
    exit(0);
}

// === TEST ENDPOINTS ===
if ($_SERVER['REQUEST_URI'] === '/dump-all-env') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        
        $output = "=== ALL /proc/self/environ VARIABLES ===\n";
        $output .= "Total count: " . count($vars) . "\n\n";
        
        // Sort for readability
        sort($vars);
        
        foreach ($vars as $var) {
            if (!empty($var)) {
                $output .= "$var\n";
            }
        }
        
        echo $output;
    } else {
        echo "/proc/self/environ NOT FOUND";
    }
    flush();
    exit(0);
}

if ($_SERVER['REQUEST_URI'] === '/check-db-password') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    $output = "=== CHECKING DB_PASSWORD SOURCE ===\n\n";
    
    // Check /proc/self/environ
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        
        $output .= "Searching /proc/self/environ for DB_PASSWORD or MYSQL_PASSWORD...\n";
        foreach ($vars as $var) {
            if (strpos($var, 'PASSWORD') !== false) {
                $output .= "Found: " . $var . "\n";
            }
        }
    } else {
        $output .= "/proc/self/environ NOT found\n";
    }
    
    echo $output;
    flush();
    exit(0);
}

if ($_SERVER['REQUEST_URI'] === '/show-env') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    $envPath = __DIR__.'/../.env';
    if (file_exists($envPath)) {
        $content = file_get_contents($envPath);
        echo "=== .env FILE CONTENT ===\n";
        echo $content;
    } else {
        echo ".env file does not exist at $envPath";
    }
    flush();
    exit(0);
}

if ($_SERVER['REQUEST_URI'] === '/debug-env-gen') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    $output = "=== PROC ENVIRONMENT DEBUG ===\n\n";
    
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        
        $db_vars = array_filter($vars, function($v) {
            return strpos($v, 'DB_') === 0 || strpos($v, 'MYSQL_') === 0 || strpos($v, 'APP_KEY') === 0;
        });
        
        $output .= "Found " . count($db_vars) . " database/app variables:\n";
        foreach ($db_vars as $var) {
            $parts = explode('=', $var, 2);
            if (isset($parts[0])) {
                $key = $parts[0];
                $val = isset($parts[1]) ? substr($parts[1], 0, 20) . (strlen($parts[1]) > 20 ? '...' : '') : '';
                $output .= "  $key = $val\n";
            }
        }
    } else {
        $output .= "/proc/self/environ not accessible\n";
    }
    
    echo $output;
    flush();
    exit(0);
}

if ($_SERVER['REQUEST_URI'] === '/debug') {
    @file_put_contents('php://stderr', "DEBUG endpoint hit\n");
    header('Content-Type: text/plain; charset=UTF-8');
    echo "OK";
    flush();
    exit(0);
}

if ($_SERVER['REQUEST_URI'] === '/env-status') {
    header('Content-Type: text/plain; charset=UTF-8');
    echo "TESTING_ENV_ENDPOINT";
    flush();
    exit(0);
}

// === MIGRATION ENDPOINT (ONE-TIME SETUP) ===
if ($_SERVER['REQUEST_URI'] === '/run-migrations') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    ob_start();
    
    try {
        // Bootstrap Laravel
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        
        // Run migrations
        $status = $kernel->call('migrate', ['--force' => true, '--verbose' => true]);
        
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "✓ Migrations completed successfully!\n\n";
            echo "Output:\n";
            echo $output;
        } else {
            echo "✗ Migration failed with status code: $status\n\n";
            echo "Output:\n";
            echo $output;
        }
    } catch (\Exception $e) {
        $output = ob_get_clean();
        echo "✗ Error running migrations:\n";
        echo $e->getMessage() . "\n\n";
        if (!empty($output)) {
            echo "Partial output:\n" . $output;
        }
    }
    flush();
    exit(0);
}

// === FRESH MIGRATIONS (RESET AND REBUILD) ===
if ($_SERVER['REQUEST_URI'] === '/run-migrations-fresh') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    ob_start();
    
    try {
        // Bootstrap Laravel
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        
        // Run fresh migrations (drop all and recreate)
        $status = $kernel->call('migrate:fresh', ['--force' => true, '--verbose' => true]);
        
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "✓ Fresh migrations completed successfully!\n\n";
            echo "Output:\n";
            echo $output;
        } else {
            echo "✗ Fresh migration failed with status code: $status\n\n";
            echo "Output:\n";
            echo $output;
        }
    } catch (\Exception $e) {
        $output = ob_get_clean();
        echo "✗ Error running fresh migrations:\n";
        echo $e->getMessage() . "\n\n";
        if (!empty($output)) {
            echo "Partial output:\n" . $output;
        }
    }
    flush();
    exit(0);
}

// === SEEDER ENDPOINT (CREATE TEST USERS) ===
if ($_SERVER['REQUEST_URI'] === '/run-seeders') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    ob_start();
    
    try {
        // Bootstrap Laravel
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        
        // Run seeders
        $status = $kernel->call('db:seed', ['--force' => true, '--verbose' => true]);
        
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "✓ Seeders completed successfully!\n\n";
            echo "Test users created:\n";
            echo "- admin@hexaglass.com / Admin@123\n";
            echo "- operator@hexaglass.com / Operator@123\n";
            echo "- satpam@hexaglass.com / Satpam@123\n\n";
            echo "Output:\n";
            echo $output;
        } else {
            echo "✗ Seeder failed with status code: $status\n\n";
            echo "Output:\n";
            echo $output;
        }
    } catch (\Exception $e) {
        $output = ob_get_clean();
        echo "✗ Error running seeders:\n";
        echo $e->getMessage() . "\n\n";
        if (!empty($output)) {
            echo "Partial output:\n" . $output;
        }
    }
    flush();
    exit(0);
}

// === TEST AUTHENTICATION ===
if ($_SERVER['REQUEST_URI'] === '/test-auth') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    try {
        // Test database connection and user lookup directly
        $dbHost = $_SERVER['DB_HOST'] ?? 'mysql.railway.internal';
        $dbPort = $_SERVER['DB_PORT'] ?? '3306';
        $dbDatabase = $_SERVER['DB_DATABASE'] ?? 'railway';
        $dbUsername = $_SERVER['DB_USERNAME'] ?? 'root';
        $dbPassword = $_SERVER['DB_PASSWORD'] ?? '';
        
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase";
        $pdo = new \PDO($dsn, $dbUsername, $dbPassword);
        
        // Get admin user
        $stmt = $pdo->prepare("SELECT id, name, email, password, status FROM users WHERE email = ?");
        $stmt->execute(['admin@hexaglass.com']);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        echo "=== User Found ===\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Name: " . $user['name'] . "\n";
        echo "Password Hash: " . substr($user['password'], 0, 20) . "...\n";
        echo "Status: " . $user['status'] . "\n\n";
        
        // Test password verification
        $testPassword = "Admin@123";
        $match = password_verify($testPassword, $user['password']);
        
        echo "=== Password Test ===\n";
        echo "Testing password: $testPassword\n";
        echo "Password matches: " . ($match ? "YES ✓" : "NO ✗") . "\n";
        
        if ($match) {
            echo "\n✓ Authentication test PASSED - User credentials are correct!\n";
        } else {
            echo "\n✗ Authentication test FAILED - Password doesn't match!\n";
        }
    } catch (\Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
    flush();
    exit(0);
}

// === VIEW LARAVEL LOGS ===
if ($_SERVER['REQUEST_URI'] === '/logs') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    $logPath = __DIR__.'/../storage/logs/laravel.log';
    if (file_exists($logPath)) {
        // Read last 100 lines
        $lines = file($logPath);
        $lastLines = array_slice($lines, -100);
        
        echo "=== Last 100 lines of Laravel log ===\n\n";
        echo implode('', $lastLines);
    } else {
        echo "Log file not found at $logPath";
    }
    flush();
    exit(0);
}

// === DEBUG REQUEST HEADERS ===
if ($_SERVER['REQUEST_URI'] === '/debug-headers') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    echo "=== REQUEST INFORMATION ===\n";
    echo "Scheme: " . ($_SERVER['REQUEST_SCHEME'] ?? 'NOT SET') . "\n";
    echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'NOT SET') . "\n";
    echo "HTTP_PROTOCOL: " . ($_SERVER['SERVER_PROTOCOL'] ?? 'NOT SET') . "\n";
    echo "\n=== PROXY HEADERS ===\n";
    echo "X-Forwarded-Proto: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NOT SET') . "\n";
    echo "X-Forwarded-For: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'NOT SET') . "\n";
    echo "X-Forwarded-Host: " . ($_SERVER['HTTP_X_FORWARDED_HOST'] ?? 'NOT SET') . "\n";
    echo "\n=== HTTP HOST ===\n";
    echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";
    echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'NOT SET') . "\n";
    echo "SERVER_PORT: " . ($_SERVER['SERVER_PORT'] ?? 'NOT SET') . "\n";
    
    flush();
    exit(0);
}

// === DEBUG SESSION CONFIG ===
if ($_SERVER['REQUEST_URI'] === '/session-config') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    echo "=== SESSION CONFIGURATION ===\n";
    echo "SESSION_DRIVER: " . ($_SERVER['SESSION_DRIVER'] ?? 'NOT SET') . "\n";
    echo "SESSION_LIFETIME: " . ($_SERVER['SESSION_LIFETIME'] ?? 'NOT SET') . "\n";
    echo "SESSION_DOMAIN: " . ($_SERVER['SESSION_DOMAIN'] ?? 'NOT SET') . "\n";
    echo "SESSION_PATH: " . ($_SERVER['SESSION_PATH'] ?? 'NOT SET') . "\n";
    echo "SESSION_SECURE_COOKIE: " . ($_SERVER['SESSION_SECURE_COOKIE'] ?? 'NOT SET') . "\n";
    echo "SESSION_HTTP_ONLY: " . ($_SERVER['SESSION_HTTP_ONLY'] ?? 'NOT SET') . "\n";
    echo "SESSION_SAME_SITE: " . ($_SERVER['SESSION_SAME_SITE'] ?? 'NOT SET') . "\n\n";
    
    echo "=== REQUEST COOKIES ===\n";
    echo "Incoming cookies: " . (empty($_COOKIE) ? "NONE" : count($_COOKIE)) . "\n";
    foreach ($_COOKIE as $name => $value) {
        echo "- $name = " . substr($value, 0, 30) . "...\n";
    }
    
    echo "\n=== RESPONSE HEADERS TO BE SET ===\n";
    echo "This page will include Set-Cookie headers (check network tab)\n";
    
    flush();
    exit(0);
}

// === CSRF DEBUG ===
if ($_SERVER['REQUEST_URI'] === '/csrf-test') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    echo "=== CSRF / SESSION DEBUG ===\n\n";
    
    echo "=== SESSION CONFIGURATION ===\n";
    echo "driver: " . ($_SERVER['SESSION_DRIVER'] ?? 'NOT SET') . "\n";
    echo "table: " . ($_SERVER['SESSION_TABLE'] ?? 'NOT SET') . "\n\n";
    
    echo "=== SESSION FILES/DATA ===\n";
    echo "PHP session_id(): " . (session_id() ?: 'NOT SET') . "\n";
    echo "PHPSESSID cookie: " . ($_COOKIE['PHPSESSID'] ?? 'NOT SET') . "\n";
    echo "LARAVEL_SESSION cookie: " . ($_COOKIE['LARAVEL_SESSION'] ?? 'NOT SET') . "\n\n";
    
    echo "=== REQUEST COOKIES ===\n";
    if (empty($_COOKIE)) {
        echo "NO COOKIES\n";
    } else {
        foreach ($_COOKIE as $name => $value) {
            echo "- $name = " . substr($value, 0, 30) . "...\n";
        }
    }
    
    echo "\n=== HTTP HEADERS ===\n";
    echo "Cookie header: " . ($_SERVER['HTTP_COOKIE'] ?? 'NOT SET') . "\n";
    
    flush();
    exit(0);
}

// === DEBUG LOGIN ENDPOINT ===
if ($_SERVER['REQUEST_URI'] === '/login-debug' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain; charset=UTF-8');
    
    echo "=== POST /login-debug RECEIVED ===\n";
    echo "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
    echo "Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'NOT SET') . "\n";
    echo "Content-Length: " . ($_SERVER['CONTENT_LENGTH'] ?? '0') . "\n\n";
    
    echo "=== REQUEST BODY ===\n";
    $body = file_get_contents('php://input');
    echo "Raw body length: " . strlen($body) . " bytes\n";
    echo "Body preview: " . substr($body, 0, 200) . "...\n\n";
    
    echo "=== PARSED POST DATA ===\n";
    echo "POST fields: " . count($_POST) . "\n";
    foreach ($_POST as $key => $value) {
        if ($key === '_token') {
            echo "- $key = " . substr($value, 0, 20) . "...\n";
        } else if ($key === 'password') {
            echo "- $key = ***\n";
        } else {
            echo "- $key = $value\n";
        }
    }
    
    echo "\n=== INCOMING COOKIES ===\n";
    foreach ($_COOKIE as $name => $value) {
        echo "- $name = " . substr($value, 0, 20) . "...\n";
    }
    
    flush();
    exit(0);
}

// === MAINTENANCE ===
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// === BOOTSTRAP LARAVEL ===
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
