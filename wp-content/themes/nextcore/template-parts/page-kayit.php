<?php
/**
 * Kayıt ol sayfası — Tam tasarım (form her zaman görünür)
 *
 * @package nextcore
 */

if ( is_user_logged_in() ) {
	$hesabim_url = function_exists( 'nextcore_get_hesabim_url' ) ? nextcore_get_hesabim_url() : home_url( '/hesabim/' );
	wp_safe_redirect( $hesabim_url );
	exit;
}

$register_url  = admin_url( 'admin-post.php?action=entrymark_register' );
$redirect      = function_exists( 'nextcore_get_hesabim_url' ) ? nextcore_get_hesabim_url() : home_url( '/hesabim/' );
$giris_url     = function_exists( 'nextcore_get_giris_url' ) ? nextcore_get_giris_url() : home_url( '/giris/' );
$home_label    = __( 'Anasayfa', 'nextcore' );
$kayit_label   = __( 'Kayıt ol', 'nextcore' );
$can_register  = get_option( 'users_can_register' );
$show_warning  = ! $can_register || ( isset( $_GET['registration'] ) && $_GET['registration'] === 'disabled' );
$reg_errors    = array();
if ( isset( $_GET['reg_error'] ) ) {
	$reg_errors[] = sanitize_text_field( wp_unslash( $_GET['reg_error'] ) );
}
?>

