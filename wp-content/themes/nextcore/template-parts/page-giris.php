<?php
/**
 * Giriş yap sayfası — Detaylı tasarım (Hesabım / Kayıt ol ile uyumlu)
 *
 * @package nextcore
 */

if ( is_user_logged_in() ) {
	$hesabim_url = function_exists( 'nextcore_get_hesabim_url' ) ? nextcore_get_hesabim_url() : home_url( '/hesabim/' );
	wp_safe_redirect( $hesabim_url );
	exit;
}

$redirect          = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : ( function_exists( 'nextcore_get_hesabim_url' ) ? nextcore_get_hesabim_url() : home_url( '/hesabim/' ) );
$lost_password_url = function_exists( 'nextcore_get_sifre_sifirlama_url' ) ? nextcore_get_sifre_sifirlama_url() : wp_lostpassword_url( get_permalink() );
$kayit_url         = function_exists( 'nextcore_get_kayit_url' ) ? nextcore_get_kayit_url() : wp_registration_url();
$home_label        = __( 'Anasayfa', 'nextcore' );
$giris_label       = __( 'Giriş yap', 'nextcore' );
?>

<div class="auth-page auth-page--giris giris-page">

	<section class="giris-hero">
		<div class="giris-hero-glow giris-hero-glow--1"></div>
		<div class="giris-hero-glow giris-hero-glow--2"></div>
		<div class="giris-hero-inner">
			<div class="giris-eyebrow">
				<span class="giris-eyebrow-line"></span>
				<span class="giris-eyebrow-text"><?php echo esc_html( $giris_label ); ?></span>
				<span class="giris-eyebrow-line"></span>
			</div>
			<h1 class="giris-title">Hesabınıza <em>Giriş</em> Yapın</h1>
			<p class="giris-desc">Mevcut hesabınızla giriş yaparak siparişlerinizi takip edin ve bilgilerinizi yönetin.</p>
		</div>
	</section>

	<section class="giris-content">
		<nav class="giris-breadcrumb" aria-label="<?php esc_attr_e( 'Sayfa yolu', 'nextcore' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $home_label ); ?></a>
			<span class="giris-breadcrumb-sep" aria-hidden="true"></span>
			<span class="giris-breadcrumb-current"><?php echo esc_html( $giris_label ); ?></span>
		</nav>

		<div class="giris-card reveal">
			<div class="giris-card-head">
				<span class="giris-card-icon" aria-hidden="true">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
				</span>
				<div class="giris-card-head-text">
					<h2 class="giris-card-title"><?php esc_html_e( 'Giriş yap', 'nextcore' ); ?></h2>
					<p class="giris-card-subtitle"><?php esc_html_e( 'E-posta veya kullanıcı adı ile devam edin', 'nextcore' ); ?></p>
				</div>
			</div>

			<div class="giris-card-body">
				<?php
				wp_login_form(
					array(
						'echo'           => true,
						'redirect'       => $redirect,
						'form_id'        => 'auth-login-form',
						'label_username' => __( 'E-posta veya kullanıcı adı', 'nextcore' ),
						'label_password' => __( 'Parola', 'nextcore' ),
						'label_remember' => __( 'Beni hatırla', 'nextcore' ),
						'label_log_in'   => __( 'Giriş yap', 'nextcore' ),
						'id_username'    => 'user_login',
						'id_password'    => 'user_pass',
						'id_remember'    => 'rememberme',
						'id_submit'      => 'auth-login-submit',
						'remember'       => true,
						'value_username' => '',
						'value_remember' => false,
					)
				);
				?>
				<p class="giris-links">
					<a href="<?php echo esc_url( $lost_password_url ); ?>" class="giris-link giris-link--forgot">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
						<?php esc_html_e( 'Şifremi unuttum', 'nextcore' ); ?>
					</a>
				</p>
				<p class="giris-switch">
					<?php esc_html_e( 'Hesabınız yok mu?', 'nextcore' ); ?>
					<a href="<?php echo esc_url( $kayit_url ); ?>" class="giris-switch-link">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
						<?php esc_html_e( 'Kayıt olun', 'nextcore' ); ?>
					</a>
				</p>
			</div>
		</div>
	</section>

</div>
