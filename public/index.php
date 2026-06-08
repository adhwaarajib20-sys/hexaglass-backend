<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// TEST: Debug endpoint
if ($_SERVER['REQUEST_URI'] === '/debug') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "DEBUG: PHP is running\n";
    echo "Time: " . date('Y-m-d H:i:s') . "\n";
    exit(0);
}

// TEST: Check if .env exists
if ($_SERVER['REQUEST_URI'] === '/env-status') {
    header('Content-Type: application/json; charset=utf-8');
    $envPath = __DIR__.'/../.env';
    echo json_encode([
        'env_exists' => file_exists($envPath) ? 'YES' : 'NO',
        'env_size' => file_exists($envPath) ? filesize($envPath) : 0,
    ]);
    exit(0);
}

// TEST: Test env generator manually
if ($_SERVER['REQUEST_URI'] === '/test-env-gen') {
    header('Content-Type: text/plain; charset=utf-8');
    
    // Check what's in /proc/self/environ
    echo "=== Checking /proc/self/environ ===\n";
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        $found = 0;
        foreach ($vars as $var) {
            if (strpos($var, 'DB_HOST') === 0 || strpos($var, 'DB_DATABASE') === 0) {
                echo $var . "\n";
                $found++;
            }
        }
        if ($found == 0) echo "NO DB_* vars found!\n";
    } else {
        echo "/proc/self/environ NOT FOUND\n";
    }
    
    echo "\n=== .env before generation ===\n";
    $envPath = __DIR__.'/../.env';
    echo "exists: " . (file_exists($envPath) ? 'YES' : 'NO') . "\n";
    
    exit(0);
}

// Delete all cached files to force fresh reads
$cachePath = __DIR__.'/../bootstrap/cache';
foreach (['config.php', 'routes-v7.php', 'routes-v7.php.gz', 'events.php', 'events.php.gz'] as $file) {
    $path = $cachePath . '/' . $file;
    if (file_exists($path)) {
        @unlink($path);
    }
}

// Generate .env from Railway environment variables
require __DIR__.'/../bootstrap/railway-env-generator.php';

// Maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

if ($_SERVER['REQUEST_URI'] === '/debug') {
    http_response_code(200);
    header('Content-Type: text/plain; charset=utf-8');
    echo "DEBUG ENDPOINT WORKS!\n";
    echo "Time: " . date('Y-m-d H:i:s GMT+7') . "\n";
    echo "PHP Version: " . phpversion() . "\n";
    echo "Memory: " . (memory_get_usage(true) / 1024 / 1024) . " MB\n";
    echo "CWD: " . getcwd() . "\n";
    exit(0);
}

// TEST: Check .env file status
if ($_SERVER['REQUEST_URI'] === '/env-status') {
    header('Content-Type: application/json; charset=utf-8');
    $envPath = __DIR__.'/../.env';
    echo json_encode([
        'env_exists' => file_exists($envPath) ? 'YES' : 'NO',
        'env_size' => file_exists($envPath) ? filesize($envPath) : 0,
        'env_readable' => file_exists($envPath) && is_readable($envPath) ? 'YES' : 'NO',
    ]);
    exit(0);
}

// TEST: Simple text output to verify PHP works
if (isset($_GET['test']) && $_GET['test'] === 'echo') {
    http_response_code(200);
    header('Content-Type: text/plain; charset=utf-8');
    exit("SUCCESS: PHP is working! Time: " . date('Y-m-d H:i:s GMT\\+7') . "\n" .
         "Memory: " . memory_get_usage(true) / 1024 / 1024 . " MB\n" .
         "DB connection test will follow...");
}

// TEST: Cache file status
if ($_SERVER['REQUEST_URI'] === '/cache-status') {
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    $cachePath = __DIR__.'/../bootstrap/cache';
    $output = json_encode([
        'config_cached' => file_exists($cachePath . '/config.php') ? 'YES' : 'NO',
        'routes_cached' => file_exists($cachePath . '/routes-v7.php') ? 'YES' : 'NO',
        'env_exists' => file_exists(__DIR__.'/../.env') ? 'YES' : 'NO',
    ], JSON_PRETTY_PRINT);
    echo $output;
    ob_flush();
    flush();
    die();
}