<div class="auth-page auth-page--kayit kayit-page kayit-page--full">

	<section class="kayit-hero">
		<div class="kayit-hero-glow kayit-hero-glow--1"></div>
		<div class="kayit-hero-glow kayit-hero-glow--2"></div>
		<div class="kayit-hero-inner">
			<div class="kayit-eyebrow">
				<span class="kayit-eyebrow-line"></span>
				<span class="kayit-eyebrow-text"><?php echo esc_html( $kayit_label ); ?></span>
				<span class="kayit-eyebrow-line"></span>
			</div>
			<h1 class="kayit-title">Yeni <em>Hesap</em> Oluşturun</h1>
			<p class="kayit-desc">Ücretsiz hesap açarak siparişlerinizi takip edin, adreslerinizi kaydedin ve alışverişinizi kolaylaştırın.</p>
		</div>
	</section>

	<section class="kayit-content">
		<nav class="kayit-breadcrumb" aria-label="<?php esc_attr_e( 'Sayfa yolu', 'nextcore' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $home_label ); ?></a>
			<span class="kayit-breadcrumb-sep" aria-hidden="true"></span>
			<span class="kayit-breadcrumb-current"><?php echo esc_html( $kayit_label ); ?></span>
		</nav>

		<div class="kayit-card reveal">
			<div class="kayit-card-head">
				<span class="kayit-card-icon" aria-hidden="true">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
				</span>
				<div class="kayit-card-head-text">
					<h2 class="kayit-card-title"><?php esc_html_e( 'Hesap bilgilerinizi girin', 'nextcore' ); ?></h2>
					<p class="kayit-card-subtitle"><?php esc_html_e( 'Kullanıcı adı, e-posta ve şifre belirleyin', 'nextcore' ); ?></p>
				</div>
			</div>

			<div class="kayit-card-body">
				<?php if ( ! empty( $reg_errors ) ) : ?>
					<div class="kayit-message kayit-message--error">
						<?php echo esc_html( implode( ' ', $reg_errors ) ); ?>
					</div>
				<?php endif; ?>
				<?php if ( isset( $_GET['reg_success'] ) && $_GET['reg_success'] === '1' ) : ?>
					<div class="kayit-message kayit-message--success">
						<?php esc_html_e( 'Kayıt başarılı! Şimdi giriş yapabilirsiniz.', 'nextcore' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( $show_warning ) : ?>
					<div class="kayit-message kayit-message--warning">
						<strong><?php esc_html_e( 'Yeni kullanıcı kaydı şu an kapalı.', 'nextcore' ); ?></strong>
						<?php esc_html_e( 'Kayıt açıldığında aşağıdaki formu kullanabilirsiniz. Şimdilik giriş yapabilirsiniz.', 'nextcore' ); ?>
					</div>
				<?php endif; ?>

				<ul class="kayit-feature-list">
					<li><span class="kayit-feature-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><?php esc_html_e( 'Siparişlerinizi tek yerden takip edin', 'nextcore' ); ?></li>
					<li><span class="kayit-feature-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><?php esc_html_e( 'Teslimat adreslerinizi kaydedin', 'nextcore' ); ?></li>
					<li><span class="kayit-feature-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><?php esc_html_e( 'Favori ürünlere hızlı erişin', 'nextcore' ); ?></li>
				</ul>

				<form name="registerform" id="auth-register-form" action="<?php echo esc_url( $register_url ); ?>" method="post" class="kayit-form <?php echo $can_register ? '' : 'kayit-form--disabled'; ?>">
					<?php wp_nonce_field( 'entrymark_register', 'register_nonce' ); ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
					<div class="kayit-form-row">
						<label for="user_login"><?php esc_html_e( 'Kullanıcı adı', 'nextcore' ); ?> <span class="required">*</span></label>
						<input type="text" name="user_login" id="user_login" class="kayit-input" value="<?php echo isset( $_GET['user_login'] ) ? esc_attr( sanitize_user( wp_unslash( $_GET['user_login'] ) ) ) : ''; ?>" <?php echo $can_register ? 'required' : 'readonly disabled'; ?> autocomplete="username" placeholder="<?php esc_attr_e( 'Örn. ahmet_yilmaz', 'nextcore' ); ?>">
					</div>
					<div class="kayit-form-row">
						<label for="user_email"><?php esc_html_e( 'E-posta', 'nextcore' ); ?> <span class="required">*</span></label>
						<input type="email" name="user_email" id="user_email" class="kayit-input" value="<?php echo isset( $_GET['user_email'] ) ? esc_attr( sanitize_email( wp_unslash( $_GET['user_email'] ) ) ) : ''; ?>" <?php echo $can_register ? 'required' : 'readonly disabled'; ?> autocomplete="email" placeholder="<?php esc_attr_e( 'ornek@email.com', 'nextcore' ); ?>">
					</div>
					<div class="kayit-form-row">
						<label for="user_pass"><?php esc_html_e( 'Şifre', 'nextcore' ); ?> <span class="required">*</span></label>
						<input type="password" name="user_pass" id="user_pass" class="kayit-input" <?php echo $can_register ? 'required minlength="6"' : 'readonly disabled'; ?> autocomplete="new-password" placeholder="<?php esc_attr_e( 'En az 6 karakter', 'nextcore' ); ?>">
					</div>
					<div class="kayit-form-row">
						<label for="user_pass2"><?php esc_html_e( 'Şifre tekrar', 'nextcore' ); ?> <span class="required">*</span></label>
						<input type="password" name="user_pass2" id="user_pass2" class="kayit-input" <?php echo $can_register ? 'required minlength="6"' : 'readonly disabled'; ?> autocomplete="new-password" placeholder="<?php esc_attr_e( 'Şifrenizi tekrar girin', 'nextcore' ); ?>">
					</div>
					<div class="kayit-form-row kayit-form-row--submit">
						<?php if ( $can_register ) : ?>
							<button type="submit" name="wp-submit" id="auth-register-submit" class="kayit-btn kayit-btn--primary">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
								<?php esc_html_e( 'Kayıt ol', 'nextcore' ); ?>
							</button>
						<?php else : ?>
							<button type="button" disabled class="kayit-btn kayit-btn--disabled">
								<?php esc_html_e( 'Kayıt şu an kapalı', 'nextcore' ); ?>
							</button>
						<?php endif; ?>
					</div>
				</form>

				<p class="kayit-switch">
					<?php esc_html_e( 'Zaten hesabınız var mı?', 'nextcore' ); ?>
					<a href="<?php echo esc_url( $giris_url ); ?>" class="kayit-switch-link">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
						<?php esc_html_e( 'Giriş yapın', 'nextcore' ); ?>
					</a>
				</p>
			</div>
		</div>
	</section>

</div>
