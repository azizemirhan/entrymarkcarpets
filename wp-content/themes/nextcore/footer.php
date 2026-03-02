<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package nextcore
 */

?>

	<?php
	$newsletter_title   = get_option( 'eternal_general_newsletter_title', 'Stay Inspired with' );
	$newsletter_title_em = get_option( 'eternal_general_newsletter_title_em', 'Entry Mark' );
	$newsletter_desc   = get_option( 'eternal_general_newsletter_desc', 'Subscribe for exclusive access to new collections, designer insights, trade-only offers, and the latest in luxury flooring trends.' );
	$newsletter_btn    = get_option( 'eternal_general_newsletter_btn', 'Subscribe' );
	// Footer newsletter: İngilizce veritabanı değerlerini Türkçe gösterme
	$footer_newsletter_tr = array(
		'Stay Inspired with' => 'İlham almaya devam edin',
		'Entry Mark' => 'Entry Mark',
		'Subscribe for exclusive access to new collections, designer insights, trade-only offers, and the latest in luxury flooring trends.' => 'Yeni koleksiyonlar, tasarım içgörüleri ve lüks zemin kaplama trendleri hakkında özel içeriklere erişmek için abone olun.',
		'Subscribe' => 'Abone ol',
		'SUBSCRIBE' => 'Abone ol',
	);
	$newsletter_title   = isset( $footer_newsletter_tr[ $newsletter_title ] ) ? $footer_newsletter_tr[ $newsletter_title ] : $newsletter_title;
	$newsletter_title_em = isset( $footer_newsletter_tr[ $newsletter_title_em ] ) ? $footer_newsletter_tr[ $newsletter_title_em ] : $newsletter_title_em;
	$newsletter_desc   = isset( $footer_newsletter_tr[ $newsletter_desc ] ) ? $footer_newsletter_tr[ $newsletter_desc ] : $newsletter_desc;
	$newsletter_btn    = isset( $footer_newsletter_tr[ $newsletter_btn ] ) ? $footer_newsletter_tr[ $newsletter_btn ] : ( isset( $footer_newsletter_tr[ strtoupper( $newsletter_btn ) ] ) ? $footer_newsletter_tr[ strtoupper( $newsletter_btn ) ] : $newsletter_btn );
	?>
	<!-- ╔══════════════════════════════════════════════╗
	     ║  FOOTER — NEWSLETTER SECTION                ║
	     ╚══════════════════════════════════════════════╝ -->
	<section class="footer-newsletter-section">
	  <div class="newsletter-grid">
	    <div class="newsletter-text">
	      <h3><?php echo esc_html( $newsletter_title ); ?><br><em><?php echo esc_html( $newsletter_title_em ); ?></em></h3>
	      <p><?php echo esc_html( $newsletter_desc ); ?></p>
	    </div>
	    <div>
	      <div class="newsletter-form">
	        <input type="email" placeholder="<?php esc_attr_e( 'E-posta adresinizi girin', 'nextcore' ); ?>">
	        <button><?php echo esc_html( $newsletter_btn ); ?></button>
	      </div>
	    </div>
	  </div>
	</section>

	<!-- ╔══════════════════════════════════════════════╗
	     ║  FOOTER — MAIN CONTENT                      ║
	     ╚══════════════════════════════════════════════╝ -->
	<footer>
	  <div class="footer-main">
	    <div class="footer-grid">
	      <?php
	      $footer_logo_url = get_option( 'eternal_general_footer_logo', '' );
	      $footer_about_raw = get_option( 'eternal_general_footer_about_text', 'Premium carpet manufacturer delivering timeless design and infinite elegance since 1992. Trusted by leading hotels, offices, and residences across 40+ countries.' );
	      $footer_about_tr = array(
	        'Premium carpet manufacturer delivering timeless design and infinite elegance since 1992. Trusted by leading hotels, offices, and residences across 40+ countries.' => '1992\'den beri zamansız tasarım ve sonsuz zarafet sunan premium halı üreticisi. 40\'tan fazla ülkede önde gelen otel, ofis ve konutların tercihi.',
	      );
	      $footer_about = isset( $footer_about_tr[ $footer_about_raw ] ) ? $footer_about_tr[ $footer_about_raw ] : $footer_about_raw;
	      $social_ig = get_option( 'eternal_general_social_instagram', '#' );
	      $social_fb = get_option( 'eternal_general_social_facebook', '#' );
	      $social_li = get_option( 'eternal_general_social_linkedin', '#' );
	      $social_pin = get_option( 'eternal_general_social_pinterest', '#' );
	      $social_yt = get_option( 'eternal_general_social_youtube', '#' );
	      ?>
	      <!-- Brand column -->
	      <div class="footer-brand">
	        <div class="footer-logo">
	          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
	            <?php if ( ! empty( $footer_logo_url ) ) : ?>
	              <img src="<?php echo esc_url( nextcore_fix_image_url( $footer_logo_url ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="footer-logo-img">
	            <?php elseif ( has_custom_logo() ) :
	              $arr = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
	              if ( ! empty( $arr[0] ) ) : ?>
	                <img src="<?php echo esc_url( $arr[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="footer-logo-img">
	            <?php endif; elseif ( file_exists( get_template_directory() . '/assets/img/logo-footer.png' ) ) : ?>
	              <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-footer.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> Carpets" class="footer-logo-img">
	            <?php else : ?>
	              <div class="footer-logo-icon">
	                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 3l18 18M21 3L3 21"/></svg>
	              </div>
	              <div class="footer-logo-text">
	                <span class="fbrand"><?php bloginfo( 'name' ); ?></span>
	                <span class="ftagline"><?php bloginfo( 'description' ); ?></span>
	              </div>
	            <?php endif; ?>
	          </a>
	        </div>
	        <div class="footer-social footer-social-under-logo">
	          <?php if ( $social_ig && $social_ig !== '#' ) : ?><a href="<?php echo esc_url( $social_ig ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/></svg></a><?php endif; ?>
	          <?php if ( $social_fb && $social_fb !== '#' ) : ?><a href="<?php echo esc_url( $social_fb ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a><?php endif; ?>
	          <?php if ( $social_li && $social_li !== '#' ) : ?><a href="<?php echo esc_url( $social_li ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a><?php endif; ?>
	          <?php if ( $social_pin && $social_pin !== '#' ) : ?><a href="<?php echo esc_url( $social_pin ); ?>" target="_blank" rel="noopener" aria-label="Pinterest"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M8 21c1-3 1.5-5.5 2-7.5.5-2 1-3 2.5-3s2 1 1.5 3.5c-.5 2-1 3.5-1 5s.5 2 2 2c3 0 5-3.5 5-7 0-4-3-6.5-7-6.5-4.5 0-7.5 3-7.5 6.5 0 1 .5 2.5 1 3"/></svg></a><?php endif; ?>
	          <?php if ( ! empty( $social_yt ) && $social_yt !== '#' ) : ?><a href="<?php echo esc_url( $social_yt ); ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22.54 6.42a2.78 2.78 0 0 1-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.43z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg></a><?php endif; ?>
	        </div>
	        <p class="footer-brand-desc"><?php echo esc_html( $footer_about ); ?></p>
	      </div>

	      <?php
	      $url_galeri = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'galeri', 'template-gallery.php' ) : home_url( '/galeri/' );
	      $url_iletisim = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'iletisim', 'template-contact.php' ) : home_url( '/iletisim/' );
	      $url_hakkimizda = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'hakkimizda', 'template-about.php' ) : home_url( '/hakkimizda/' );
	      $url_ozellestir = function_exists( 'nextcore_get_customizer_url' ) ? nextcore_get_customizer_url() : home_url( '/paspas-ozellestir/' );
	      $url_surdurulebilirlik = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'surdurulebilirlik', 'template-surdurulebilirlik.php' ) : home_url( '/surdurulebilirlik/' );
	      $url_yardim = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'yardim-merkezi', 'template-yardim-merkezi.php' ) : home_url( '/yardim-merkezi/' );
	      $url_kullanim = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'kullanim-kosullari', 'template-kullanim.php' ) : home_url( '/kullanim-kosullari/' );
	      $url_degerlerimiz = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'degerlerimiz', 'template-degerlerimiz.php' ) : home_url( '/degerlerimiz/' );
	      $url_kvkk = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'kvkk-aydinlatma-metni', 'template-kvkk.php' ) : home_url( '/kvkk-aydinlatma-metni/' );
	      $url_cerez = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'cerez-politikasi', 'template-cerez.php' ) : home_url( '/cerez-politikasi/' );
	      $url_gizlilik = get_privacy_policy_url() ?: ( function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'gizlilik-politikasi', 'template-gizlilik.php' ) : home_url( '/gizlilik-politikasi/' ) );
	      $url_anasayfa = home_url( '/' );
	      ?>
	      <!-- Hızlı Linkler - her zaman ilk sütun -->
	      <div class="footer-col">
	        <div class="footer-col-title">Hızlı Linkler</div>
	        <div class="footer-links">
	          <a href="<?php echo esc_url( $url_anasayfa ); ?>">Anasayfa</a>
	          <a href="<?php echo esc_url( $url_ozellestir ); ?>">Kendin Tasarla</a>
	          <a href="<?php echo esc_url( $url_galeri ); ?>">Galeri</a>
	          <a href="<?php echo esc_url( $url_iletisim ); ?>">İletişim</a>
	        </div>
	      </div>
	      <!-- Kurumsal -->
	      <div class="footer-col">
	        <div class="footer-col-title">Kurumsal</div>
	        <div class="footer-links">
	          <a href="<?php echo esc_url( $url_hakkimizda ); ?>">Hakkımızda</a>
	          <a href="<?php echo esc_url( $url_degerlerimiz ); ?>">Değerlerimiz</a>
	          <a href="<?php echo esc_url( $url_surdurulebilirlik ); ?>">Sürdürülebilirlik</a>
	          <a href="<?php echo esc_url( $url_iletisim ); ?>">İletişim</a>
	        </div>
	      </div>
	      <!-- Yardım Merkezi -->
	      <div class="footer-col">
	        <div class="footer-col-title">Yardım Merkezi</div>
	        <div class="footer-links">
	          <a href="<?php echo esc_url( $url_kvkk ); ?>">KVKK Aydınlatma Metni</a>
	          <a href="<?php echo esc_url( $url_cerez ); ?>">Çerez Politikası</a>
	          <a href="<?php echo esc_url( $url_gizlilik ); ?>">Gizlilik Politikası</a>
	          <a href="<?php echo esc_url( $url_kullanim ); ?>">Kullanım Koşulları</a>
	        </div>
	      </div>
	      <?php
	      $footer_email = get_option( 'eternal_general_topbar_email', '' );
	      $footer_phone = get_option( 'eternal_general_topbar_phone', '' );
	      ?>
	      <!-- Bize Ulaşın -->
	      <div class="footer-col footer-col-contact">
	        <div class="footer-col-title">Bize Ulaşın</div>
	        <div class="footer-contact-block">
	          <?php if ( $footer_email ) : ?>
	            <a href="mailto:<?php echo esc_attr( $footer_email ); ?>" class="footer-contact-item">
	              <span class="footer-contact-icon" aria-hidden="true">
	                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 4 10 8 10-8"/></svg>
	              </span>
	              <span class="footer-contact-text"><?php echo esc_html( $footer_email ); ?></span>
	            </a>
	          <?php endif; ?>
	          <?php if ( $footer_phone ) : ?>
	            <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $footer_phone ) ); ?>" class="footer-contact-item">
	              <span class="footer-contact-icon" aria-hidden="true">
	                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
	              </span>
	              <span class="footer-contact-text"><?php echo esc_html( $footer_phone ); ?></span>
	            </a>
	          <?php endif; ?>
	          <a href="<?php echo esc_url( $url_iletisim ); ?>" class="footer-contact-link">İletişim sayfası →</a>
	        </div>
	      </div>
	    </div>

	    <!-- Trust badges -->
	    <div class="footer-trust">
	      <div class="trust-badge">
	        <div class="trust-badge-icon">
	          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
	        </div>
	        <div class="trust-badge-text">
	          <strong><?php esc_html_e( 'Güvenli Ödeme', 'nextcore' ); ?></strong>
	          <?php esc_html_e( 'SSL şifreli ödeme', 'nextcore' ); ?>
	        </div>
	      </div>
	      <div class="trust-badge">
	        <div class="trust-badge-icon">
	          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
	        </div>
	        <div class="trust-badge-text">
	          <strong><?php esc_html_e( 'Dünya Çapında Gönderim', 'nextcore' ); ?></strong>
	          <?php esc_html_e( '40\'tan fazla ülkeye teslimat', 'nextcore' ); ?>
	        </div>
	      </div>
	      <div class="trust-badge">
	        <div class="trust-badge-icon">
	          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
	        </div>
	        <div class="trust-badge-text">
	          <strong><?php esc_html_e( 'Premium Kalite', 'nextcore' ); ?></strong>
	          <?php esc_html_e( 'Sertifikalı malzemeler', 'nextcore' ); ?>
	        </div>
	      </div>
	      <div class="trust-badge">
	        <div class="trust-badge-icon">
	          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
	        </div>
	        <div class="trust-badge-text">
	          <strong><?php esc_html_e( 'Uzman Destek', 'nextcore' ); ?></strong>
	          <?php esc_html_e( 'Özel tasarım ekibi', 'nextcore' ); ?>
	        </div>
	      </div>
	    </div>
	  </div>

	  <?php
	  $copyright_image = get_option( 'eternal_general_copyright_image', '' );
	  $copyright_text  = get_option( 'eternal_general_copyright_text', '' );
	  if ( '' === trim( $copyright_text ) ) {
	    $copyright_text = '&copy; ' . gmdate( 'Y' ) . ' <a href="' . esc_url( home_url( '/' ) ) . '">' . get_bloginfo( 'name' ) . '</a>. ' . __( 'Tüm hakları saklıdır.', 'nextcore' );
	  }
	  $legal_links = get_option( 'eternal_general_legal_links', array() );
	  if ( empty( $legal_links ) || ! is_array( $legal_links ) ) {
	    $url_kvkk = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'kvkk-aydinlatma-metni', 'template-kvkk.php' ) : home_url( '/kvkk-aydinlatma-metni/' );
	    $url_cerez = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'cerez-politikasi', 'template-cerez.php' ) : home_url( '/cerez-politikasi/' );
	    $url_gizlilik = get_privacy_policy_url() ?: ( function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'gizlilik-politikasi', 'template-gizlilik.php' ) : home_url( '/gizlilik-politikasi/' ) );
	    $url_kullanim = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'kullanim-kosullari', 'template-kullanim.php' ) : home_url( '/kullanim-kosullari/' );
	    $legal_links = array(
	      array( 'label' => 'KVKK Aydınlatma Metni', 'url' => $url_kvkk ),
	      array( 'label' => 'Çerez Politikası', 'url' => $url_cerez ),
	      array( 'label' => 'Gizlilik Politikası', 'url' => $url_gizlilik ),
	      array( 'label' => 'Kullanım Koşulları', 'url' => $url_kullanim ),
	    );
	  } else {
	    // KVKK linki yoksa en başa ekle
	    $has_kvkk = false;
	    foreach ( $legal_links as $l ) {
	      if ( ! empty( $l['label'] ) && ( strpos( $l['label'], 'KVKK' ) !== false || strpos( $l['url'], 'kvkk' ) !== false ) ) {
	        $has_kvkk = true;
	        break;
	      }
	    }
	    if ( ! $has_kvkk ) {
	      $url_kvkk = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'kvkk-aydinlatma-metni', 'template-kvkk.php' ) : home_url( '/kvkk-aydinlatma-metni/' );
	      array_unshift( $legal_links, array( 'label' => 'KVKK Aydınlatma Metni', 'url' => $url_kvkk ) );
	    }
	    foreach ( $legal_links as &$l ) {
	      if ( ! empty( $l['label'] ) && strpos( $l['label'], 'Çerez' ) !== false && ( empty( $l['url'] ) || $l['url'] === '#' ) ) {
	        $l['url'] = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'cerez-politikasi', 'template-cerez.php' ) : home_url( '/cerez-politikasi/' );
	        break;
	      }
	    }
	    foreach ( $legal_links as &$l ) {
	      if ( ! empty( $l['label'] ) && strpos( $l['label'], 'Gizlilik' ) !== false && ( empty( $l['url'] ) || $l['url'] === '#' ) ) {
	        $l['url'] = get_privacy_policy_url() ?: ( function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'gizlilik-politikasi', 'template-gizlilik.php' ) : home_url( '/gizlilik-politikasi/' ) );
	        break;
	      }
	    }
	    foreach ( $legal_links as &$l ) {
	      if ( ! empty( $l['label'] ) && strpos( $l['label'], 'Kullanım' ) !== false && ( empty( $l['url'] ) || $l['url'] === '#' ) ) {
	        $l['url'] = function_exists( 'nextcore_get_page_url' ) ? nextcore_get_page_url( 'kullanim-kosullari', 'template-kullanim.php' ) : home_url( '/kullanim-kosullari/' );
	        break;
	      }
	    }
	    unset( $l );
	  }
	  $payment_icons = get_option( 'eternal_general_payment_icons', array() );
	  if ( ! is_array( $payment_icons ) ) {
	    $payment_icons = array();
	  }
	  $payment_icons = array_values( array_filter( $payment_icons, function( $p ) { return ! empty( $p['url'] ); } ) );
	  ?>
	  <!-- Bottom bar -->
	  <div class="footer-bottom">
	    <div class="footer-bottom-inner">
	      <div class="footer-copyright">
	        <?php if ( ! empty( $copyright_image ) ) : ?>
	          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-copyright-img-link">
	            <img src="<?php echo esc_url( function_exists( 'nextcore_fix_image_url' ) ? nextcore_fix_image_url( $copyright_image ) : $copyright_image ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="footer-copyright-img" loading="lazy">
	          </a>
	        <?php else : ?>
	          <?php echo wp_kses_post( $copyright_text ); ?>
	        <?php endif; ?>
	      </div>
	      <div class="footer-legal">
	        <?php foreach ( $legal_links as $link ) : ?>
	          <?php if ( ! empty( $link['label'] ) ) : ?>
	            <a href="<?php echo esc_url( ! empty( $link['url'] ) ? $link['url'] : '#' ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
	          <?php endif; ?>
	        <?php endforeach; ?>
	      </div>
	      <div class="footer-payments">
	        <?php if ( ! empty( $payment_icons ) ) : ?>
	          <?php foreach ( $payment_icons as $pay ) : ?>
	            <img src="<?php echo esc_url( function_exists( 'nextcore_fix_image_url' ) ? nextcore_fix_image_url( $pay['url'] ) : $pay['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $pay['label'] ) ? $pay['label'] : '' ); ?>" class="footer-payment-img" loading="lazy">
	          <?php endforeach; ?>
	        <?php else : ?>
	          <div class="payment-icon">VISA</div>
	          <div class="payment-icon">MC</div>
	          <div class="payment-icon">AMEX</div>
	          <div class="payment-icon">PP</div>
	        <?php endif; ?>
	      </div>
	    </div>
	  </div>
	</footer>

	<!-- Back to Top -->
	<button class="back-to-top" id="backToTop">
	  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
	</button>

</div><!-- #page -->

<!-- Google Translate Script -->
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'tr',
    includedLanguages: 'tr,en,de,fr,es,it,ar,ru,zh-CN,ja',
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
    autoDisplay: false
  }, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<?php wp_footer(); ?>

</body>
</html>
