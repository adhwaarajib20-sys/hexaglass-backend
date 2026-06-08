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
