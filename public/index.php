<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Log all requests for debugging
error_log("REQUEST: " . $_SERVER['REQUEST_URI'] . " at " . date('Y-m-d H:i:s'));

// === ULTRA SIMPLE TEST ===
if ($_SERVER['REQUEST_URI'] === '/ping') {
    header('Content-Type: text/plain; charset=UTF-8');
    echo "PONG";
    error_log("PING endpoint executed");
    exit(0);
}

// === BEFORE ANYTHING ELSE ===
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

// Backup: Create .env manually if generator failed
$envPath = __DIR__.'/../.env';
if (!file_exists($envPath)) {
    function readFromProc($varName) {
        if (file_exists('/proc/self/environ')) {
            $environ = file_get_contents('/proc/self/environ');
            $vars = explode("\0", $environ);
            foreach ($vars as $var) {
                if (strpos($var, $varName . '=') === 0) {
                    return substr($var, strlen($varName) + 1);
                }
            }
        }
        return '';
    }
    
    $host = readFromProc('DB_HOST') ?: 'mysql.railway.internal';
    $port = readFromProc('DB_PORT') ?: '3306';
    $database = readFromProc('DB_DATABASE') ?: 'hexaglass_db';
    $username = readFromProc('DB_USERNAME') ?: 'railway';
    $password = readFromProc('DB_PASSWORD') ?: '';
    $appKey = readFromProc('APP_KEY') ?: 'base64:TcjCW1iHmMeebYhqRReWOWRR2NXX6buMQVWY68LuwEQ=';
    
    $content = "APP_NAME=MigasQueue\nAPP_ENV=production\nAPP_DEBUG=true\nAPP_KEY=$appKey\n";
    $content .= "DB_CONNECTION=mysql\nDB_HOST=$host\nDB_PORT=$port\nDB_DATABASE=$database\n";
    $content .= "DB_USERNAME=$username\nDB_PASSWORD=$password\n";
    
    file_put_contents($envPath, $content);
}

// === TEST ENDPOINTS ===
if ($_SERVER['REQUEST_URI'] === '/debug') {
    $output = "OK at " . date('Y-m-d H:i:s') . "\n";
    @file_put_contents(__DIR__.'/../storage/logs/debug.txt', $output, FILE_APPEND);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $output;
    exit(0);
}

if ($_SERVER['REQUEST_URI'] === '/env-status') {
    $status = file_exists($envPath) ? "YES" : "NO";
    $output = $status . " at " . date('Y-m-d H:i:s') . "\n";
    @file_put_contents(__DIR__.'/../storage/logs/env-status.txt', $output, FILE_APPEND);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $output;
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
