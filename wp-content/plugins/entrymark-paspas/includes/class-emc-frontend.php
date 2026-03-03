<?php
/**
 * Frontend: shortcode, customizer sayfası, config enjeksiyonu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EMC_Frontend {

	public static function init() {
		add_shortcode( 'entrymark_paspas_customizer', array( __CLASS__, 'shortcode' ) );
		add_shortcode( 'entrymark_paspas_checkout', array( __CLASS__, 'shortcode_checkout' ) );
		add_shortcode( 'entrymark_paspas_cart', array( __CLASS__, 'shortcode_cart' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_for_shortcode' ), 20 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_for_checkout' ), 20 );
		add_filter( 'body_class', array( __CLASS__, 'body_class_customizer_page' ) );
		add_filter( 'the_title', array( __CLASS__, 'hide_title_on_customizer_page' ), 10, 2 );
		add_filter( 'the_content', array( __CLASS__, 'inject_checkout_on_odeme_page' ), 5 );
	}

	/**
	 * Mevcut sayfa ödeme sayfası mı? (slug odeme veya atanmış checkout sayfası)
	 */
	private static function is_checkout_page() {
		global $post;
		if ( ! $post || ! is_singular( 'page' ) ) {
			return false;
		}
		$checkout_page_id = (int) get_option( 'emc_checkout_page_id', 0 );
		if ( $checkout_page_id && (int) $post->ID === $checkout_page_id ) {
			return true;
		}
		if ( $post->post_name === 'odeme' || $post->post_name === 'checkout' ) {
			return true;
		}
		return false;
	}

	/**
	 * Ödeme sayfasında (slug: odeme) shortcode yoksa checkout formunu göster.
	 */
	public static function inject_checkout_on_odeme_page( $content ) {
		if ( ! self::is_checkout_page() ) {
			return $content;
		}
		if ( has_shortcode( $content, 'entrymark_paspas_checkout' ) ) {
			return $content;
		}
		return do_shortcode( '[entrymark_paspas_checkout]' );
	}

	/**
	 * Özelleştirici sayfasında body class; sidebar gizleme ve tam genişlik için.
	 */
	public static function body_class_customizer_page( $classes ) {
		global $post;
		if ( $post && is_singular() && has_shortcode( $post->post_content, 'entrymark_paspas_customizer' ) ) {
			$classes[] = 'emc-customizer-page';
		}
		if ( self::is_checkout_page() ) {
			$classes[] = 'emc-checkout-page';
		}
		return $classes;
	}

	/**
	 * Özelleştirici ve sepet sayfasında sayfa başlığını (logo altındaki başlığı) gizle.
	 */
	public static function hide_title_on_customizer_page( $title, $post_id = null ) {
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $title;
		}
		$post = $post_id ? get_post( $post_id ) : get_post();
		if ( ! $post ) {
			return $title;
		}
		// Özelleştirici sayfası
		if ( has_shortcode( $post->post_content, 'entrymark_paspas_customizer' ) ) {
			return '';
		}
		// Sepet sayfası (shortcode veya slug ile)
		if ( has_shortcode( $post->post_content, 'entrymark_paspas_cart' ) || $post->post_name === 'sepet' ) {
			return '';
		}
		// Ödeme sayfası (shortcode veya slug ile)
		if ( has_shortcode( $post->post_content, 'entrymark_paspas_checkout' ) || self::is_checkout_page() ) {
			return '';
		}
		return $title;
	}

	/**
	 * Shortcode: [entrymark_paspas_customizer]
	 * Config ve script'ler wp_enqueue_scripts'ta (enqueue_for_shortcode) yüklenir; bağımlılık hatası önlenir.
	 */
	public static function shortcode() {
		ob_start();
		load_template( EMC_PLUGIN_DIR . 'templates/shortcode-customizer.php', false );
		return ob_get_clean();
	}

	/**
	 * Shortcode: [entrymark_paspas_checkout]
	 */
	public static function shortcode_checkout() {
		self::maybe_inject_checkout_data();
		ob_start();
		load_template( EMC_PLUGIN_DIR . 'templates/shortcode-checkout.php', false );
		return ob_get_clean();
	}

	private static function maybe_inject_checkout_data() {
		global $post;
		$has_shortcode = $post && has_shortcode( $post->post_content, 'entrymark_paspas_checkout' );
		if ( ! $has_shortcode && ! self::is_checkout_page() ) {
			return;
		}
		wp_enqueue_script(
			'emc-checkout-config',
			EMC_PLUGIN_URL . 'assets/frontend/config-loader.js',
			array(),
			EMC_VERSION,
			true
		);
		// Sayfa yüklenirken cookie ile alınan cart_id'yi JS'e ver; fetch cookie göndermese bile sepete erişilebilsin.
		$cart_id = class_exists( 'EMC_Cart' ) ? EMC_Cart::get_cart_id() : '';
		wp_localize_script( 'emc-checkout-config', 'EMC_REST_DATA', array(
			'rest_url'  => rest_url( 'entrymark-paspas/v1' ),
			'nonce'     => wp_create_nonce( 'wp_rest' ),
			'emc_nonce' => wp_create_nonce( 'emc_cart' ),
			'cart_id'   => $cart_id ?: '',
		) );
	}

	public static function enqueue_for_shortcode() {
		global $post;
		if ( ! $post || ! has_shortcode( $post->post_content, 'entrymark_paspas_customizer' ) ) {
			return;
		}
		$config = EMC_REST::get_config_data();
		wp_enqueue_script(
			'emc-config',
			EMC_PLUGIN_URL . 'assets/frontend/config-loader.js',
			array(),
			EMC_VERSION,
			true
		);
		// WP 5.7+ wp_localize_script yalnızca dizi kabul ediyor; karmaşık config için inline script kullanıyoruz.
		$config_json = wp_json_encode( $config );
		wp_add_inline_script( 'emc-config', 'var EMC_CONFIG = ' . $config_json . ';', 'before' );
		$checkout_page_id = get_option( 'emc_checkout_page_id', 0 );
		$checkout_url     = $checkout_page_id ? get_permalink( (int) $checkout_page_id ) : '';
		$cart_page_id     = get_option( 'emc_cart_page_id', 0 );
		$cart_url         = $cart_page_id ? get_permalink( (int) $cart_page_id ) : home_url( '/sepet' );
		wp_localize_script( 'emc-config', 'EMC_REST_DATA', array(
			'rest_url'     => rest_url( 'entrymark-paspas/v1' ),
			'nonce'        => wp_create_nonce( 'wp_rest' ),
			'emc_nonce'    => wp_create_nonce( 'emc_cart' ),
			'checkout_url' => $checkout_url ?: '',
			'cart_url'     => $cart_url,
		) );
		wp_add_inline_script( 'emc-config', 'var EMC_THEME_URL = ' . wp_json_encode( get_template_directory_uri() ) . ';', 'before' );
		wp_enqueue_style(
			'emc-customizer',
			EMC_PLUGIN_URL . 'assets/frontend/customizer.css',
			array(),
			EMC_VERSION
		);
		// Tam customizer.js (customizer.html’den taşınacak) ileride eklenecek
		wp_enqueue_script(
			'emc-customizer',
			EMC_PLUGIN_URL . 'assets/frontend/customizer.js',
			array( 'emc-config' ),
			EMC_VERSION,
			true
		);
		// Fontlar: base (italic destekli serif), el yazısı 1 ve 2 (URL uzunluğu için ayrı)
		wp_enqueue_style( 'emc-fonts-base', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Lora:ital,wght@0,400;0,700;1,400;1,700&family=Crimson+Text:ital,wght@0,400;0,600;1,400;1,600&family=Outfit:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@400;500;600&family=Lato:wght@400;700&family=Open+Sans:wght@400;600;700&family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Source+Sans+3:wght@400;600;700&display=swap', array(), null );
		wp_enqueue_style( 'emc-fonts-handwritten', 'https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Pacifico&family=Great+Vibes&family=Caveat:wght@400;500;600;700&family=Satisfy&family=Cookie&family=Kalam:wght@300;400;700&family=Amatic+SC:wght@400;700&family=Indie+Flower&family=Sacramento&family=Patrick+Hand&family=Gloria+Hallelujah&family=Caveat+Brush&family=Permanent+Marker&family=Handlee&display=swap', array( 'emc-fonts-base' ), null );
		wp_enqueue_style( 'emc-fonts-handwritten-2', 'https://fonts.googleapis.com/css2?family=Allura&family=Mr+Dafoe&family=Marck+Script&family=Shadows+Into+Light&family=Nothing+You+Could+Do&family=Architects+Daughter&family=Covered+By+Your+Grace&family=Courgette&family=Yellowtail&family=Bad+Script&family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Sriracha&family=Neucha&family=Coming+Soon&family=Reenie+Beanie&family=Rock+Salt&display=swap', array( 'emc-fonts-handwritten' ), null );
	}

	public static function enqueue_for_checkout() {
		global $post;
		$has_shortcode = $post && has_shortcode( $post->post_content, 'entrymark_paspas_checkout' );
		if ( ! $has_shortcode && ! self::is_checkout_page() ) {
			return;
		}
		self::maybe_inject_checkout_data();
		wp_enqueue_style(
			'emc-checkout',
			EMC_PLUGIN_URL . 'assets/frontend/checkout.css',
			array(),
			EMC_VERSION
		);
		wp_enqueue_script(
			'emc-checkout',
			EMC_PLUGIN_URL . 'assets/frontend/checkout.js',
			array( 'emc-checkout-config' ),
			EMC_VERSION,
			true
		);
	}

	/**
	 * Shortcode: [entrymark_paspas_cart]
	 */
	public static function shortcode_cart() {
		self::enqueue_for_cart();
		ob_start();
		load_template( EMC_PLUGIN_DIR . 'templates/page-cart.php', false );
		return ob_get_clean();
	}

	public static function enqueue_for_cart() {
		wp_enqueue_style(
			'emc-cart',
			EMC_PLUGIN_URL . 'assets/frontend/cart.css',
			array(),
			EMC_VERSION
		);
		
		wp_enqueue_script(
			'emc-cart',
			EMC_PLUGIN_URL . 'assets/frontend/cart.js',
			array(),
			EMC_VERSION,
			true
		);
		
		wp_localize_script( 'emc-cart', 'EMC_REST_DATA', array(
			'rest_url'  => rest_url( 'entrymark-paspas/v1' ),
			'nonce'     => wp_create_nonce( 'wp_rest' ),
			'emc_nonce' => wp_create_nonce( 'emc_cart' ),
		) );
		
		wp_enqueue_style( 'emc-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap', array(), null );
	}
}
