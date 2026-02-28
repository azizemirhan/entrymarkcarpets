#!/bin/bash
set -e

# wp-config.php yoksa ortam değişkenlerinden oluştur
if [ ! -f /var/www/html/wp-config.php ]; then
    cat > /var/www/html/wp-config.php << 'WPCONFIG'
<?php
/**
 * Docker ortamı için wp-config - ortam değişkenlerinden oluşturuldu.
 */
define( 'DB_NAME', getenv('DB_NAME') ?: 'wordpress' );
define( 'DB_USER', getenv('DB_USER') ?: 'wordpress' );
define( 'DB_PASSWORD', getenv('DB_PASSWORD') ?: 'wordpress' );
define( 'DB_HOST', getenv('DB_HOST') ?: 'db' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/** FTP sormasın, doğrudan dosya yazsın (Docker / yerel geliştirme) */
define( 'FS_METHOD', 'direct' );

define( 'AUTH_KEY',         getenv('AUTH_KEY')         ?: 'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY')  ?: 'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY')    ?: 'put your unique phrase here' );
define( 'NONCE_KEY',        getenv('NONCE_KEY')        ?: 'put your unique phrase here' );
define( 'AUTH_SALT',        getenv('AUTH_SALT')        ?: 'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT') ?: 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT')   ?: 'put your unique phrase here' );
define( 'NONCE_SALT',       getenv('NONCE_SALT')       ?: 'put your unique phrase here' );

$table_prefix = getenv('TABLE_PREFIX') ?: 'wp_';

define( 'WP_DEBUG', filter_var( getenv('WP_DEBUG'), FILTER_VALIDATE_BOOLEAN ) ?: false );

$wp_url = getenv('WP_URL') ?: 'http://localhost:9080';
define( 'WP_HOME', $wp_url );
define( 'WP_SITEURL', $wp_url );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
WPCONFIG
fi

exec "$@"
