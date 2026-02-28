<?php
/**
 * IP blocking functionality.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_IP_Blocker {

	public function init() {
		add_action( 'init', array( $this, 'check_blocked_ip' ), 1 );
	}

	public function check_blocked_ip() {
		if ( is_admin() && current_user_can( 'manage_options' ) ) {
			return;
		}

		$ip = $this->get_client_ip();
		if ( $this->is_blocked( $ip ) ) {
			$this->block_response();
		}
	}

	public function get_client_ip() {
		$keys = array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR' );
		foreach ( $keys as $key ) {
			if ( ! empty( $_SERVER[ $key ] ) ) {
				$ip = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );
				if ( strpos( $ip, ',' ) !== false ) {
					$ip = trim( explode( ',', $ip )[0] );
				}
				if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
					return $ip;
				}
			}
		}
		return '0.0.0.0';
	}

	public function is_blocked( $ip ) {
		global $wpdb;
		$table = WPSG_Constants::get_table_name( 'blocked_ips' );

		$result = $wpdb->get_row( $wpdb->prepare(
			"SELECT id FROM {$table} WHERE ip_address = %s 
			AND (blocked_until IS NULL OR blocked_until > %s)",
			$ip,
			current_time( 'mysql' )
		) );

		return ! empty( $result );
	}

	public function block_ip( $ip, $reason = '', $duration_minutes = null ) {
		global $wpdb;
		$table = WPSG_Constants::get_table_name( 'blocked_ips' );

		$blocked_until = null;
		if ( $duration_minutes ) {
			$blocked_until = gmdate( 'Y-m-d H:i:s', time() + ( $duration_minutes * 60 ) );
		}

		return $wpdb->insert(
			$table,
			array(
				'ip_address'   => $ip,
				'reason'       => $reason,
				'blocked_until'=> $blocked_until,
				'created_at'   => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%s', '%s' )
		);
	}

	public function unblock_ip( $ip ) {
		global $wpdb;
		$table = WPSG_Constants::get_table_name( 'blocked_ips' );
		return $wpdb->delete( $table, array( 'ip_address' => $ip ), array( '%s' ) );
	}

	private function block_response() {
		status_header( 403 );
		nocache_headers();
		wp_die( __( 'Erişim engellendi.', 'wp-security-guardian' ), __( 'Engellendi', 'wp-security-guardian' ), array( 'response' => 403 ) );
	}
}
