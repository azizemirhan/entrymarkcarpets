<?php
/**
 * Plugin Name: Next WP-Security
 * Plugin URI: https://www.nextwp.com.tr
 * Description: Next WP-Security - WordPress siteniz için kapsamlı güvenlik çözümü. Malware tarama, WAF, login koruması ve 2FA.
 * Version: 1.0.0
 * Author: Next WP
 * Author URI: https://www.nextwp.com.tr
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Text Domain: wp-security-guardian
 * Domain Path: /languages
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPSG_VERSION', '1.0.0' );
define( 'WPSG_FILE', __FILE__ );
define( 'WPSG_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPSG_URL', plugin_dir_url( __FILE__ ) );
define( 'WPSG_BASENAME', plugin_basename( __FILE__ ) );

if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-error"><p>';
		echo esc_html__( 'Next WP-Security requires PHP 7.4 or higher.', 'wp-security-guardian' );
		echo '</p></div>';
	} );
	return;
}

if ( version_compare( get_bloginfo( 'version' ), '5.8', '<' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-error"><p>';
		echo esc_html__( 'Next WP-Security requires WordPress 5.8 or higher.', 'wp-security-guardian' );
		echo '</p></div>';
	} );
	return;
}

if ( file_exists( WPSG_PATH . 'vendor/autoload.php' ) ) {
	require_once WPSG_PATH . 'vendor/autoload.php';
}

require_once WPSG_PATH . 'includes/class-wpsg-constants.php';
require_once WPSG_PATH . 'includes/class-wpsg-logger.php';
require_once WPSG_PATH . 'includes/class-wpsg-bootstrap.php';

register_activation_hook( __FILE__, array( 'WPSG_Bootstrap', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WPSG_Bootstrap', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'WPSG_Bootstrap', 'init' ) );
