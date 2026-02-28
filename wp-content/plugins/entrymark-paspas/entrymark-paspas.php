<?php
/**
 * Plugin Name: Entry Mark Paspas
 * Plugin URI:  https://entrymark.com
 * Description: Paspas özelleştirici ve satış eklentisi. Admin’den dokular (yüzey görselleri), ölçüler ve fiyatlar; frontend’de özelleştirme; PayTR ödeme.
 * Version:     1.0.1
 * Author:      Entry Mark
 * Text Domain: entrymark-paspas
 * Requires at least: 5.9
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EMC_VERSION', '1.0.1' );
define( 'EMC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EMC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'EMC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Ana loader: admin, REST, frontend, sepete ekleme / sipariş (ileride).
 */
function entrymark_paspas_init() {
	// Sepet (session/transient)
	require_once EMC_PLUGIN_DIR . 'includes/class-emc-cart.php';
	// Basit sepet işlemleri (form submit)
	require_once EMC_PLUGIN_DIR . 'includes/class-emc-cart-simple.php';
	// Checkout: sipariş oluşturma
	require_once EMC_PLUGIN_DIR . 'includes/class-emc-checkout.php';
	// PayTR: token, callback
	require_once EMC_PLUGIN_DIR . 'includes/class-emc-paytr.php';
	add_action( 'init', 'entrymark_paspas_paytr_callback', 1 );
	// REST API: özelleştirici config, sepet, checkout
	require_once EMC_PLUGIN_DIR . 'includes/class-emc-rest.php';
	EMC_REST::register();

	// Frontend: shortcode, script/style, localized config
	require_once EMC_PLUGIN_DIR . 'includes/class-emc-frontend.php';
	EMC_Frontend::init();

	if ( is_admin() ) {
		require_once EMC_PLUGIN_DIR . 'includes/class-emc-admin.php';
		EMC_Admin::init();
	}
}

add_action( 'plugins_loaded', 'entrymark_paspas_init' );

/**
 * Ondalık option değeri (virgüllü string destekli: "20,45" → 20.45).
 *
 * @param string $key    Option adı.
 * @param mixed  $default Varsayılan değer.
 * @return float
 */
function emc_get_float_option( $key, $default = 0 ) {
	$v = get_option( $key, $default );
	if ( is_string( $v ) ) {
		$v = str_replace( array( ',', ' ' ), array( '.', '' ), $v );
	}
	return (float) $v;
}

/**
 * Fiyatı Türk Lirası formatında göster.
 *
 * @param float $price Fiyat.
 * @return string
 */
function emc_format_price( $price ) {
	$num = (float) $price;
	return number_format( $num, 2, ',', '.' ) . ' TL';
}

/**
 * Sipariş CPT: emc_order (private)
 */
function entrymark_paspas_register_order_cpt() {
	register_post_type( 'emc_order', array(
		'labels'             => array(
			'name'               => _x( 'Siparişler', 'post type general name', 'entrymark-paspas' ),
			'singular_name'      => _x( 'Sipariş', 'post type singular name', 'entrymark-paspas' ),
			'menu_name'          => _x( 'Siparişler', 'admin menu', 'entrymark-paspas' ),
			'add_new'            => _x( 'Yeni Ekle', 'emc_order', 'entrymark-paspas' ),
			'add_new_item'       => __( 'Yeni Sipariş', 'entrymark-paspas' ),
			'edit_item'          => __( 'Siparişi Düzenle', 'entrymark-paspas' ),
			'view_item'          => __( 'Siparişi Görüntüle', 'entrymark-paspas' ),
			'all_items'          => __( 'Siparişler', 'entrymark-paspas' ),
			'search_items'       => __( 'Sipariş Ara', 'entrymark-paspas' ),
			'not_found'          => __( 'Sipariş bulunamadı.', 'entrymark-paspas' ),
			'not_found_in_trash' => __( 'Çöp kutusunda sipariş yok.', 'entrymark-paspas' ),
		),
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'hierarchical'        => false,
		'supports'            => array( 'title' ),
		'has_archive'         => false,
		'rewrite'             => false,
		'query_var'            => false,
	) );
}
add_action( 'init', 'entrymark_paspas_register_order_cpt' );

