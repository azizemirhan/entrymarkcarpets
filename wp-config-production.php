<?php
/**
 * Production configuration for WordPress
 * Domain: demo.entrymarkcarpets.com
 *
 * Kullanım: Sunucuda bu dosyayı wp-config.php olarak kopyalayın veya içeriğini wp-config.php'ye taşıyın.
 *
 * @package WordPress
 */

// ** Veritabanı ayarları ** //
define( 'DB_NAME', 'dbtrkqwoviu5dg' );
define( 'DB_USER', 'umwg4mqb6fen2' );
define( 'DB_PASSWORD', 'otxh9bxvxxbz' );
define( 'DB_HOST', 'localhost' );  // Hosting sağlayıcınız farklı bir host verirse (örn. mysql.xxx.com) burayı güncelleyin
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**
 * Authentication unique keys and salts.
 * Aşağıdaki değerleri https://api.wordpress.org/secret-key/1.1/salt/ adresinden alıp güncelleyin.
 */
define( 'AUTH_KEY',         'production-key-buraya-ekleyin-api-wordpress-org-secret-key' );
define( 'SECURE_AUTH_KEY',  'production-key-buraya-ekleyin-api-wordpress-org-secret-key' );
define( 'LOGGED_IN_KEY',    'production-key-buraya-ekleyin-api-wordpress-org-secret-key' );
define( 'NONCE_KEY',        'production-key-buraya-ekleyin-api-wordpress-org-secret-key' );
define( 'AUTH_SALT',        'production-salt-buraya-ekleyin-api-wordpress-org-secret-key' );
define( 'SECURE_AUTH_SALT', 'production-salt-buraya-ekleyin-api-wordpress-org-secret-key' );
define( 'LOGGED_IN_SALT',   'production-salt-buraya-ekleyin-api-wordpress-org-secret-key' );
define( 'NONCE_SALT',       'production-salt-buraya-ekleyin-api-wordpress-org-secret-key' );

/**#@-*/

/**
 * WordPress database table prefix.
 */
$table_prefix = 'wp_';

/**
 * Site URL (Production)
 */
define( 'WP_HOME', 'https://demo.entrymarkcarpets.com' );
define( 'WP_SITEURL', 'https://demo.entrymarkcarpets.com' );

/**
 * Production: Debug kapalı
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

/**
 * Dosya yükleme yöntemi
 */
define( 'FS_METHOD', 'direct' );

/**
 * SSL / Admin için güvenlik (proxy/load balancer kullanıyorsanız gerekebilir)
 * define( 'FORCE_SSL_ADMIN', true );
 */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
