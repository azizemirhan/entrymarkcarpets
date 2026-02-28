<?php
/**
 * Login protection - Brute force and hardening.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Login_Protection {

	public function init() {
		add_action( 'wp_login_failed', array( $this, 'log_failed_login' ), 10, 2 );
		add_action( 'wp_login', array( $this, 'log_successful_login' ), 10, 2 );
		add_filter( 'authenticate', array( $this, 'check_lockout' ), 30, 3 );
		add_action( 'login_init', array( $this, 'check_login_rate_limit' ) );
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

	public function log_failed_login( $username, $error ) {
		global $wpdb;
		$ip = $this->get_client_ip();

		$table = WPSG_Constants::get_table_name( 'login_logs' );
		$wpdb->insert(
			$table,
			array(
				'username'   => $username,
				'ip_address' => $ip,
				'success'    => 0,
				'created_at' => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%d', '%s' )
		);

		$this->check_and_block_ip( $ip );
	}

	public function log_successful_login( $username, $user ) {
		global $wpdb;
		$ip = $this->get_client_ip();
		delete_transient( 'wpsg_login_attempts_' . md5( $ip ) );

		$table = WPSG_Constants::get_table_name( 'login_logs' );
		$wpdb->insert(
			$table,
			array(
				'username'   => $username,
				'ip_address' => $ip,
				'success'    => 1,
				'created_at' => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%d', '%s' )
		);
	}

	private function get_failed_attempts_count( $ip ) {
		global $wpdb;
		$table = WPSG_Constants::get_table_name( 'login_logs' );

		$lockout_minutes = WPSG_Constants::LOCKOUT_DURATION / 60;
		$since = gmdate( 'Y-m-d H:i:s', time() - WPSG_Constants::LOCKOUT_DURATION );

		return (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$table} 
			WHERE ip_address = %s AND success = 0 AND created_at > %s",
			$ip,
			$since
		) );
	}

	private function check_and_block_ip( $ip ) {
		$failed = $this->get_failed_attempts_count( $ip );
		if ( $failed >= WPSG_Constants::MAX_LOGIN_ATTEMPTS ) {
			$blocker = new WPSG_IP_Blocker();
			$blocker->block_ip( $ip, 'Brute force - too many failed logins', WPSG_Constants::LOCKOUT_DURATION / 60 );
		}
	}

	public function check_lockout( $user, $username, $password ) {
		if ( is_wp_error( $user ) && $user->get_error_code() === 'invalid_username' ) {
			return $user;
		}

		$ip = $this->get_client_ip();
		$blocker = new WPSG_IP_Blocker();

		if ( $blocker->is_blocked( $ip ) ) {
			return new WP_Error(
				'wpsg_locked_out',
				__( 'Çok fazla başarısız giriş denemesi. Lütfen ' . ( WPSG_Constants::LOCKOUT_DURATION / 60 ) . ' dakika sonra tekrar deneyin.', 'wp-security-guardian' )
			);
		}

		return $user;
	}

	public function check_login_rate_limit() {
		$ip = $this->get_client_ip();
		$transient_key = 'wpsg_login_attempts_' . md5( $ip );

		$attempts = get_transient( $transient_key );
		if ( $attempts === false ) {
			set_transient( $transient_key, 1, 60 );
			return;
		}

		if ( $attempts >= 20 ) {
			$blocker = new WPSG_IP_Blocker();
			$blocker->block_ip( $ip, 'Login rate limit exceeded', 15 );
			wp_die(
				__( 'Çok fazla istek. Lütfen bir süre bekleyin.', 'wp-security-guardian' ),
				__( 'Erişim Engellendi', 'wp-security-guardian' ),
				array( 'response' => 429 )
			);
		}

		set_transient( $transient_key, $attempts + 1, 60 );
	}
}