function entrymark_paspas_paytr_callback() {
	if ( isset( $_GET['emc_paytr_callback'] ) && $_GET['emc_paytr_callback'] === '1' ) {
		EMC_PayTR::handle_callback();
	}
}

/**
 * REST API: Giriş yapmadan da erişime izin ver
 */
add_filter( 'rest_authentication_errors', function( $result ) {
	// Entrymark namespace'i için authentication hatalarını görmezden gel
	if ( ! empty( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], 'entrymark-paspas/v1' ) !== false ) {
		return null; // Hata yok, devam et
	}
	return $result;
}, 20 );

/**
 * Aktivasyon: varsayılan seçenekler
 */
function entrymark_paspas_activate() {
	$defaults = array(
		'emc_price_per_m2'       => 2050,
		'emc_min_total'          => 200,
		'emc_tax_rate'           => 10,
		'emc_custom_size_min_w'  => 10,
		'emc_custom_size_min_h'  => 10,
		'emc_custom_size_max_w'  => 500,
		'emc_custom_size_max_h'  => 500,
		'emc_custom_size_enabled'=> 1,
		'emc_text_max_length'    => 100,
		'emc_logo_max_mb'        => 5,
		'emc_paytr_test_mode'   => 1,
	);
	foreach ( $defaults as $key => $value ) {
		if ( get_option( $key ) === false ) {
			add_option( $key, $value );
		}
	}
	// Gönderim seçenekleri (serialize)
	if ( get_option( 'emc_shipping_options' ) === false ) {
		add_option( 'emc_shipping_options', array(
			array( 'id' => 'standard', 'label' => 'Standart', 'extra' => 0, 'days' => '5-7' ),
			array( 'id' => 'express', 'label' => 'Express 72 saat', 'extra' => 1500, 'days' => '2-3' ),
		) );
	}
	// Sepet sayfasını oluştur
	$cart_page = get_page_by_path( 'sepet' );
	if ( ! $cart_page ) {
		$cart_page_id = wp_insert_post( array(
			'post_title'   => 'Sepetim',
			'post_name'    => 'sepet',
			'post_content' => '[entrymark_paspas_cart]',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_author'  => 1,
		) );
		if ( ! is_wp_error( $cart_page_id ) ) {
			update_option( 'emc_cart_page_id', $cart_page_id );
		}
	}

	// Ölçü setleri (serialize) – customizer.html ile uyumlu
	if ( get_option( 'emc_sizes_horizontal' ) === false ) {
		$h = array( array( 40, 70 ), array( 50, 70 ), array( 50, 90 ), array( 50, 100 ), array( 60, 90 ), array( 60, 100 ), array( 60, 120 ), array( 70, 100 ), array( 70, 120 ), array( 80, 120 ), array( 80, 150 ), array( 100, 150 ), array( 100, 200 ), array( 120, 200 ), array( 140, 200 ), array( 150, 200 ) );
		add_option( 'emc_sizes_horizontal', $h );
	}
	if ( get_option( 'emc_sizes_vertical' ) === false ) {
		$v = array( array( 70, 40 ), array( 70, 50 ), array( 90, 50 ), array( 100, 50 ), array( 90, 60 ), array( 100, 60 ), array( 120, 60 ), array( 100, 70 ), array( 120, 70 ), array( 120, 80 ), array( 150, 80 ), array( 150, 100 ) );
		add_option( 'emc_sizes_vertical', $v );
	}
	if ( get_option( 'emc_sizes_round' ) === false ) {
		$r = array( array( 60, 60 ), array( 70, 70 ), array( 80, 80 ), array( 90, 90 ), array( 100, 100 ), array( 120, 120 ), array( 150, 150 ), array( 200, 200 ) );
		add_option( 'emc_sizes_round', $r );
	}
}

register_activation_hook( __FILE__, 'entrymark_paspas_activate' );
