<?php
/**
 * Load environment variables from .env file
 */

function loadEnv($filePath = __DIR__ . '/.env') {
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse the line
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
                (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
                $value = substr($value, 1, -1);
            }
            
            // Set as environment variable and in $_ENV
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// Load the environment file
loadEnv();

// Helper function to get environment variables
function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

// Helper function to get the current view, normalized for different environments
function getCurrentView() {
    $view = $_GET['view'] ?? '';
    
    // Get the base path from APP_URL
    $baseUrl = env('APP_URL', '');
    $basePath = parse_url($baseUrl, PHP_URL_PATH);
    $basePath = trim($basePath, '/');
    
    // Remove the base path if it exists in the view
    if ($basePath && strpos($view, $basePath) === 0) {
        $view = substr($view, strlen($basePath) + 1); // +1 for the slash
    }
    
    // Remove leading and trailing slashes
    $view = trim($view, '/');
    
    return $view;
}
