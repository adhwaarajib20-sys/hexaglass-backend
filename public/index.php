<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// === WRITE TO LOG FILE ===
@file_put_contents('/tmp/php_request.log', '[' . date('Y-m-d H:i:s') . '] URI: ' . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

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

// === LOAD .env into $_SERVER ===
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        // Set in both $_SERVER and $_ENV for compatibility
        $_SERVER[$key] = $value;
        $_ENV[$key] = $value;
        if (!isset($_SERVER['DB_HOST']) && $key === 'DB_HOST') $_SERVER['DB_HOST'] = $value;
    }
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
if (strpos($_SERVER['REQUEST_URI'], '/run-migrations') === 0) {
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

// === CHECK DATABASE CONNECTION ===
if (strpos($_SERVER['REQUEST_URI'], '/check-db') === 0) {
    header('Content-Type: text/plain; charset=UTF-8');
    
    try {
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        
        $host = $_SERVER['DB_HOST'] ?? 'mysql.railway.internal';
        $port = $_SERVER['DB_PORT'] ?? 3306;
        $database = $_SERVER['DB_DATABASE'] ?? 'railway';
        $username = $_SERVER['DB_USERNAME'] ?? 'railway';
        $password = $_SERVER['DB_PASSWORD'] ?? '';
        
        echo "Attempting connection with:\n";
        echo "  Host: $host\n";
        echo "  Port: $port\n";
        echo "  Database: $database\n";
        echo "  Username: $username\n";
        echo "  Password: " . (empty($password) ? '(empty)' : '(set)') . "\n\n";
        
        // Test basic PDO connection
        $dsn = "mysql:host=$host;port=$port;dbname=$database";
        $pdo = new \PDO($dsn, $username, $password);
        
        echo "✓ Database connection successful\n\n";
        
        // Check tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        echo "Tables in database: " . count($tables) . "\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
        
        echo "\n";
        
        // Check if migrations table exists
        if (in_array('migrations', $tables)) {
            echo "✓ Migrations table exists\n\n";
            $stmt = $pdo->query("SELECT * FROM migrations ORDER BY batch, migration");
            $migrations = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            echo "Migrations run: " . count($migrations) . "\n";
            foreach ($migrations as $m) {
                echo "  Batch {$m['batch']}: {$m['migration']}\n";
            }
        } else {
            echo "✗ No migrations table - database is empty\n";
        }
        
    } catch (\Exception $e) {
        echo "✗ Database Error: " . $e->getMessage() . "\n";
        echo "Code: " . $e->getCode() . "\n";
    }
    flush();
    exit(0);
}

// === RESET DATABASE ===
if (strpos($_SERVER['REQUEST_URI'], '/reset-db') === 0) {
    header('Content-Type: text/plain; charset=UTF-8');
    
    ob_start();
    
    try {
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        
        echo "Running migrate:refresh (drops all tables and re-runs migrations)...\n\n";
        
        $status = $kernel->call('migrate:refresh', ['--force' => true, '--verbose' => true]);
        
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "✓ Database reset and migrations completed!\n\n";
            echo $output;
        } else {
            echo "✗ Reset failed with status: $status\n\n";
            echo $output;
        }
    } catch (\Exception $e) {
        $output = ob_get_clean();
        echo "✗ Error: " . $e->getMessage() . "\n\n";
        echo $output;
    }
    flush();
    exit(0);
}

// === COMPREHENSIVE ENVIRONMENT DIAGNOSTIC ===
if (strpos($_SERVER['REQUEST_URI'], '/diagnose-env') === 0) {
    header('Content-Type: text/plain; charset=UTF-8');
    
    echo "=== PROC/SELF/ENVIRON ===\n";
    if (file_exists('/proc/self/environ')) {
        $environ = file_get_contents('/proc/self/environ');
        $vars = explode("\0", $environ);
        $db_vars = array_filter($vars, function($v) {
            return strpos($v, 'DB_') === 0;
        });
        foreach ($db_vars as $var) {
            echo "  $var\n";
        }
    } else {
        echo "  /proc/self/environ NOT FOUND\n";
    }
    
    echo "\n=== .env FILE ===\n";
    $envPath = __DIR__.'/../.env';
    if (file_exists($envPath)) {
        $lines = explode("\n", file_get_contents($envPath));
        foreach ($lines as $line) {
            if (strpos($line, 'DB_') === 0) {
                echo "  $line\n";
            }
        }
    } else {
        echo "  .env NOT FOUND\n";
    }
    
    echo "\n=== \$_SERVER VARIABLES ===\n";
    echo "  DB_HOST=" . ($_SERVER['DB_HOST'] ?? 'NOT_SET') . "\n";
    echo "  DB_PORT=" . ($_SERVER['DB_PORT'] ?? 'NOT_SET') . "\n";
    echo "  DB_DATABASE=" . ($_SERVER['DB_DATABASE'] ?? 'NOT_SET') . "\n";
    echo "  DB_USERNAME=" . ($_SERVER['DB_USERNAME'] ?? 'NOT_SET') . "\n";
    echo "  DB_PASSWORD=" . (isset($_SERVER['DB_PASSWORD']) ? (empty($_SERVER['DB_PASSWORD']) ? '(EMPTY)' : '(SET)') : 'NOT_SET') . "\n";
    
    flush();
    exit(0);
}
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// === BOOTSTRAP LARAVEL ===
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
