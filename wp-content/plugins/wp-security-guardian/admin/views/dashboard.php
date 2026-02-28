<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$last_scan   = get_option( 'wpsg_last_scan', array() );
$options     = get_option( 'wpsg_options', array() );
$threats     = isset( $last_scan['threats'] ) ? (int) $last_scan['threats'] : 0;
$completed   = ! empty( $last_scan['completed_at'] );
$scan_date   = ! empty( $last_scan['completed_at'] ) ? $last_scan['completed_at'] : ( $last_scan['started_at'] ?? '' );
$score       = 100 - min( $threats * 10, 100 );
$score_class = $threats > 0 ? 'wpsg-score-warning' : 'wpsg-score-good';

// Durum ikonu ve mesajı
if ( $score >= 90 ) {
    $status_icon = '🛡️';
    $status_text = __( 'Mükemmel', 'wp-security-guardian' );
} elseif ( $score >= 70 ) {
    $status_icon = '⚡';
    $status_text = __( 'İyi', 'wp-security-guardian' );
} elseif ( $score >= 50 ) {
    $status_icon = '⚠️';
    $status_text = __( 'Orta', 'wp-security-guardian' );
} else {
    $status_icon = '🚨';
    $status_text = __( 'Kritik', 'wp-security-guardian' );
}
?>
<div class="wrap wpsg-dashboard">
	<div class="wpsg-header">
		<img src="<?php echo esc_url( WPSG_URL . 'admin/assets/images/logo.png' ); ?>" alt="Next WP" class="wpsg-header-logo" />
		<div>
			<h1><span class="wpsg-brand">Next</span> WP-Security</h1>
			<p class="wpsg-header-tagline"><?php esc_html_e( 'WordPress siteniz için profesyonel güvenlik çözümü', 'wp-security-guardian' ); ?></p>
		</div>
	</div>

	<!-- Güvenlik Durumu Özeti -->
	<div class="wpsg-dashboard-summary">
		<div class="wpsg-score-card <?php echo esc_attr( $score_class ); ?>">
			<div class="wpsg-score-icon"><?php echo $status_icon; ?></div>
			<div class="wpsg-score-value"><?php echo esc_html( $score ); ?></div>
			<div class="wpsg-score-label"><?php esc_html_e( 'Güvenlik Skoru', 'wp-security-guardian' ); ?></div>
			<div class="wpsg-score-status"><?php echo esc_html( $status_text ); ?></div>
		</div>
		<div class="wpsg-stats">
			<div class="wpsg-stat-item">
				<div class="wpsg-stat-icon">📅</div>
				<div class="wpsg-stat-content">
					<span class="wpsg-stat-label"><?php esc_html_e( 'Son Tarama', 'wp-security-guardian' ); ?></span>
					<span class="wpsg-stat-value">
						<?php echo $completed ? esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $scan_date ) ) ) : esc_html__( 'Tamamlanmadı', 'wp-security-guardian' ); ?>
					</span>
				</div>
			</div>
			<div class="wpsg-stat-item">
				<div class="wpsg-stat-icon">🔍</div>
				<div class="wpsg-stat-content">
					<span class="wpsg-stat-label"><?php esc_html_e( 'Tespit Edilen Tehdit', 'wp-security-guardian' ); ?></span>
					<span class="wpsg-stat-value <?php echo $threats > 0 ? 'wpsg-threat-yes' : 'wpsg-threat-no'; ?>">
						<?php echo $threats > 0 ? sprintf( __( '%d tehdit', 'wp-security-guardian' ), $threats ) : __( 'Temiz', 'wp-security-guardian' ); ?>
					</span>
				</div>
			</div>
			<div class="wpsg-stat-item">
				<div class="wpsg-stat-icon">🛡️</div>
				<div class="wpsg-stat-content">
					<span class="wpsg-stat-label"><?php esc_html_e( 'WAF Durumu', 'wp-security-guardian' ); ?></span>
					<span class="wpsg-stat-value wpsg-<?php echo ! empty( $options['firewall_enabled'] ) ? 'enabled' : 'disabled'; ?>">
						<?php echo ! empty( $options['firewall_enabled'] ) ? __( 'Etkin', 'wp-security-guardian' ) : __( 'Devre dışı', 'wp-security-guardian' ); ?>
					</span>
				</div>
			</div>
			<div class="wpsg-stat-item">
				<div class="wpsg-stat-icon">🔐</div>
				<div class="wpsg-stat-content">
					<span class="wpsg-stat-label"><?php esc_html_e( '2FA Koruması', 'wp-security-guardian' ); ?></span>
					<span class="wpsg-stat-value wpsg-enabled"><?php esc_html_e( 'Aktif', 'wp-security-guardian' ); ?></span>
				</div>
			</div>
		</div>
	</div>

	<!-- Hızlı Erişim Kartları -->
	<div class="wpsg-dashboard-cards">
		<div class="wpsg-card wpsg-card-featured">
			<div class="wpsg-card-icon">🔍</div>
			<h2><?php esc_html_e( 'Güvenlik Taraması', 'wp-security-guardian' ); ?></h2>
			<p><?php esc_html_e( 'Sitenizi malware, virüsler ve şüpheli kodlar için tarayın. Zararlı yazılımları anında tespit edin ve temizleyin.', 'wp-security-guardian' ); ?></p>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpsg-scan' ) ); ?>" class="button button-primary">
				<?php esc_html_e( 'Tarama Başlat', 'wp-security-guardian' ); ?>
				<span class="wpsg-btn-arrow">→</span>
			</a>
		</div>
		
		<div class="wpsg-card">
			<div class="wpsg-card-icon">⚙️</div>
			<h2><?php esc_html_e( 'Güvenlik Ayarları', 'wp-security-guardian' ); ?></h2>
			<p><?php esc_html_e( 'WAF, otomatik tarama ve diğer güvenlik özelliklerini yapılandırın. Sitenizi özelleştirilmiş korumayla güçlendirin.', 'wp-security-guardian' ); ?></p>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpsg-settings' ) ); ?>" class="button">
				<?php esc_html_e( 'Ayarları Yönet', 'wp-security-guardian' ); ?>
			</a>
		</div>
		
		<div class="wpsg-card">
			<div class="wpsg-card-icon">📊</div>
			<h2><?php esc_html_e( 'Raporlar', 'wp-security-guardian' ); ?></h2>
			<p><?php esc_html_e( 'Geçmiş tarama sonuçlarını görüntüleyin ve sitenizin güvenlik geçmişini analiz edin.', 'wp-security-guardian' ); ?></p>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpsg-scan' ) ); ?>" class="button">
				<?php esc_html_e( 'Raporları Gör', 'wp-security-guardian' ); ?>
			</a>
		</div>
		
		<div class="wpsg-card">
			<div class="wpsg-card-icon">🛡️</div>
			<h2><?php esc_html_e( 'İki Faktörlü Doğrulama', 'wp-security-guardian' ); ?></h2>
			<p><?php esc_html_e( 'Hesap güvenliğinizi artırın. 2FA ile yetkisiz erişimleri önleyin.', 'wp-security-guardian' ); ?></p>
			<a href="<?php echo esc_url( admin_url( 'profile.php#wpsg-2fa' ) ); ?>" class="button">
				<?php esc_html_e( '2FA Ayarla', 'wp-security-guardian' ); ?>
			</a>
		</div>
	</div>

	<!-- Güvenlik İpuçları -->
	<div class="wpsg-security-tips">
		<h3>💡 <?php esc_html_e( 'Güvenlik İpuçları', 'wp-security-guardian' ); ?></h3>
		<div class="wpsg-tips-grid">
			<div class="wpsg-tip-item">
				<span class="wpsg-tip-icon">✓</span>
				<span><?php esc_html_e( 'Düzenli olarak yedekleme yapın', 'wp-security-guardian' ); ?></span>
			</div>
			<div class="wpsg-tip-item">
				<span class="wpsg-tip-icon">✓</span>
				<span><?php esc_html_e( 'Güçlü şifreler kullanın', 'wp-security-guardian' ); ?></span>
			</div>
			<div class="wpsg-tip-item">
				<span class="wpsg-tip-icon">✓</span>
				<span><?php esc_html_e( 'Eklentileri güncel tutun', 'wp-security-guardian' ); ?></span>
			</div>
			<div class="wpsg-tip-item">
				<span class="wpsg-tip-icon">✓</span>
				<span><?php esc_html_e( 'Güvenilir tema kullanın', 'wp-security-guardian' ); ?></span>
			</div>
		</div>
	</div>
