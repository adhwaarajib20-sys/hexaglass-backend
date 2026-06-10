<?php
/**
 * Load environment variables for Railway deployment
 * Railway passes environment variables as $_ENV and getenv()
 */

// First, load from .env file if it exists (development)
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        // Remove quotes if present
        if (! empty($value) && in_array($value[0], ['"', "'"])) {
            $value = substr($value, 1, -1);
        }
        
        // Only set if not already in environment
        if (empty(getenv($key))) {
            putenv("$key=$value");
        }
    }
}

// Load from Railway environment (getenv() is the standard way)
// Railway automatically populates $_SERVER and getenv()
$appKey = getenv('APP_KEY') ?: ($_SERVER['APP_KEY'] ?? ($_ENV['APP_KEY'] ?? null));

// If still no APP_KEY, error
if (empty($appKey)) {
    $message = "FATAL: APP_KEY environment variable not set!\n";
    $message .= "Make sure APP_KEY is set in Railway Variables.\n";
    
    error_log($message);
    
    // Log available environment variables for debugging
    error_log("DEBUG: Available environment variables:");
    $keys = array_keys(getenv());
    $important_keys = array_filter($keys, function($k) {
        return strpos($k, 'APP_') === 0 || strpos($k, 'DB_') === 0;
    });
    error_log("Available APP_* and DB_* vars: " . implode(', ', $important_keys));
    
    if (php_sapi_name() === 'cli-server') {
        header('HTTP/1.0 500 Internal Server Error');
        echo $message;
        exit(1);
    }
}

// Optional: Verify database variables if production
if (getenv('APP_ENV') === 'production') {
    $required = ['APP_KEY', 'DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE'];
    $missing = [];
    
    foreach ($required as $var) {
        if (empty(getenv($var))) {
            $missing[] = $var;
        }
    }
    
    if (! empty($missing)) {
        error_log("WARNING: Missing production environment variables: " . implode(', ', $missing));
    }
}

// Mark as loaded
define('LARAVEL_ENV_LOADED', true);
