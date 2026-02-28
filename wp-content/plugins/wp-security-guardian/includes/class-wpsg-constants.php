<?php
/**
 * Plugin constants and configuration.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Constants {

	const OPTION_GROUP = 'wpsg_options';
	const DB_VERSION = 1;
	const SCAN_CHUNK_SIZE = 50;
	const MAX_LOGIN_ATTEMPTS = 5;
	const LOCKOUT_DURATION = 900;
	const RATE_LIMIT_REQUESTS = 60;
	const RATE_LIMIT_PERIOD = 60;

	public static function get_excluded_dirs() {
		return array(
			'node_modules',
			'.git',
			'.svn',
			'vendor',
			'wp-security-guardian',
		);
	}

	public static function get_scan_dirs() {
		$content_dir = WP_CONTENT_DIR;
		$scan_dirs   = array(
			$content_dir . '/themes',
			$content_dir . '/plugins',
		);

		if ( defined( 'ABSPATH' ) ) {
			$scan_dirs[] = ABSPATH . 'wp-includes';
		}

		return $scan_dirs;
	}

	public static function get_table_name( $table ) {
		global $wpdb;
		return $wpdb->prefix . 'wpsg_' . $table;
	}
}
