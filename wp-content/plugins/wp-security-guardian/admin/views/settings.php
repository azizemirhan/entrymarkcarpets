<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options = get_option( 'wpsg_options', array() );
?>
<div class="wrap wpsg-settings-page">
	<div class="wpsg-header">
		<img src="<?php echo esc_url( WPSG_URL . 'admin/assets/images/logo.png' ); ?>" alt="Next WP" class="wpsg-header-logo" />
		<div>
			<h1><span class="wpsg-brand">Next</span> WP-Security</h1>
			<p class="wpsg-header-tagline"><?php esc_html_e( 'Güvenlik Ayarları ve Yapılandırma', 'wp-security-guardian' ); ?></p>
		</div>
	</div>

	<div class="wpsg-settings-intro">
		<p><?php esc_html_e( 'Güvenlik özelliklerini yapılandırarak sitenizi en üst düzeyde koruyun.', 'wp-security-guardian' ); ?></p>
	</div>

	<form method="post" action="options.php">
		<?php settings_fields( 'wpsg_options' ); ?>

		<div class="wpsg-settings-section">
			<h3><span class="wpsg-section-icon">🛡️</span> <?php esc_html_e( 'Genel Güvenlik', 'wp-security-guardian' ); ?></h3>
			
			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Otomatik Tarama', 'wp-security-guardian' ); ?></label>
						<p class="description"><?php esc_html_e( 'Sitenizin düzenli olarak taranmasını sağlayın.', 'wp-security-guardian' ); ?></p>
					</th>
					<td>
						<label class="wpsg-toggle">
							<input type="checkbox" name="wpsg_options[auto_scan]" value="1" <?php checked( ! empty( $options['auto_scan'] ) ); ?> />
							<span class="wpsg-toggle-slider"></span>
							<span class="wpsg-toggle-label"><?php esc_html_e( 'Günlük otomatik tarama yap', 'wp-security-guardian' ); ?></span>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Web Application Firewall (WAF)', 'wp-security-guardian' ); ?></label>
						<p class="description"><?php esc_html_e( 'SQL injection, XSS ve diğer saldırılara karşı koruma.', 'wp-security-guardian' ); ?></p>
					</th>
					<td>
						<label class="wpsg-toggle">
							<input type="checkbox" name="wpsg_options[firewall_enabled]" value="1" <?php checked( ! empty( $options['firewall_enabled'] ) ); ?> />
							<span class="wpsg-toggle-slider"></span>
							<span class="wpsg-toggle-label"><?php esc_html_e( 'WAF korumasını etkinleştir', 'wp-security-guardian' ); ?></span>
						</label>
					</td>
				</tr>
			</table>
		</div>

		<div class="wpsg-settings-section">
			<h3><span class="wpsg-section-icon">📁</span> <?php esc_html_e( 'Tarama Ayarları', 'wp-security-guardian' ); ?></h3>
			
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'Tarama Dizinleri', 'wp-security-guardian' ); ?></th>
					<td>
						<div class="wpsg-scan-dirs">
							<span class="wpsg-dir-tag">themes/</span>
							<span class="wpsg-dir-tag">plugins/</span>
							<span class="wpsg-dir-tag">wp-includes/</span>
						</div>
						<p class="description">
							<?php esc_html_e( 'Varsayılan olarak tema, eklenti ve wp-includes dizinleri taranır.', 'wp-security-guardian' ); ?>
						</p>
					</td>
				</tr>
			</table>
		</div>

		<div class="wpsg-settings-footer">
			<?php submit_button( __( 'Ayarları Kaydet', 'wp-security-guardian' ), 'primary', 'submit', false ); ?>
		</div>
	</form>
</div>

<style>
.wpsg-settings-intro {
    margin-bottom: 24px;
    padding: 20px 24px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-radius: 12px;
    border-left: 4px solid #3b82f6;
}

.wpsg-settings-intro p {
    margin: 0;
    color: #1e40af;
    font-size: 15px;
}

.wpsg-settings-section {
    background: #fff;
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.wpsg-settings-section h3 {
    margin: 0 0 24px 0;
    padding-bottom: 16px;
    border-bottom: 2px solid #f1f5f9;
    font-size: 18px;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 12px;
}

.wpsg-section-icon {
    font-size: 24px;
}

.wpsg-settings-section .form-table {
    margin-top: 0;
}

.wpsg-settings-section .form-table th {
    padding: 24px 20px 24px 0;
    width: 300px;
}

.wpsg-settings-section .form-table th label {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.wpsg-settings-section .form-table .description {
    margin-top: 6px;
    color: #64748b;
    font-size: 13px;
    font-style: normal;
}

.wpsg-settings-section .form-table td {
    padding: 20px 0;
}

/* Toggle Switch */
.wpsg-toggle {
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
}

.wpsg-toggle input {
    display: none;
}

.wpsg-toggle-slider {
    position: relative;
    width: 52px;
    height: 28px;
    background: #e2e8f0;
    border-radius: 100px;
    transition: background 0.3s ease;
}

.wpsg-toggle-slider::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 22px;
    height: 22px;
    background: #fff;
    border-radius: 50%;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.wpsg-toggle input:checked + .wpsg-toggle-slider {
    background: #3b82f6;
}

.wpsg-toggle input:checked + .wpsg-toggle-slider::after {
    transform: translateX(24px);
}

.wpsg-toggle-label {
    font-size: 14px;
    color: #334155;
    font-weight: 500;
}

/* Directory Tags */
.wpsg-scan-dirs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
}

.wpsg-dir-tag {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 13px;
    color: #475569;
    font-weight: 500;
}

.wpsg-dir-tag::before {
    content: '📁';
    margin-right: 8px;
}

.wpsg-settings-footer {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 2px solid #f1f5f9;
}

.wpsg-settings-footer .button-primary {
    padding: 14px 32px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Responsive */
@media screen and (max-width: 782px) {
    .wpsg-settings-section {
        padding: 20px;
    }
    
    .wpsg-settings-section .form-table th {
        width: auto;
        padding-bottom: 8px;
    }
    
    .wpsg-settings-section .form-table td {
        padding-top: 0;
    }
}
</style>
