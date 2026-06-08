<?php
/**
 * Custom env() helper untuk Railway
 * Override Laravel's default env() to ensure Railway environment variables are accessible
 */

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable
     * Checks getenv() first (Railway's method), then $_ENV, then $_SERVER
     */
    function env($key, $default = null)
    {
        // Try getenv() first - this is how Railway passes variables
        $value = getenv($key);
        if ($value !== false) {
            return value($default) === false ? $value : $value;
        }
        
        // Fallback to $_ENV
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        
        // Fallback to $_SERVER
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        
        // Return default
        return value($default);
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
