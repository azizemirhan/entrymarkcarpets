<?php
/**
 * Plugin bootstrap and initialization.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Bootstrap {

	public static function init() {
		load_plugin_textdomain( 'wp-security-guardian', false, dirname( WPSG_BASENAME ) . '/languages' );
		self::load_modules();
		self::load_admin();
	}

	public static function activate() {
		self::create_tables();
		update_option( 'wpsg_db_version', WPSG_Constants::DB_VERSION );
		flush_rewrite_rules();
	}

	public static function deactivate() {
		flush_rewrite_rules();
	}

	private static function create_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$prefix          = $wpdb->prefix . 'wpsg_';

		$sql = "CREATE TABLE {$prefix}scan_results (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			scan_id varchar(32) NOT NULL,
			file_path varchar(500) NOT NULL,
			signature_id varchar(50) NOT NULL,
			severity varchar(20) NOT NULL,
			line_number int(11) DEFAULT NULL,
			snippet text,
			status varchar(20) DEFAULT 'pending',
			created_at datetime DEFAULT NULL,
			PRIMARY KEY (id),
			KEY scan_id (scan_id),
			KEY severity (severity)
		) $charset_collate;

		CREATE TABLE {$prefix}blocked_ips (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			ip_address varchar(45) NOT NULL,
			reason varchar(100) DEFAULT NULL,
			blocked_until datetime DEFAULT NULL,
			created_at datetime DEFAULT NULL,
			PRIMARY KEY (id),
			KEY ip_address (ip_address)
		) $charset_collate;

		CREATE TABLE {$prefix}login_logs (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			username varchar(60) DEFAULT NULL,
			ip_address varchar(45) NOT NULL,
			success tinyint(1) NOT NULL DEFAULT 0,
			created_at datetime DEFAULT NULL,
			PRIMARY KEY (id),
			KEY ip_address (ip_address),
			KEY created_at (created_at)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	private static function load_modules() {
		require_once WPSG_PATH . 'modules/scanner/class-wpsg-signature-engine.php';
		require_once WPSG_PATH . 'modules/scanner/class-wpsg-file-integrity.php';
		require_once WPSG_PATH . 'modules/scanner/class-wpsg-scanner.php';
		require_once WPSG_PATH . 'modules/firewall/class-wpsg-ip-blocker.php';
		require_once WPSG_PATH . 'modules/firewall/class-wpsg-firewall.php';
		require_once WPSG_PATH . 'modules/login/class-wpsg-login-protection.php';
		require_once WPSG_PATH . 'modules/twofa/class-wpsg-two-factor.php';
		require_once WPSG_PATH . 'modules/autofix/class-wpsg-autofix.php';

		$scanner = new WPSG_Scanner();
		$scanner->init();

		$ip_blocker = new WPSG_IP_Blocker();
		$ip_blocker->init();

		$firewall = new WPSG_Firewall();
		$firewall->init();

		$login_protection = new WPSG_Login_Protection();
		$login_protection->init();

		$twofa = new WPSG_Two_Factor();
		$twofa->init();

		$autofix = new WPSG_Autofix();
		$autofix->init();
	}

	private static function load_admin() {
		if ( is_admin() ) {
			require_once WPSG_PATH . 'admin/class-wpsg-admin.php';
			new WPSG_Admin();
		}
	}
}
