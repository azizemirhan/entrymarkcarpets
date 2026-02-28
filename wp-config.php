<?php
/**
 * The base configuration for WordPress
 *
 * @package WordPress
 */

// ** Load environment variables from .env file ** //
function load_env($file) {
    if (!file_exists($file)) {
        return false;
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue; // Skip comments
        if (strpos($line, '=') === false) continue; // Skip invalid lines
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        if (!empty($key)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
    return true;
}

// Load .env file
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    load_env($env_file);
}

// ** Database settings ** //
define( 'DB_NAME', getenv('DB_NAME') ?: 'wordpress' );
define( 'DB_USER', getenv('DB_USER') ?: 'wordpress' );
define( 'DB_PASSWORD', getenv('DB_PASSWORD') ?: 'wordpress' );
define( 'DB_HOST', getenv('DB_HOST') ?: 'db:3306' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 */
define( 'AUTH_KEY',         getenv('AUTH_KEY') ?: 'unique_key_here_change_in_production' );
define( 'SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY') ?: 'unique_key_here_change_in_production' );
define( 'LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY') ?: 'unique_key_here_change_in_production' );
define( 'NONCE_KEY',        getenv('NONCE_KEY') ?: 'unique_key_here_change_in_production' );
define( 'AUTH_SALT',        getenv('AUTH_SALT') ?: 'unique_key_here_change_in_production' );
define( 'SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT') ?: 'unique_key_here_change_in_production' );
define( 'LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT') ?: 'unique_key_here_change_in_production' );
define( 'NONCE_SALT',       getenv('NONCE_SALT') ?: 'unique_key_here_change_in_production' );

/**#@-*/

/**
 * WordPress database table prefix.
 */
$table_prefix = 'wp_';

/**
 * Debug mode - ENABLED for development
 */
define( 'WP_DEBUG', filter_var(getenv('WP_DEBUG') ?: 'true', FILTER_VALIDATE_BOOLEAN) );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );

/* Add any custom values between this line and the "stop editing" line. */
define('FS_METHOD', 'direct');

// Force theme - uncomment if needed
// define('WP_DEFAULT_THEME', 'nextcore');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
