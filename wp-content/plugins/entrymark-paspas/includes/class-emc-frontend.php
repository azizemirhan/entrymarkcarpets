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
	}

	/**
	 * Özelleştirici sayfasında body class; sidebar gizleme ve tam genişlik için.
	 */
	public static function body_class_customizer_page( $classes ) {
		global $post;
		if ( $post && is_singular() && has_shortcode( $post->post_content, 'entrymark_paspas_customizer' ) ) {
			$classes[] = 'emc-customizer-page';
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
		if ( ! $post || ! has_shortcode( $post->post_content, 'entrymark_paspas_checkout' ) ) {
			return;
		}
		wp_enqueue_script(
			'emc-checkout-config',
			EMC_PLUGIN_URL . 'assets/frontend/config-loader.js',
			array(),
			EMC_VERSION,
			true
		);
		wp_localize_script( 'emc-checkout-config', 'EMC_REST_DATA', array(
			'rest_url' => rest_url( 'entrymark-paspas/v1' ),
			'nonce'    => wp_create_nonce( 'emc_cart' ),
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
			'nonce'        => wp_create_nonce( 'emc_cart' ),
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
		wp_enqueue_style( 'emc-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@400;500;600&family=Lato:wght@400;700&family=Open+Sans:wght@400;600;700&family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Source+Sans+3:wght@400;600;700&display=swap', array(), null );
	}

	public static function enqueue_for_checkout() {
		global $post;
		if ( ! $post || ! has_shortcode( $post->post_content, 'entrymark_paspas_checkout' ) ) {
			return;
		}
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
			'rest_url' => rest_url( 'entrymark-paspas/v1' ),
			'nonce'    => wp_create_nonce( 'emc_cart' ),
		) );
		
		wp_enqueue_style( 'emc-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap', array(), null );
	}
}
