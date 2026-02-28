<?php
/**
 * Admin interface for Security Guardian.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_head', array( $this, 'menu_icon_styles' ) );
	}

	public function menu_icon_styles() {
		echo '<style>
		#adminmenu .toplevel_page_wpsg-dashboard .wp-menu-image img {
			width: 20px; height: 20px; padding: 6px 0; opacity: 0.9; filter: none;
		}
		#adminmenu .toplevel_page_wpsg-dashboard:hover .wp-menu-image img,
		#adminmenu .toplevel_page_wpsg-dashboard.wp-has-current-submenu .wp-menu-image img {
			opacity: 1;
		}
		</style>';
	}

	public function register_settings() {
		register_setting( 'wpsg_options', 'wpsg_options', array(
			'type'              => 'array',
			'sanitize_callback' => array( $this, 'sanitize_options' ),
		) );
	}

	public function sanitize_options( $input ) {
		$sanitized = array();
		if ( ! empty( $input['auto_scan'] ) ) {
			$sanitized['auto_scan'] = 1;
		}
		if ( ! empty( $input['firewall_enabled'] ) ) {
			$sanitized['firewall_enabled'] = 1;
		}
		return $sanitized;
	}

	public function add_menu() {
		add_menu_page(
			__( 'Next WP-Security', 'wp-security-guardian' ),
			__( 'Next WP-Security', 'wp-security-guardian' ),
			'manage_options',
			'wpsg-dashboard',
			array( $this, 'render_dashboard' ),
			WPSG_URL . 'admin/assets/images/icon-next-security.png',
			80
		);

		add_submenu_page(
			'wpsg-dashboard',
			__( 'Tarama', 'wp-security-guardian' ),
			__( 'Tarama', 'wp-security-guardian' ),
			'manage_options',
			'wpsg-scan',
			array( $this, 'render_scan_page' )
		);

		add_submenu_page(
			'wpsg-dashboard',
			__( 'Ayarlar', 'wp-security-guardian' ),
			__( 'Ayarlar', 'wp-security-guardian' ),
			'manage_options',
			'wpsg-settings',
			array( $this, 'render_settings_page' )
		);
	}

	public function enqueue_assets( $hook ) {
		if ( strpos( $hook, 'wpsg-' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'wpsg-admin',
			WPSG_URL . 'admin/assets/css/admin.css',
			array(),
			WPSG_VERSION
		);

		if ( strpos( $hook, 'wpsg-scan' ) !== false ) {
			wp_enqueue_script(
				'wpsg-scan',
				WPSG_URL . 'admin/assets/js/scan.js',
				array( 'jquery' ),
				WPSG_VERSION,
				true
			);

			wp_localize_script( 'wpsg-scan', 'wpsgScan', array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wpsg_scan' ),
				'i18n'    => array(
					'scanning'     => __( 'Taranıyor...', 'wp-security-guardian' ),
					'complete'     => __( 'Tarama tamamlandı', 'wp-security-guardian' ),
					'error'        => __( 'Bir hata oluştu', 'wp-security-guardian' ),
					'startScan'    => __( 'Tarama Başlat', 'wp-security-guardian' ),
					'threatsFound' => __( 'Tehdit bulundu', 'wp-security-guardian' ),
					'noThreats'    => __( 'Tehdit bulunamadı', 'wp-security-guardian' ),
				),
			) );
		}
	}

	public function render_dashboard() {
		require_once WPSG_PATH . 'admin/views/dashboard.php';
	}

	public function render_scan_page() {
		require_once WPSG_PATH . 'admin/views/scan.php';
	}

	public function render_settings_page() {
		require_once WPSG_PATH . 'admin/views/settings.php';
	}
}
