<?php
/**
 * Malware signature detection engine.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Signature_Engine {

	private $signatures = array();

	public function __construct() {
		$this->load_signatures();
	}

	private function load_signatures() {
		$path = WPSG_PATH . 'modules/scanner/signatures/malware-signatures.json';
		if ( ! file_exists( $path ) ) {
			return;
		}

		$content = file_get_contents( $path );
		$data    = json_decode( $content, true );

		if ( isset( $data['signatures'] ) && is_array( $data['signatures'] ) ) {
			$this->signatures = $data['signatures'];
		}
	}

	public function scan_content( $content, $file_path = '' ) {
		$results = array();
		$lines   = explode( "\n", $content );

		foreach ( $this->signatures as $signature ) {
			$pattern = $signature['pattern'];

			if ( ! empty( $signature['exclude_paths'] ) && ! empty( $file_path ) ) {
				$skip = false;
				foreach ( $signature['exclude_paths'] as $exclude ) {
					if ( strpos( $file_path, $exclude ) !== false ) {
						$skip = true;
						break;
					}
				}
				if ( $skip ) {
					continue;
				}
			}

			foreach ( $lines as $line_num => $line ) {
				$line_number = $line_num + 1;

				if ( preg_match( '/' . $pattern . '/', $line, $matches ) ) {
					$snippet = trim( $line );
					if ( strlen( $snippet ) > 200 ) {
						$snippet = substr( $snippet, 0, 200 ) . '...';
					}

					$results[] = array(
						'signature_id' => $signature['id'],
						'name'         => $signature['name'],
						'severity'     => $signature['severity'],
						'line_number'  => $line_number,
						'snippet'      => $snippet,
					);

					break;
				}
			}
		}

		return $results;
	}

	public function scan_file( $file_path ) {
		if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
			return array();
		}

		$content = file_get_contents( $file_path );
		return $this->scan_content( $content, $file_path );
	}

	public function get_signatures_count() {
		return count( $this->signatures );
	}
}
