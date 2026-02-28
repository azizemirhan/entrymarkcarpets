<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap wpsg-scan-page">
	<div class="wpsg-header">
		<img src="<?php echo esc_url( WPSG_URL . 'admin/assets/images/logo.png' ); ?>" alt="Next WP" class="wpsg-header-logo" />
		<div>
			<h1><span class="wpsg-brand">Next</span> WP-Security</h1>
			<p class="wpsg-header-tagline"><?php esc_html_e( 'Malware Tarama ve Güvenlik Analizi', 'wp-security-guardian' ); ?></p>
		</div>
	</div>

	<div class="wpsg-scan-intro">
		<p><?php esc_html_e( 'Sitenizi kapsamlı bir güvenlik taramasından geçirin. Zararlı yazılımları, virüsleri ve şüpheli kodları tespit edin.', 'wp-security-guardian' ); ?></p>
	</div>

	<div class="wpsg-scan-controls">
		<button type="button" id="wpsg-start-scan" class="button button-primary button-hero">
			<span class="wpsg-btn-icon">🔍</span>
			<?php esc_html_e( 'Tarama Başlat', 'wp-security-guardian' ); ?>
		</button>
	</div>

	<div id="wpsg-scan-progress" class="wpsg-scan-progress" style="display: none;">
		<div class="wpsg-progress-header">
			<span class="wpsg-progress-icon">🔄</span>
			<span class="wpsg-progress-title"><?php esc_html_e( 'Tarama Devam Ediyor', 'wp-security-guardian' ); ?></span>
		</div>
		<div class="wpsg-progress-bar">
			<div class="wpsg-progress-fill" style="width: 0%;"></div>
		</div>
		<p class="wpsg-progress-text">
			<span class="wpsg-status"><?php esc_html_e( 'Hazırlanıyor...', 'wp-security-guardian' ); ?></span>
			<span class="wpsg-counts"></span>
		</p>
	</div>

	<div id="wpsg-scan-results" class="wpsg-scan-results" style="display: none;">
		<div class="wpsg-results-header">
			<h2><?php esc_html_e( 'Tarama Sonuçları', 'wp-security-guardian' ); ?></h2>
			<span class="wpsg-results-badge"></span>
		</div>
		<div class="wpsg-results-summary"></div>
		<table class="wp-list-table widefat fixed striped" id="wpsg-results-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Dosya', 'wp-security-guardian' ); ?></th>
					<th><?php esc_html_e( 'Tehdit', 'wp-security-guardian' ); ?></th>
					<th><?php esc_html_e( 'Önem', 'wp-security-guardian' ); ?></th>
					<th><?php esc_html_e( 'Satır', 'wp-security-guardian' ); ?></th>
					<th><?php esc_html_e( 'Önizleme', 'wp-security-guardian' ); ?></th>
					<th><?php esc_html_e( 'İşlem', 'wp-security-guardian' ); ?></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

	<div id="wpsg-scan-empty" class="wpsg-scan-empty" style="display: none;">
		<p class="wpsg-success-message"><?php esc_html_e( 'Harika! Tehdit bulunamadı. Siteniz temiz görünüyor.', 'wp-security-guardian' ); ?></p>
		<p class="wpsg-success-subtitle"><?php esc_html_e( 'Düzenli taramalarla güvenliği koruyun.', 'wp-security-guardian' ); ?></p>
	</div>
</div>