// TEST: Simple JSON test
if ($_SERVER['REQUEST_URI'] === '/json-test') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'JSON test works', 'time' => date('Y-m-d H:i:s')]);
    die();
}

// TEST: Read /proc env directly (no generator call)
if ($_SERVER['REQUEST_URI'] === '/proc-test') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "=== Reading /proc/self/environ directly ===\n\n";
    
    if (!file_exists('/proc/self/environ')) {
        echo "/proc/self/environ not found!\n";
        die();
    }
    
    $environ = file_get_contents('/proc/self/environ');
    $vars = explode("\0", $environ);
    
    $dbVars = [];
    foreach ($vars as $var) {
        if (!empty($var) && (strpos($var, 'DB_') !== false || strpos($var, 'APP_') !== false)) {
            list($key, $value) = explode('=', $var, 2);
            $dbVars[$key] = $value;
        }
    }
    
    echo count($dbVars) . " DB/APP environment variables found:\n\n";
    foreach ($dbVars as $key => $value) {
        $val = (strlen($value) > 80) ? substr($value, 0, 80) . '...' : $value;
        echo "$key = $val\n";
    }
    die();
}

// TEST: Simple env generator test
if ($_SERVER['REQUEST_URI'] === '/simple-gen') {
    header('Content-Type: text/plain; charset=utf-8');
    
    // Check proc environ
    echo "=== Checking /proc/self/environ ===\n";
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        foreach ($vars as $var) {
            if (strpos($var, 'DB_HOST') !== false) echo $var . "\n";
            if (strpos($var, 'DB_DATABASE') !== false) echo $var . "\n";
        }
    }
    
    echo "\n=== Before calling env generator ===\n";
    echo "CWD: " . getcwd() . "\n";
    
    $envPath = getcwd() . '/.env';
    echo ".env path: " . $envPath . "\n";
    echo ".env exists before: " . (file_exists($envPath) ? 'YES' : 'NO') . "\n";
    
    echo "\n=== Calling env generator ===\n";
    set_error_handler(function($errno, $errstr) {
        echo "ERROR [$errno]: $errstr\n";
        return true;
    });
    
    require __DIR__.'/../bootstrap/railway-env-generator.php';
    
    restore_error_handler();
    
    echo "\n=== After calling env generator ===\n";
    echo ".env exists after: " . (file_exists($envPath) ? 'YES' : 'NO') . "\n";
    if (file_exists($envPath)) {
        echo ".env size: " . filesize($envPath) . " bytes\n";
        $content = file_get_contents($envPath);
        echo ".env first 300 chars:\n";
        echo substr($content, 0, 300) . "\n";
    }
    
    die();
}

// TEST: Run env generator and test it
if ($_SERVER['REQUEST_URI'] === '/test-gen-env') {
    header('Content-Type: application/json; charset=utf-8');
    $response = [];
    
    // Check environ before generation
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        foreach ($vars as $var) {
            if (strpos($var, 'DB_HOST') !== false) {
                $parts = explode('=', $var);
                $response['proc_db_host'] = isset($parts[1]) ? $parts[1] : 'EMPTY';
            }
            if (strpos($var, 'DB_DATABASE') !== false) {
                $parts = explode('=', $var);
                $response['proc_db_database'] = isset($parts[1]) ? $parts[1] : 'EMPTY';
            }
        }
    }
    
    // Try to run env generator
    ob_start();
    try {
        require __DIR__.'/../bootstrap/railway-env-generator.php';
        $response['generator_output'] = ob_get_clean();
    } catch (Exception $e) {
        $response['generator_error'] = $e->getMessage();
        ob_end_clean();
    }
    
    // Check if .env was created
    $envPath = __DIR__.'/../.env';
    $response['env_exists_after'] = file_exists($envPath) ? 'YES' : 'NO';
    if (file_exists($envPath)) {
        $response['env_size'] = filesize($envPath) . ' bytes';
        $lines = explode("\n", file_get_contents($envPath));
        $response['env_sample'] = implode("\n", array_slice($lines, 0, 3));
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    die();
}

