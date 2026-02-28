<?php
/**
 * WordPress core file integrity checker.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_File_Integrity {

	public function get_core_checksums() {
		global $wp_version;
		$url = "https://api.wordpress.org/core/checksums/1.0/?version={$wp_version}";

		$response = wp_remote_get( $url, array( 'timeout' => 30 ) );
		if ( is_wp_error( $response ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['checksums'] ) ) {
			return array();
		}

		return $data['checksums'];
	}

	public function scan_core_integrity( $scan_id ) {
		$checksums = $this->get_core_checksums();
		if ( empty( $checksums ) ) {
			return 0;
		}

		$found = 0;
		$abspath = trailingslashit( ABSPATH );

		foreach ( $checksums as $file => $expected_md5 ) {
			$file_path = $abspath . $file;
			if ( ! file_exists( $file_path ) ) {
				continue;
			}

			$actual_md5 = md5_file( $file_path );
			if ( $actual_md5 !== $expected_md5 ) {
				WPSG_Logger::log_scan_result(
					$scan_id,
					$file_path,
					'CORE_FILE_MODIFIED',
					'high',
					null,
					sprintf( 'Expected: %s, Got: %s', $expected_md5, $actual_md5 )
				);
				$found++;
			}
		}

		return $found;
	}

	public function get_core_files_count() {
		$checksums = $this->get_core_checksums();
		return is_array( $checksums ) ? count( $checksums ) : 0;
	}
}
