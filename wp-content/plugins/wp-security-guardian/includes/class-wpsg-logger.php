<?php
/**
 * Logging utility for Security Guardian.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Logger {

	public static function log( $message, $context = array() ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$log_message = '[WPSG] ' . $message;
			if ( ! empty( $context ) ) {
				$log_message .= ' ' . wp_json_encode( $context );
			}
			error_log( $log_message );
		}
	}

	public static function log_scan_result( $scan_id, $file_path, $signature_id, $severity, $line_number, $snippet ) {
		global $wpdb;
		$table = WPSG_Constants::get_table_name( 'scan_results' );

		$wpdb->insert(
			$table,
			array(
				'scan_id'      => $scan_id,
				'file_path'    => $file_path,
				'signature_id' => $signature_id,
				'severity'     => $severity,
				'line_number'  => $line_number,
				'snippet'      => $snippet,
				'status'       => 'pending',
				'created_at'   => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s' )
		);
	}
}
