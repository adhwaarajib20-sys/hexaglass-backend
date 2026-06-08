<?php
/**
 * Load environment variables at runtime for Railway deployment
 * This ensures APP_KEY and database credentials from Railway are available
 */

if (! function_exists('env')) {
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? ($_SERVER[$key] ?? $default);
    }
}

// Ensure critical environment variables are set
$required_vars = ['APP_KEY', 'DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE'];

foreach ($required_vars as $var) {
    if (empty(env($var))) {
        $message = "ERROR: Required environment variable '$var' is not set!\n";
        error_log($message);
        if (php_sapi_name() === 'cli-server') {
            header('HTTP/1.0 500 Internal Server Error');
            echo $message;
            exit(1);
        }
    }
}

// Load .env file if it exists (for development)
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') === false || strpos($line, '#') === 0) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Remove quotes if present
        if (in_array($value[0] ?? null, ['"', "'"])) {
            $value = substr($value, 1, -1);
        }
        if (! isset($_ENV[$key]) && ! isset($_SERVER[$key])) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Ensure Laravel can find environment
define('LARAVEL_ENV_LOADED', true);
