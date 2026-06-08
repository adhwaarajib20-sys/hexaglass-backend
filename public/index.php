<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// TEST: Super simple debug endpoint before any Laravel loading
if ($_SERVER['REQUEST_URI'] === '/debug') {
    http_response_code(200);
    header('Content-Type: text/plain; charset=utf-8');
    echo "DEBUG ENDPOINT WORKS!\n";
    echo "Time: " . date('Y-m-d H:i:s GMT+7') . "\n";
    echo "PHP Version: " . phpversion() . "\n";
    echo "Memory: " . (memory_get_usage(true) / 1024 / 1024) . " MB\n";
    echo "CWD: " . getcwd() . "\n";
    echo "Laravel Start Time: " . microtime(true) . "\n";
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
