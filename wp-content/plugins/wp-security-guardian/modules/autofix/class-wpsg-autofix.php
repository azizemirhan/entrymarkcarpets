<?php
/**
 * Auto-fix and quarantine for infected files.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Autofix {

	private $quarantine_dir;

	public function __construct() {
		$upload_dir = wp_upload_dir();
		$this->quarantine_dir = $upload_dir['basedir'] . '/wpsg-quarantine';
	}

	public function init() {
		add_action( 'wp_ajax_wpsg_quarantine_file', array( $this, 'ajax_quarantine' ) );
		add_action( 'wp_ajax_wpsg_restore_file', array( $this, 'ajax_restore' ) );
		add_action( 'wp_ajax_wpsg_restore_core', array( $this, 'ajax_restore_core' ) );
	}

	public function quarantine_file( $file_path ) {
		if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
			return new WP_Error( 'file_not_found', __( 'Dosya bulunamadı.', 'wp-security-guardian' ) );
		}

		if ( strpos( realpath( $file_path ), realpath( ABSPATH ) ) !== 0 ) {
			return new WP_Error( 'invalid_path', __( 'Geçersiz dosya yolu.', 'wp-security-guardian' ) );
		}

		$this->ensure_quarantine_dir();

		$filename = basename( $file_path );
		$rel_path = str_replace( ABSPATH, '', $file_path );
		$safe_name = str_replace( array( '/', '\\' ), '_', $rel_path );
		$quarantine_path = $this->quarantine_dir . '/' . $safe_name . '.' . time();

		if ( ! copy( $file_path, $quarantine_path ) ) {
			return new WP_Error( 'copy_failed', __( 'Dosya kopyalanamadı.', 'wp-security-guardian' ) );
		}

		$metadata = array(
			'original_path' => $file_path,
			'quarantined_at' => current_time( 'mysql' ),
		);
		file_put_contents( $quarantine_path . '.meta.json', wp_json_encode( $metadata ) );

		if ( ! unlink( $file_path ) ) {
			return new WP_Error( 'delete_failed', __( 'Orijinal dosya silinemedi.', 'wp-security-guardian' ) );
		}

		update_option( 'wpsg_quarantine_' . md5( $file_path ), $quarantine_path, false );

		return true;
	}

	public function restore_file( $quarantine_path ) {
		$meta_file = $quarantine_path . '.meta.json';
		if ( ! file_exists( $meta_file ) ) {
			return new WP_Error( 'meta_not_found', __( 'Meta dosyası bulunamadı.', 'wp-security-guardian' ) );
		}

		$metadata = json_decode( file_get_contents( $meta_file ), true );
		$original = $metadata['original_path'] ?? '';

		if ( empty( $original ) || ! file_exists( $quarantine_path ) ) {
			return new WP_Error( 'invalid_restore', __( 'Geri yükleme başarısız.', 'wp-security-guardian' ) );
		}

		$dir = dirname( $original );
		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		if ( ! copy( $quarantine_path, $original ) ) {
			return new WP_Error( 'restore_failed', __( 'Dosya geri yüklenemedi.', 'wp-security-guardian' ) );
		}

		unlink( $quarantine_path );
		unlink( $meta_file );

		return true;
	}

	public function restore_core_file( $relative_path ) {
		global $wp_version;
		$url = "https://downloads.wordpress.org/release/wordpress-{$wp_version}.zip";

		$tmp = download_url( $url );
		if ( is_wp_error( $tmp ) ) {
			return $tmp;
		}

		$zip = new ZipArchive();
		if ( $zip->open( $tmp ) !== true ) {
			@unlink( $tmp );
			return new WP_Error( 'zip_open', __( 'Zip açılamadı.', 'wp-security-guardian' ) );
		}

		$zip_path = 'wordpress/' . ltrim( $relative_path, '/' );
		$content = $zip->getFromName( $zip_path );
		$zip->close();
		@unlink( $tmp );

		if ( $content === false ) {
			return new WP_Error( 'file_not_in_zip', __( 'Dosya zip içinde bulunamadı.', 'wp-security-guardian' ) );
		}

		$target = ABSPATH . $relative_path;
		$dir = dirname( $target );
		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		if ( file_put_contents( $target, $content ) === false ) {
			return new WP_Error( 'write_failed', __( 'Dosya yazılamadı.', 'wp-security-guardian' ) );
		}

		return true;
	}

	public function ajax_quarantine() {
		check_ajax_referer( 'wpsg_scan', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		$file_path = isset( $_POST['file_path'] ) ? sanitize_text_field( wp_unslash( $_POST['file_path'] ) ) : '';
		if ( empty( $file_path ) || ! file_exists( $file_path ) ) {
			wp_send_json_error( array( 'message' => __( 'Geçersiz dosya.', 'wp-security-guardian' ) ) );
		}

		$result = $this->quarantine_file( $file_path );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success();
	}

	public function ajax_restore() {
		check_ajax_referer( 'wpsg_scan', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		$quarantine_path = isset( $_POST['quarantine_path'] ) ? sanitize_text_field( wp_unslash( $_POST['quarantine_path'] ) ) : '';
		if ( empty( $quarantine_path ) ) {
			wp_send_json_error( array( 'message' => __( 'Geçersiz yol.', 'wp-security-guardian' ) ) );
		}

		$result = $this->restore_file( $quarantine_path );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success();
	}

	public function ajax_restore_core() {
		check_ajax_referer( 'wpsg_scan', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		$file_path = isset( $_POST['file_path'] ) ? sanitize_text_field( wp_unslash( $_POST['file_path'] ) ) : '';
		$relative_path = ltrim( str_replace( array( ABSPATH, '\\' ), array( '', '/' ), $file_path ), '/' );

		if ( empty( $relative_path ) || strpos( $relative_path, '..' ) !== false ) {
			wp_send_json_error( array( 'message' => __( 'Geçersiz dosya yolu.', 'wp-security-guardian' ) ) );
		}

		$result = $this->restore_core_file( $relative_path );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success();
	}

	private function ensure_quarantine_dir() {
		if ( ! is_dir( $this->quarantine_dir ) ) {
			wp_mkdir_p( $this->quarantine_dir );
			$htaccess = $this->quarantine_dir . '/.htaccess';
			file_put_contents( $htaccess, "Require all denied\nDeny from all" );
		}
	}

	public function get_quarantine_dir() {
		return $this->quarantine_dir;
	}
}