</div>

<style>
/* Dashboard Özel Stilleri */
.wpsg-header-tagline {
    margin: 8px 0 0 0;
    color: #64748b;
    font-size: 14px;
}

.wpsg-score-icon {
    font-size: 40px;
    margin-bottom: 12px;
}

.wpsg-score-status {
    margin-top: 8px;
    padding: 6px 16px;
    background: #f1f5f9;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--nextwp-text);
}

.wpsg-score-good .wpsg-score-status {
    background: #d1fae5;
    color: #065f46;
}

.wpsg-score-warning .wpsg-score-status {
    background: #fef3c7;
    color: #92400e;
}

.wpsg-stat-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.wpsg-stat-item:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
}

.wpsg-stat-icon {
    font-size: 32px;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.wpsg-stat-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.wpsg-stat-label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.wpsg-stat-value {
    font-size: 18px;
    font-weight: 700;
    color: var(--nextwp-primary-dark);
}

.wpsg-stat-value.wpsg-enabled {
    color: var(--nextwp-success);
}

.wpsg-stat-value.wpsg-disabled {
    color: #94a3b8;
}

.wpsg-card-icon {
    font-size: 36px;
    margin-bottom: 16px;
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-radius: 16px;
}

.wpsg-card-featured {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    color: #fff;
}

.wpsg-card-featured .wpsg-card-icon {
    background: rgba(255,255,255,0.2);
}

.wpsg-card-featured h2 {
    color: #fff;
}

.wpsg-card-featured h2::before {
    background: #fff;
}

.wpsg-card-featured p {
    color: rgba(255,255,255,0.9);
}

.wpsg-card-featured .button-primary {
    background: #fff;
    color: #1e40af;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.wpsg-card-featured .button-primary:hover {
    background: #f8fafc;
    color: #1e3a8a;
}

.wpsg-btn-arrow {
    margin-left: 8px;
    transition: transform 0.2s ease;
}

.wpsg-card-featured .button-primary:hover .wpsg-btn-arrow {
    transform: translateX(4px);
}

.wpsg-security-tips {
    margin-top: 32px;
    padding: 28px 32px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.wpsg-security-tips h3 {
    margin: 0 0 20px 0;
    color: #0f172a;
    font-size: 18px;
    font-weight: 700;
}

.wpsg-tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.wpsg-tip-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: #f8fafc;
    border-radius: 12px;
    font-size: 14px;
    color: #334155;
    font-weight: 500;
    transition: all 0.2s ease;
}

.wpsg-tip-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.wpsg-tip-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #10b981;
    color: #fff;
    border-radius: 50%;
    font-size: 12px;
    font-weight: 700;
}
</style>