// TEST: Find env - check for any .env or env files
if ($_SERVER['REQUEST_URI'] === '/find-env') {
    header('Content-Type: application/json; charset=utf-8');
    $response = [];
    $locations = [
        '/app/.env',
        '/app/.env.production',
        '/app/app/.env',
        '/.env',
        '/home/.env',
        '/root/.env',
        getcwd() . '/.env',
    ];
    
    foreach ($locations as $loc) {
        $response[$loc] = file_exists($loc) ? 'EXISTS (' . filesize($loc) . ' bytes)' : 'NOT FOUND';
    }
    
    // Also try to read /proc/self/environ
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        $mysql_vars = [];
        foreach ($vars as $var) {
            if (strpos($var, 'MYSQL') !== false || strpos($var, 'DB') !== false || strpos($var, 'DATABASE') !== false) {
                $mysql_vars[] = explode('=', $var)[0];
            }
        }
        $response['proc_environ_mysql_keys'] = $mysql_vars ?: [];
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    die();
}

// TEST: Environment check - includes $_SERVER and $_ENV
if ($_SERVER['REQUEST_URI'] === '/env-check') {
    header('Content-Type: application/json; charset=utf-8');
    $envFile = __DIR__.'/../.env';
    $response = [
        'env_exists' => file_exists($envFile) ? 'YES' : 'NO',
        'env_readable' => is_readable($envFile) ? 'YES' : 'NO',
    ];
    
    if (file_exists($envFile)) {
        $content = file_get_contents($envFile);
        $response['env_size'] = strlen($content) . ' bytes';
        $response['env_lines'] = count(explode("\n", $content));
        $response['env_preview'] = implode("\n", array_slice(explode("\n", $content), 0, 5));
    } else {
        // Check getenv()
        $response['getenv_mysql_host'] = getenv('MYSQL_HOST') ?: 'NOT SET';
        $response['getenv_mysql_port'] = getenv('MYSQL_PORT') ?: 'NOT SET';
        
        // Check $_SERVER
        $response['server_mysql_host'] = $_SERVER['MYSQL_HOST'] ?? 'NOT SET';
        $response['server_mysql_port'] = $_SERVER['MYSQL_PORT'] ?? 'NOT SET';
        
        // Check $_ENV
        $response['env_mysql_host'] = $_ENV['MYSQL_HOST'] ?? 'NOT SET';
        $response['env_mysql_port'] = $_ENV['MYSQL_PORT'] ?? 'NOT SET';
        
        // List first 10 MYSQL* keys in $_SERVER
        $mysqlVars = [];
        foreach ($_SERVER as $key => $val) {
            if (strpos($key, 'MYSQL') !== false || strpos($key, 'DB') !== false) {
                $mysqlVars[$key] = (strlen($val) > 50) ? substr($val, 0, 50) . '...' : $val;
            }
        }
        $response['mysql_vars_in_server'] = $mysqlVars ?: 'NONE FOUND';
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    die();
}

// CRITICAL: Delete all cached files to force fresh .env read
$cachePath = __DIR__.'/../bootstrap/cache';
$cacheFiles = [
    'config.php',
    'routes-v7.php', 
    'routes-v7.php.gz',
    'events.php',
    'events.php.gz',
];

foreach ($cacheFiles as $cacheFile) {
    $file = $cachePath . '/' . $cacheFile;
    if (file_exists($file)) {
        @unlink($file);
    }
}

// Generate .env from Railway environment variables before Laravel boots
// Always attempt to generate - it validates and exits if not on Railway
require __DIR__.'/../bootstrap/railway-env-generator.php';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
