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
	        <input type="email" placeholder="Enter your email address">
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
	      $footer_about    = get_option( 'eternal_general_footer_about_text', 'Premium carpet manufacturer delivering timeless design and infinite elegance since 1992. Trusted by leading hotels, offices, and residences across 40+ countries.' );
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
	        <p class="footer-brand-desc"><?php echo esc_html( $footer_about ); ?></p>
	        <div class="footer-social">
	          <?php if ( $social_ig && $social_ig !== '#' ) : ?><a href="<?php echo esc_url( $social_ig ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/></svg></a><?php endif; ?>
	          <?php if ( $social_fb && $social_fb !== '#' ) : ?><a href="<?php echo esc_url( $social_fb ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a><?php endif; ?>
	          <?php if ( $social_li && $social_li !== '#' ) : ?><a href="<?php echo esc_url( $social_li ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a><?php endif; ?>
	          <?php if ( $social_pin && $social_pin !== '#' ) : ?><a href="<?php echo esc_url( $social_pin ); ?>" target="_blank" rel="noopener" aria-label="Pinterest"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M8 21c1-3 1.5-5.5 2-7.5.5-2 1-3 2.5-3s2 1 1.5 3.5c-.5 2-1 3.5-1 5s.5 2 2 2c3 0 5-3.5 5-7 0-4-3-6.5-7-6.5-4.5 0-7.5 3-7.5 6.5 0 1 .5 2.5 1 3"/></svg></a><?php endif; ?>
	          <?php if ( ! empty( $social_yt ) && $social_yt !== '#' ) : ?><a href="<?php echo esc_url( $social_yt ); ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22.54 6.42a2.78 2.78 0 0 1-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.43z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg></a><?php endif; ?>
	        </div>
	      </div>

	      <?php if ( has_nav_menu( 'footer' ) ) : ?>
	        <?php
	        wp_nav_menu(
	          array(
	            'theme_location' => 'footer',
	            'container'      => false,
	            'items_wrap'     => '%3$s',
	            'walker'         => new Nextcore_Footer_Menu_Walker(),
	            'depth'          => 2,
	          )
	        );
	        ?>
	      <?php else : ?>
	        <!-- Collections -->
	        <div class="footer-col">
	          <div class="footer-col-title">Collections</div>
	          <div class="footer-links">
	            <a href="#">Modern & Contemporary</a>
	            <a href="#">Classic & Traditional</a>
	            <a href="#">Geometric Patterns</a>
	            <a href="#">Minimalist</a>
	            <a href="#">Aegean Luxe Series</a>
	            <a href="#">Anatolian Heritage</a>
	          </div>
	        </div>
	        <div class="footer-col">
	          <div class="footer-col-title">Solutions</div>
	          <div class="footer-links">
	            <a href="#">Hotels & Hospitality</a>
	            <a href="#">Restaurants & Cafés</a>
	            <a href="#">Corporate Offices</a>
	            <a href="#">Residential Spaces</a>
	            <a href="#">Automotive Showrooms</a>
	            <a href="#">Education Centers</a>
	          </div>
	        </div>
	        <div class="footer-col">
	          <div class="footer-col-title">Company</div>
	          <div class="footer-links">
	            <a href="#">About Entry Mark</a>
	            <a href="#">Our Projects</a>
	            <a href="#">Designer Lab</a>
	            <a href="#">Sustainability</a>
	            <a href="#">Careers</a>
	            <a href="#">Press & Media</a>
	          </div>
	        </div>
	        <div class="footer-col">
	          <div class="footer-col-title">Support</div>
	          <div class="footer-links">
	            <a href="#">Contact Us</a>
	            <a href="#">Request a Sample</a>
	            <a href="#">Get a Quote</a>
	            <a href="#">FAQs</a>
	            <a href="#">Shipping & Delivery</a>
	            <a href="#">Returns Policy</a>
	          </div>
	        </div>
	      <?php endif; ?>
	    </div>

	    <!-- Trust badges -->
	    <div class="footer-trust">
	      <div class="trust-badge">
	        <div class="trust-badge-icon">
	          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
	        </div>
	        <div class="trust-badge-text">
	          <strong>Secure Payments</strong>
	          SSL encrypted checkout
	        </div>
	      </div>
	      <div class="trust-badge">
	        <div class="trust-badge-icon">
	          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
	        </div>
	        <div class="trust-badge-text">
	          <strong>Worldwide Shipping</strong>
	          40+ countries delivered
	        </div>
	      </div>
	      <div class="trust-badge">
	        <div class="trust-badge-icon">
	          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
	        </div>
	        <div class="trust-badge-text">
	          <strong>Premium Quality</strong>
	          Certified materials
	        </div>
	      </div>
	      <div class="trust-badge">
	        <div class="trust-badge-icon">
	          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
	        </div>
	        <div class="trust-badge-text">
	          <strong>Expert Support</strong>
	          Dedicated design team
	        </div>
	      </div>
	    </div>
	  </div>

	  <?php
	  $copyright_image = get_option( 'eternal_general_copyright_image', '' );
	  $copyright_text  = get_option( 'eternal_general_copyright_text', '' );
	  if ( '' === trim( $copyright_text ) ) {
	    $copyright_text = '&copy; ' . gmdate( 'Y' ) . ' <a href="' . esc_url( home_url( '/' ) ) . '">' . get_bloginfo( 'name' ) . '</a>. All rights reserved.';
	  }
	  $legal_links = get_option( 'eternal_general_legal_links', array() );
	  if ( empty( $legal_links ) || ! is_array( $legal_links ) ) {
	    $legal_links = array(
	      array( 'label' => 'Privacy Policy', 'url' => '#' ),
	      array( 'label' => 'Terms of Service', 'url' => '#' ),
	      array( 'label' => 'Cookie Settings', 'url' => '#' ),
	    );
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
