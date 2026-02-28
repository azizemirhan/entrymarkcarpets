<?php
/**
 * Web Application Firewall - Request filtering.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Firewall {

	private $patterns = array();

	public function __construct() {
		$this->init_patterns();
	}

	public function init() {
		add_action( 'muplugins_loaded', array( $this, 'check_request' ), 1 );
	}

	private function init_patterns() {
		$this->patterns = array(
			'sql_injection' => array(
				'union\s+select',
				'union\s+all\s+select',
				"'\s*or\s+'1'\s*=\s*'1",
				"'\s*or\s+1\s*=\s*1",
				';?\s*drop\s+table',
				'insert\s+into',
				'delete\s+from',
				'information_schema',
			),
			'xss' => array(
				'<script',
				'javascript:',
				'onerror\s*=',
				'onload\s*=',
				'onclick\s*=',
				'<iframe',
			),
			'path_traversal' => array(
				'\.\.\/',
				'\.\.\\\\',
			),
			'lfi_rfi' => array(
				'php://',
				'data://',
				'expect://',
				'phar://',
				'input',
			),
		);
	}

	public function check_request() {
		$options = get_option( 'wpsg_options', array() );
		if ( empty( $options['firewall_enabled'] ) ) {
			return;
		}

		$inputs = array_merge(
			$_GET,
			array( 'REQUEST_URI' => isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' )
		);
		$request_string = wp_json_encode( $inputs );

		foreach ( $this->patterns as $type => $patterns ) {
			foreach ( $patterns as $pattern ) {
				if ( preg_match( '/' . $pattern . '/i', $request_string ) ) {
					$this->block_request( $type, $pattern );
				}
			}
		}
	}

	private function block_request( $type, $matched ) {
		$ip_blocker = new WPSG_IP_Blocker();
		$ip = $ip_blocker->get_client_ip();
		$ip_blocker->block_ip( $ip, 'WAF: ' . $type . ' - ' . $matched, 60 );
		$this->deny_response();
	}

	private function deny_response() {
		status_header( 403 );
		nocache_headers();
		wp_die( __( 'Şüpheli istek tespit edildi. Erişim engellendi.', 'wp-security-guardian' ), __( 'Erişim Engellendi', 'wp-security-guardian' ), array( 'response' => 403 ) );
	}
}
