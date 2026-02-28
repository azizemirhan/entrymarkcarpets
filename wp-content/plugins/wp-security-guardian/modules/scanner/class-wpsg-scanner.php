<?php
/**
 * File scanner for malware detection.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Scanner {

	private $signature_engine;
	private $file_integrity;
	private $scan_id;

	public function __construct() {
		$this->signature_engine = new WPSG_Signature_Engine();
		$this->file_integrity  = new WPSG_File_Integrity();
	}

	public function init() {
		add_action( 'wp_ajax_wpsg_start_scan', array( $this, 'ajax_start_scan' ) );
		add_action( 'wp_ajax_wpsg_scan_chunk', array( $this, 'ajax_scan_chunk' ) );
		add_action( 'wp_ajax_wpsg_get_scan_results', array( $this, 'ajax_get_scan_results' ) );
	}

	public function get_php_files( $offset = 0, $limit = null ) {
		$limit  = $limit ?: WPSG_Constants::SCAN_CHUNK_SIZE;
		$files  = array();
		$dirs   = WPSG_Constants::get_scan_dirs();
		$exclude = WPSG_Constants::get_excluded_dirs();

		foreach ( $dirs as $dir ) {
			if ( ! is_dir( $dir ) ) {
				continue;
			}
			$this->collect_php_files( $dir, $files, $exclude );
		}

		$root_php = $this->get_root_php_files();
		$files    = array_merge( $files, $root_php );
		$files    = array_unique( $files );
		$files    = array_values( $files );

		return array_slice( $files, $offset, $limit );
	}

	public function get_total_file_count() {
		$files  = array();
		$dirs   = WPSG_Constants::get_scan_dirs();
		$exclude = WPSG_Constants::get_excluded_dirs();

		foreach ( $dirs as $dir ) {
			if ( ! is_dir( $dir ) ) {
				continue;
			}
			$this->collect_php_files( $dir, $files, $exclude );
		}

		$root_php = $this->get_root_php_files();
		$files    = array_merge( $files, $root_php );
		$files    = array_unique( $files );

		return count( $files );
	}

	private function collect_php_files( $dir, &$files, $exclude ) {
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ( $iterator as $file ) {
			if ( ! $file->isFile() || $file->getExtension() !== 'php' ) {
				continue;
			}

			$path = $file->getPathname();
			$skip = false;

			foreach ( $exclude as $excluded ) {
				if ( strpos( $path, $excluded ) !== false ) {
					$skip = true;
					break;
				}
			}

			if ( ! $skip ) {
				$files[] = $path;
			}
		}
	}

	private function get_root_php_files() {
		$files = array();
		if ( ! defined( 'ABSPATH' ) ) {
			return $files;
		}

		$root_files = array( 'index.php', 'wp-login.php', 'wp-cron.php', 'wp-blog-header.php', 'wp-load.php', 'xmlrpc.php' );
		foreach ( $root_files as $file ) {
			$path = ABSPATH . $file;
			if ( file_exists( $path ) ) {
				$files[] = $path;
			}
		}

		return $files;
	}

	public function scan_database( $scan_id ) {
		global $wpdb;
		$found = 0;

		$options_table = $wpdb->prefix . 'options';
		$suspicious_options = $wpdb->get_results(
			"SELECT option_id, option_name, option_value FROM {$options_table} 
			WHERE autoload = 'yes' 
			AND (option_value LIKE '%base64_decode%eval%' OR option_value LIKE '%eval%base64_decode%')
			AND option_name NOT IN ('cron', 'rewrite_rules')",
			ARRAY_A
		);

		foreach ( $suspicious_options as $row ) {
			WPSG_Logger::log_scan_result(
				$scan_id,
				'db:wp_options:' . $row['option_name'],
				'DB_SUSPICIOUS_OPTION',
				'high',
				null,
				substr( $row['option_value'], 0, 200 )
			);
			$found++;
		}

		return $found;
	}

	public function scan_files( $files, $scan_id = null ) {
		if ( ! $scan_id ) {
			$scan_id = $this->generate_scan_id();
		}
		$this->scan_id = $scan_id;

		$found = 0;
		foreach ( $files as $file_path ) {
			$results = $this->signature_engine->scan_file( $file_path );
			foreach ( $results as $result ) {
				WPSG_Logger::log_scan_result(
					$scan_id,
					$file_path,
					$result['signature_id'],
					$result['severity'],
					$result['line_number'],
					$result['snippet']
				);
				$found++;
			}
		}

		return array(
			'scan_id' => $scan_id,
			'files_scanned' => count( $files ),
			'threats_found' => $found,
		);
	}

	private function generate_scan_id() {
		return md5( uniqid( (string) wp_rand(), true ) );
	}

	public function ajax_start_scan() {
		check_ajax_referer( 'wpsg_scan', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		$scan_id      = $this->generate_scan_id();
		$total        = $this->get_total_file_count();
		$first_batch  = $this->get_php_files( 0, WPSG_Constants::SCAN_CHUNK_SIZE );

		$result          = $this->scan_files( $first_batch, $scan_id );
		$db_threats      = $this->scan_database( $scan_id );
		$core_threats    = $this->file_integrity->scan_core_integrity( $scan_id );
		$total_threats   = $result['threats_found'] + $db_threats + $core_threats;

		update_option( 'wpsg_last_scan', array(
			'scan_id' => $scan_id,
			'started_at' => current_time( 'mysql' ),
		), false );

		wp_send_json_success( array(
			'scan_id'       => $scan_id,
			'total_files'   => $total,
			'scanned'       => count( $first_batch ),
			'threats_found' => $total_threats,
			'has_more'      => $total > count( $first_batch ),
		) );
	}

	public function ajax_scan_chunk() {
		check_ajax_referer( 'wpsg_scan', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		$scan_id = isset( $_POST['scan_id'] ) ? sanitize_text_field( $_POST['scan_id'] ) : '';
		$offset  = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;

		if ( empty( $scan_id ) ) {
			wp_send_json_error( array( 'message' => 'Invalid scan ID' ) );
		}

		$files = $this->get_php_files( $offset, WPSG_Constants::SCAN_CHUNK_SIZE );
		$total = $this->get_total_file_count();

		$result = $this->scan_files( $files, $scan_id );

		$new_offset = $offset + count( $files );

		$has_more = $new_offset < $total;
		if ( ! $has_more ) {
			$total_threats = $this->get_scan_threat_count( $scan_id );
			$last_scan = get_option( 'wpsg_last_scan', array() );
			$last_scan['completed_at'] = current_time( 'mysql' );
			$last_scan['threats'] = $total_threats;
			update_option( 'wpsg_last_scan', $last_scan, false );
		}

		wp_send_json_success( array(
			'scan_id'       => $scan_id,
			'offset'        => $new_offset,
			'scanned'       => count( $files ),
			'threats_found' => $result['threats_found'],
			'has_more'      => $has_more,
			'total_files'   => $total,
		) );
	}

	private function get_scan_threat_count( $scan_id ) {
		global $wpdb;
		$table = WPSG_Constants::get_table_name( 'scan_results' );
		return (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$table} WHERE scan_id = %s",
			$scan_id
		) );
	}

	public function ajax_get_scan_results() {
		check_ajax_referer( 'wpsg_scan', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		$scan_id = isset( $_GET['scan_id'] ) ? sanitize_text_field( $_GET['scan_id'] ) : '';
		if ( empty( $scan_id ) ) {
			wp_send_json_error( array( 'message' => 'Invalid scan ID' ) );
		}

		global $wpdb;
		$table  = WPSG_Constants::get_table_name( 'scan_results' );
		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$table} WHERE scan_id = %s ORDER BY severity DESC, id ASC",
			$scan_id
		), ARRAY_A );

		wp_send_json_success( array( 'results' => $results ) );
	}

	public function get_scan_results( $scan_id ) {
		global $wpdb;
		$table = WPSG_Constants::get_table_name( 'scan_results' );
		return $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$table} WHERE scan_id = %s ORDER BY severity DESC",
			$scan_id
		), ARRAY_A );
	}
}
