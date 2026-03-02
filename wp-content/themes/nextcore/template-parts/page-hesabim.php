<?php
/**
 * Hesabım sayfası — Giriş formu veya hesap özeti (detaylı tasarım)
 *
 * @package nextcore
 */

$is_logged_in = is_user_logged_in();
$current_user = $is_logged_in ? wp_get_current_user() : null;
$login_redirect = get_permalink();
$register_url = function_exists( 'nextcore_get_kayit_url' ) ? nextcore_get_kayit_url() : wp_registration_url();
$lost_password_url = function_exists( 'nextcore_get_sifre_sifirlama_url' ) ? nextcore_get_sifre_sifirlama_url() : wp_lostpassword_url();
$home_label = __( 'Anasayfa', 'nextcore' );
$hesabim_label = __( 'Hesabım', 'nextcore' );
?>

<div class="hesabim-page">

	<?php if ( ! $is_logged_in ) : ?>

		<section class="hesabim-hero">
			<div class="hesabim-hero-glow hesabim-hero-glow--1"></div>
			<div class="hesabim-hero-glow hesabim-hero-glow--2"></div>
			<div class="hesabim-hero-inner">
				<div class="hesabim-eyebrow">
					<span class="hesabim-eyebrow-line"></span>
					<span class="hesabim-eyebrow-text"><?php echo esc_html( $hesabim_label ); ?></span>
					<span class="hesabim-eyebrow-line"></span>
				</div>
				<h1 class="hesabim-title">Hesabınıza <em>Giriş</em> Yapın</h1>
				<p class="hesabim-desc">Giriş yaparak siparişlerinizi takip edin, bilgilerinizi güncelleyin veya yeni hesap oluşturun.</p>
			</div>
		</section>

		<section class="hesabim-content">
			<nav class="hesabim-breadcrumb" aria-label="<?php esc_attr_e( 'Sayfa yolu', 'nextcore' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $home_label ); ?></a>
				<span class="hesabim-breadcrumb-sep" aria-hidden="true"></span>
				<span class="hesabim-breadcrumb-current"><?php echo esc_html( $hesabim_label ); ?></span>
			</nav>

			<div class="hesabim-cards-wrap">
				<div class="hesabim-card hesabim-card--login reveal">
					<div class="hesabim-card-head">
						<span class="hesabim-card-icon hesabim-card-icon--login" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
						</span>
						<div class="hesabim-card-head-text">
							<h2 class="hesabim-card-title">Giriş yap</h2>
							<p class="hesabim-card-subtitle">Mevcut hesabınızla devam edin</p>
						</div>
					</div>
					<div class="hesabim-card-body">
						<?php
						wp_login_form(
							array(
								'echo'           => true,
								'redirect'       => $login_redirect,
								'form_id'        => 'hesabim-login-form',
								'label_username' => __( 'E-posta veya kullanıcı adı', 'nextcore' ),
								'label_password' => __( 'Parola', 'nextcore' ),
								'label_remember' => __( 'Beni hatırla', 'nextcore' ),
								'label_log_in'   => __( 'Giriş yap', 'nextcore' ),
								'id_username'    => 'user_login',
								'id_password'    => 'user_pass',
								'id_remember'    => 'rememberme',
								'id_submit'      => 'hesabim-submit',
								'remember'       => true,
								'value_username' => '',
								'value_remember' => false,
							)
						);
						?>
						<p class="hesabim-links">
							<a href="<?php echo esc_url( $lost_password_url ); ?>" class="hesabim-link hesabim-link--forgot">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
								<?php esc_html_e( 'Şifremi unuttum', 'nextcore' ); ?>
							</a>
						</p>
					</div>
				</div>

				<div class="hesabim-divider reveal">
					<span class="hesabim-divider-line"></span>
					<span class="hesabim-divider-text"><?php esc_html_e( 'veya', 'nextcore' ); ?></span>
					<span class="hesabim-divider-line"></span>
				</div>

				<div class="hesabim-card hesabim-card--register reveal">
					<div class="hesabim-card-head">
						<span class="hesabim-card-icon hesabim-card-icon--register" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
						</span>
						<div class="hesabim-card-head-text">
							<h2 class="hesabim-card-title"><?php esc_html_e( 'Hesabınız yok mu?', 'nextcore' ); ?></h2>
							<p class="hesabim-card-subtitle"><?php esc_html_e( 'Birkaç saniyede ücretsiz hesap oluşturun', 'nextcore' ); ?></p>
						</div>
					</div>
					<div class="hesabim-card-body">
						<ul class="hesabim-feature-list">
							<li><span class="hesabim-feature-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><?php esc_html_e( 'Siparişlerinizi takip edin', 'nextcore' ); ?></li>
							<li><span class="hesabim-feature-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><?php esc_html_e( 'Adres ve bilgilerinizi yönetin', 'nextcore' ); ?></li>
							<li><span class="hesabim-feature-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><?php esc_html_e( 'Favori ürünleri kaydedin', 'nextcore' ); ?></li>
						</ul>
						<a href="<?php echo esc_url( $register_url ); ?>" class="hesabim-btn hesabim-btn--secondary hesabim-btn--block"><?php esc_html_e( 'Kayıt ol', 'nextcore' ); ?></a>
					</div>
				</div>
			</div>
		</section>

	<?php else : ?>

		<section class="hesabim-hero">
			<div class="hesabim-hero-glow hesabim-hero-glow--1"></div>
			<div class="hesabim-hero-glow hesabim-hero-glow--2"></div>
			<div class="hesabim-hero-inner">
				<div class="hesabim-eyebrow">
					<span class="hesabim-eyebrow-line"></span>
					<span class="hesabim-eyebrow-text"><?php echo esc_html( $hesabim_label ); ?></span>
					<span class="hesabim-eyebrow-line"></span>
				</div>
				<h1 class="hesabim-title">Hoş Geldiniz, <em><?php echo esc_html( $current_user->display_name ); ?></em></h1>
				<p class="hesabim-desc">Hesap bilgilerinizi yönetin, siparişlerinizi takip edin.</p>
			</div>
		</section>

		<section class="hesabim-content">
			<nav class="hesabim-breadcrumb" aria-label="<?php esc_attr_e( 'Sayfa yolu', 'nextcore' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $home_label ); ?></a>
				<span class="hesabim-breadcrumb-sep" aria-hidden="true"></span>
				<span class="hesabim-breadcrumb-current"><?php echo esc_html( $hesabim_label ); ?></span>
			</nav>

			<div class="hesabim-dashboard">
				<div class="hesabim-card hesabim-card--profile reveal">
					<div class="hesabim-card-head">
						<span class="hesabim-card-icon hesabim-card-icon--profile" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
						</span>
						<div class="hesabim-card-head-text">
							<h2 class="hesabim-card-title"><?php esc_html_e( 'Hesap bilgileri', 'nextcore' ); ?></h2>
							<p class="hesabim-card-subtitle"><?php esc_html_e( 'Kişisel bilgileriniz', 'nextcore' ); ?></p>
						</div>
					</div>
					<div class="hesabim-card-body">
						<div class="hesabim-profile-avatar" aria-hidden="true">
							<span class="hesabim-profile-avatar-inner"><?php echo esc_html( mb_substr( $current_user->display_name, 0, 1 ) ); ?></span>
						</div>
						<dl class="hesabim-dl">
							<div class="hesabim-dl-row">
								<dt><span class="hesabim-dl-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span><?php esc_html_e( 'Ad Soyad', 'nextcore' ); ?></dt>
								<dd><?php echo esc_html( $current_user->display_name ); ?></dd>
							</div>
							<div class="hesabim-dl-row">
								<dt><span class="hesabim-dl-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span><?php esc_html_e( 'E-posta', 'nextcore' ); ?></dt>
								<dd><a href="mailto:<?php echo esc_attr( $current_user->user_email ); ?>"><?php echo esc_html( $current_user->user_email ); ?></a></dd>
							</div>
							<div class="hesabim-dl-row">
								<dt><span class="hesabim-dl-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/><path d="M12 6v6l4 2"/></svg></span><?php esc_html_e( 'Kullanıcı adı', 'nextcore' ); ?></dt>
								<dd><?php echo esc_html( $current_user->user_login ); ?></dd>
							</div>
						</dl>
						<?php if ( current_user_can( 'edit_posts' ) ) : ?>
							<a href="<?php echo esc_url( get_edit_user_link( $current_user->ID ) ); ?>" class="hesabim-btn hesabim-btn--primary">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
								<?php esc_html_e( 'Profilimi düzenle', 'nextcore' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<?php
				// Paspas (EMC) siparişleri — giriş yapan kullanıcının e-postasına göre listele
				$emc_orders = array();
				if ( class_exists( 'EMC_Checkout' ) && $current_user->user_email ) {
					$emc_orders = get_posts( array(
						'post_type'      => 'emc_order',
						'post_status'    => 'any',
						'posts_per_page' => 20,
						'orderby'        => 'date',
						'order'          => 'DESC',
						'meta_query'     => array(
							array(
								'key'     => '_emc_customer',
								'value'   => $current_user->user_email,
								'compare' => 'LIKE',
							),
						),
					) );
				}
				$emc_status_labels = array(
					'pending_payment' => __( 'Ödeme bekliyor', 'nextcore' ),
					'paid'            => __( 'Ödendi', 'nextcore' ),
					'failed'          => __( 'Başarısız', 'nextcore' ),
					'processing'      => __( 'İşleniyor', 'nextcore' ),
					'completed'       => __( 'Tamamlandı', 'nextcore' ),
				);
				?>
				<div class="hesabim-card hesabim-card--orders reveal">
					<div class="hesabim-card-head">
						<span class="hesabim-card-icon hesabim-card-icon--orders" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
						</span>
						<div class="hesabim-card-head-text">
							<h2 class="hesabim-card-title"><?php esc_html_e( 'Siparişlerim', 'nextcore' ); ?></h2>
							<p class="hesabim-card-subtitle"><?php esc_html_e( 'Sipariş geçmişi ve takip', 'nextcore' ); ?></p>
						</div>
					</div>
					<div class="hesabim-card-body">
						<?php if ( ! empty( $emc_orders ) ) : ?>
							<div class="hesabim-orders-list">
								<table class="hesabim-orders-table">
									<thead>
										<tr>
											<th><?php esc_html_e( 'Tarih', 'nextcore' ); ?></th>
											<th><?php esc_html_e( 'Sipariş No', 'nextcore' ); ?></th>
											<th><?php esc_html_e( 'Toplam', 'nextcore' ); ?></th>
											<th><?php esc_html_e( 'Durum', 'nextcore' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $emc_orders as $order ) :
											$meta = class_exists( 'EMC_Checkout' ) ? EMC_Checkout::get_order_meta( $order->ID ) : array();
											$status = isset( $meta['status'] ) ? $meta['status'] : '';
											$status_label = isset( $emc_status_labels[ $status ] ) ? $emc_status_labels[ $status ] : $status;
											$total = isset( $meta['total'] ) ? (float) $meta['total'] : 0;
											?>
											<tr>
												<td><?php echo esc_html( get_the_date( '', $order ) ); ?></td>
												<td><span class="hesabim-order-id">#<?php echo esc_html( $order->ID ); ?></span></td>
												<td><?php echo esc_html( number_format( $total, 2, ',', '.' ) ); ?> TL</td>
												<td><span class="hesabim-order-status hesabim-order-status--<?php echo esc_attr( sanitize_html_class( $status ) ); ?>"><?php echo esc_html( $status_label ); ?></span></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php elseif ( class_exists( 'WooCommerce' ) ) : ?>
							<p class="hesabim-card-desc"><?php esc_html_e( 'Siparişlerinizi görüntüleyin, fatura indirin ve kargoyu takip edin.', 'nextcore' ); ?></p>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="hesabim-btn hesabim-btn--secondary">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
								<?php esc_html_e( 'Siparişlerime git', 'nextcore' ); ?>
							</a>
						<?php else : ?>
							<p class="hesabim-card-desc hesabim-orders-empty"><?php esc_html_e( 'Henüz siparişiniz bulunmuyor. Paspas tasarımınızı tamamlayıp sipariş verdiğinizde burada listelenecektir.', 'nextcore' ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<div class="hesabim-card hesabim-card--logout reveal">
					<div class="hesabim-card-body hesabim-card-body--logout">
						<a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="hesabim-btn hesabim-btn--outline hesabim-btn--logout">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
							<?php esc_html_e( 'Çıkış yap', 'nextcore' ); ?>
						</a>
					</div>
				</div>
			</div>
		</section>

	<?php endif; ?>

</div>
