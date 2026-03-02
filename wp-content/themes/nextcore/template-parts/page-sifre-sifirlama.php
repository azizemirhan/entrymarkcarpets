<?php
/**
 * Şifre sıfırlama sayfası — Özel tasarım (Giriş sayfası ile uyumlu)
 * WordPress wp-login.php yerine sitede kalır; form gönderiminde retrieve_password() kullanılır.
 *
 * @package nextcore
 */

if ( is_user_logged_in() ) {
	$hesabim_url = function_exists( 'nextcore_get_hesabim_url' ) ? nextcore_get_hesabim_url() : home_url( '/hesabim/' );
	wp_safe_redirect( $hesabim_url );
	exit;
}

$page_url = function_exists( 'nextcore_get_sifre_sifirlama_url' ) ? nextcore_get_sifre_sifirlama_url() : home_url( '/sifre-sifirlama/' );
if ( get_queried_object_id() ) {
	$page_url = get_permalink();
}
$giris_url = function_exists( 'nextcore_get_giris_url' ) ? nextcore_get_giris_url() : home_url( '/giris/' );
$home_label = __( 'Anasayfa', 'nextcore' );
$form_sent = false;
$form_error = '';

// Form gönderildiyse şifre sıfırlama e-postası tetikle (WordPress retrieve_password)
if ( isset( $_POST['sifre_sifirlama_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sifre_sifirlama_nonce'] ) ), 'nextcore_sifre_sifirlama' ) ) {
	$user_input = isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '';
	if ( empty( $user_input ) ) {
		$form_error = __( 'Lütfen e-posta adresinizi veya kullanıcı adınızı girin.', 'nextcore' );
	} else {
		$user = null;
		if ( is_email( $user_input ) ) {
			$user = get_user_by( 'email', $user_input );
		}
		if ( ! $user ) {
			$user = get_user_by( 'login', $user_input );
		}
		if ( $user ) {
			$result = retrieve_password( $user->user_login );
			if ( is_wp_error( $result ) ) {
				$msg = $result->get_error_message();
				$code = $result->get_error_code();
				// E-posta gönderilemedi hatası: daha anlaşılır ve stilli mesaj
				if ( $code === 'retrieve_password_email_failure' || strpos( $msg, 'E-posta gönderilemedi' ) !== false || strpos( $msg, 'could not be sent' ) !== false ) {
					$form_error = 'mail_failure';
				} else {
					$form_error = $msg;
				}
			} else {
				$form_sent = true;
			}
		} else {
			// Güvenlik: Kullanıcı var/yok bilgisini açıklama; aynı mesajı göster
			$form_sent = true;
		}
	}
}
?>

<div class="auth-page auth-page--sifre sifre-sifirlama-page">

	<section class="giris-hero">
		<div class="giris-hero-glow giris-hero-glow--1"></div>
		<div class="giris-hero-glow giris-hero-glow--2"></div>
		<div class="giris-hero-inner">
			<div class="giris-eyebrow">
				<span class="giris-eyebrow-line"></span>
				<span class="giris-eyebrow-text"><?php esc_html_e( 'Şifre sıfırlama', 'nextcore' ); ?></span>
				<span class="giris-eyebrow-line"></span>
			</div>
			<h1 class="giris-title">Şifrenizi <em>Sıfırlayın</em></h1>
			<p class="giris-desc">E-posta veya kullanıcı adınızı girin; size şifre sıfırlama bağlantısı göndereceğiz.</p>
		</div>
	</section>

	<section class="giris-content">
		<nav class="giris-breadcrumb" aria-label="<?php esc_attr_e( 'Sayfa yolu', 'nextcore' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $home_label ); ?></a>
			<span class="giris-breadcrumb-sep" aria-hidden="true"></span>
			<span class="giris-breadcrumb-current"><?php esc_html_e( 'Şifre sıfırlama', 'nextcore' ); ?></span>
		</nav>

		<div class="giris-card reveal">
			<div class="giris-card-head">
				<span class="giris-card-icon" aria-hidden="true">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
				</span>
				<div class="giris-card-head-text">
					<h2 class="giris-card-title"><?php esc_html_e( 'Yeni parola al', 'nextcore' ); ?></h2>
					<p class="giris-card-subtitle"><?php esc_html_e( 'Kullanıcı adı veya e-posta adresinizi girin', 'nextcore' ); ?></p>
				</div>
			</div>

			<div class="giris-card-body">
				<?php if ( $form_sent ) : ?>
					<div class="sifre-sifirlama-message sifre-sifirlama-message--success">
						<p><?php esc_html_e( 'Hesabınız sistemde kayıtlıysa, şifre sıfırlama bağlantısı e-posta adresinize gönderildi. Lütfen gelen kutunuzu ve gerekiyorsa spam klasörünü kontrol edin.', 'nextcore' ); ?></p>
						<p><a href="<?php echo esc_url( $giris_url ); ?>" class="giris-link"><?php esc_html_e( 'Giriş sayfasına dön', 'nextcore' ); ?></a></p>
					</div>
				<?php else : ?>
					<?php if ( $form_error ) : ?>
						<div class="sifre-sifirlama-message sifre-sifirlama-message--error">
							<?php if ( $form_error === 'mail_failure' ) : ?>
								<p class="sifre-sifirlama-error-title"><?php esc_html_e( 'E-posta şu an gönderilemiyor', 'nextcore' ); ?></p>
								<p><?php esc_html_e( 'Sitenizde e-posta ayarları henüz yapılandırılmamış olabilir. Şifrenizi sıfırlamak için lütfen site yöneticisi veya destek ile iletişime geçin.', 'nextcore' ); ?></p>
								<p class="sifre-sifirlama-error-actions">
									<a href="<?php echo esc_url( home_url( '/iletisim/' ) ); ?>" class="sifre-sifirlama-error-link"><?php esc_html_e( 'İletişim sayfası', 'nextcore' ); ?></a>
									<span class="sifre-sifirlama-error-sep">·</span>
									<a href="https://wordpress.org/documentation/article/reset-your-password/" target="_blank" rel="noopener noreferrer" class="sifre-sifirlama-error-link"><?php esc_html_e( 'WordPress şifre sıfırlama rehberi', 'nextcore' ); ?></a>
								</p>
							<?php else : ?>
								<p><?php echo wp_kses_post( $form_error ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<form name="sifre-sifirlama-form" id="sifre-sifirlama-form" action="<?php echo esc_url( $page_url ); ?>" method="post" class="sifre-sifirlama-form">
						<?php wp_nonce_field( 'nextcore_sifre_sifirlama', 'sifre_sifirlama_nonce' ); ?>
						<p class="login-username">
							<label for="user_login"><?php esc_html_e( 'Kullanıcı adı ya da e-posta adresi', 'nextcore' ); ?></label>
							<input type="text" name="user_login" id="user_login" class="input" value="" size="20" autocapitalize="off" autocomplete="username" required placeholder="<?php esc_attr_e( 'E-posta veya kullanıcı adınız', 'nextcore' ); ?>">
						</p>
						<p class="login-submit">
							<button type="submit" name="wp-submit" id="sifre-sifirlama-submit" class="button button-primary"><?php esc_html_e( 'Yeni parola al', 'nextcore' ); ?></button>
						</p>
					</form>

					<p class="giris-switch">
						<a href="<?php echo esc_url( $giris_url ); ?>" class="giris-switch-link">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
							<?php esc_html_e( 'Giriş sayfasına dön', 'nextcore' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</section>

</div>
