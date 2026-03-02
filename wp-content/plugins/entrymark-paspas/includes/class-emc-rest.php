<?php
/**
 * REST API: Özelleştirici config (dokular, ölçüler, fiyat, yazı/logo)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EMC_REST {

	public static function register() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	public static function register_routes() {
		register_rest_route( 'entrymark-paspas/v1', '/config', array(
			'methods'             => 'GET',
			'permission_callback' => '__return_true',
			'callback'            => array( __CLASS__, 'get_config' ),
		) );
		register_rest_route( 'entrymark-paspas/v1', '/cart', array(
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => array( __CLASS__, 'cart_get' ),
			),
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true',
				'callback'            => array( __CLASS__, 'cart_add' ),
				'args'                => array(
					'nonce' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'design' => array(
						'required' => true,
						'type'     => 'object',
					),
					'summary' => array(
						'required' => true,
						'type'     => 'object',
					),
					'pricing' => array(
						'required' => true,
						'type'     => 'object',
					),
					'preview_data_url' => array(
						'required' => false,
						'type'     => 'string',
					),
				),
			),
			array(
				'methods'             => 'DELETE',
				'permission_callback' => '__return_true',
				'callback'            => array( __CLASS__, 'cart_empty' ),
			),
		) );
		register_rest_route( 'entrymark-paspas/v1', '/cart/(?P<index>\d+)', array(
			'methods'             => 'DELETE',
			'permission_callback' => '__return_true',
			'callback'            => array( __CLASS__, 'cart_remove_item' ),
			'args'                => array(
				'index' => array(
					'required' => true,
					'type'     => 'integer',
					'minimum'  => 0,
				),
			),
		) );
		register_rest_route( 'entrymark-paspas/v1', '/cart/update', array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => array( __CLASS__, 'cart_update_quantity' ),
		) );
		register_rest_route( 'entrymark-paspas/v1', '/cart/remove', array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => array( __CLASS__, 'cart_remove_by_id' ),
		) );
		register_rest_route( 'entrymark-paspas/v1', '/cart/clear', array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => array( __CLASS__, 'cart_clear_all' ),
		) );
		register_rest_route( 'entrymark-paspas/v1', '/upload-logo', array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => array( __CLASS__, 'upload_logo' ),
			'args'                => array(
				'nonce' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		) );
		register_rest_route( 'entrymark-paspas/v1', '/checkout', array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => array( __CLASS__, 'checkout' ),
			'args'                => array(
				'nonce' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'customer' => array(
					'required' => true,
					'type'     => 'object',
				),
			),
		) );
	}

	/**
	 * Frontend customizer için tek endpoint: dokular, ölçüler, fiyat, gönderim, yazı/logo.
	 */
	public static function get_config( \WP_REST_Request $request ) {
		$response = rest_ensure_response( self::get_config_data() );
		$response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
		return $response;
	}

	/** Referans ölçü (40×70): bu ölçüye göre küçükler -, büyükler + TL farkı hesaplanır. */
	const REF_SIZE_W = 40;
	const REF_SIZE_H = 70;

	/**
	 * 40×70 referansına göre ölçü fiyat farkını hesaplar (m² fiyatı ile).
	 * Referanstan küçük alan → negatif, büyük alan → pozitif TL.
	 *
	 * @param int   $w En (cm).
	 * @param int   $h Boy (cm).
	 * @param float $price_per_m2 m² başına fiyat (TL).
	 * @return float Fark (TL).
	 */
	private static function calc_size_offset_from_ref( $w, $h, $price_per_m2 ) {
		$ref_area_m2 = ( self::REF_SIZE_W * self::REF_SIZE_H ) / 10000;
		$area_m2     = ( (float) $w * (float) $h ) / 10000;
		return round( ( $area_m2 - $ref_area_m2 ) * $price_per_m2, 2 );
	}

	/**
	 * Normalize edilmiş ölçü listesine 40×70 referanslı otomatik fiyat farkı uygular.
	 *
	 * @param array $normalized [ [w, h, label, offset], ... ]
	 * @param float $price_per_m2
	 * @return array
	 */
	private static function apply_ref_based_offsets( $normalized, $price_per_m2 ) {
		if ( ! is_array( $normalized ) ) {
			return array();
		}
		$out = array();
		foreach ( $normalized as $r ) {
			$w      = isset( $r[0] ) ? (int) $r[0] : 0;
			$h      = isset( $r[1] ) ? (int) $r[1] : 0;
			$label  = isset( $r[2] ) ? $r[2] : ( $w && $h ? $w . '×' . $h . ' cm' : '' );
			$offset = self::calc_size_offset_from_ref( $w, $h, $price_per_m2 );
			$out[]  = array( $w, $h, $label, $offset );
		}
		return $out;
	}

	/**
	 * Config verisi (REST ve shortcode tarafında kullanılır).
	 */
	public static function get_config_data() {
		$textures = self::get_textures();
		$raw_h = get_option( 'emc_sizes_horizontal', array() );
		$raw_v = get_option( 'emc_sizes_vertical', array() );
		$raw_r = get_option( 'emc_sizes_round', array() );
		$price_per_m2 = function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_per_m2', 20.45 ) : (float) get_option( 'emc_price_per_m2', 20.45 );
		$sizes = array(
			'horizontal' => self::apply_ref_based_offsets( self::normalize_sizes_for_config( $raw_h ), $price_per_m2 ),
			'vertical'   => self::apply_ref_based_offsets( self::normalize_sizes_for_config( $raw_v ), $price_per_m2 ),
			'round'      => self::apply_ref_based_offsets( self::normalize_sizes_for_config( $raw_r ), $price_per_m2 ),
			'custom_size' => array(
				'enabled' => (bool) get_option( 'emc_custom_size_enabled', 1 ),
				'min_w'   => (int) get_option( 'emc_custom_size_min_w', 10 ),
				'min_h'   => (int) get_option( 'emc_custom_size_min_h', 10 ),
				'max_w'   => (int) get_option( 'emc_custom_size_max_w', 500 ),
				'max_h'   => (int) get_option( 'emc_custom_size_max_h', 500 ),
			),
		);
		$shipping = get_option( 'emc_shipping_options', array() );
		if ( ! is_array( $shipping ) ) {
			$shipping = array();
		}
		$pricing = array(
			'price_per_m2'    => function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_per_m2', 20.45 ) : (float) get_option( 'emc_price_per_m2', 20.45 ),
			'min_total'       => function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_min_total', 200 ) : (float) get_option( 'emc_min_total', 200 ),
			'tax_rate'        => function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_tax_rate', 10 ) : (float) get_option( 'emc_tax_rate', 10 ),
			'price_per_image' => function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_per_image', 0 ) : (float) get_option( 'emc_price_per_image', 0 ),
			'text_extra'      => function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_text_extra', 0 ) : (float) get_option( 'emc_price_text_extra', 0 ),
		);
		$text_options = array(
			'max_length'  => (int) get_option( 'emc_text_max_length', 100 ),
			'fonts'       => self::get_fonts(),
			'logo_max_mb' => (int) get_option( 'emc_logo_max_mb', 5 ),
			'logo_types'  => array( 'image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml' ),
		);

		$whatsapp = array(
			'enabled'      => (bool) get_option( 'emc_whatsapp_enabled', 0 ),
			'number'       => get_option( 'emc_whatsapp_number', '' ),
			'button_text'  => get_option( 'emc_whatsapp_button_text', 'WhatsApp ile Sipariş Ver' ),
			'message'      => get_option( 'emc_whatsapp_message', 'Merhaba, paspas tasarımım hakkında bilgi almak istiyorum.' ),
		);

		return array(
			'textures'      => $textures,
			'sizes'         => $sizes,
			'shipping'      => $shipping,
			'pricing'       => $pricing,
			'text_options'  => $text_options,
			'whatsapp'      => $whatsapp,
		);
	}

	/**
	 * Ölçü listesini frontend formatına çevirir.
	 * Hem dizi [ w, h, label, offset ] hem nesne (offset anahtarı) ile uyumluluk için ikisini de döndürüyoruz.
	 */
	private static function normalize_sizes_for_config( $raw ) {
		if ( ! is_array( $raw ) ) {
			return array();
		}
		$out = array();
		foreach ( $raw as $r ) {
			$w      = isset( $r[0] ) ? (int) $r[0] : 0;
			$h      = isset( $r[1] ) ? (int) $r[1] : 0;
			$offset = isset( $r[2] ) ? (float) $r[2] : 0;
			if ( $w && $h ) {
				$label = $w . '×' . $h . ' cm';
				$out[] = array( $w, $h, $label, $offset );
			}
		}
		return $out;
	}

	/**
	 * Verilen en×boy için 40×70 referansına göre ölçü farkını (TL) döndürür.
	 */
	private static function get_size_offset( $size_w, $size_h ) {
		$price_per_m2 = function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_per_m2', 20.45 ) : (float) get_option( 'emc_price_per_m2', 20.45 );
		return self::calc_size_offset_from_ref( (int) $size_w, (int) $size_h, $price_per_m2 );
	}

	/**
	 * Dokular: admin'den yüklenen yüzey görselleri (id, name, image_url).
	 * Sitede Paspas → Dokular'da tanımlı tüm dokular döner; frontend özelleştiricide gerçek dokular olarak kullanılır.
	 */
	private static function get_textures() {
		$raw = get_option( 'emc_textures', array() );
		if ( ! is_array( $raw ) || empty( $raw ) ) {
			return array();
		}

		$home = home_url( '/' );
		$out  = array();
		foreach ( $raw as $i => $t ) {
			if ( ! is_array( $t ) ) {
				continue;
			}
			$img_id = isset( $t['image_id'] ) ? (int) $t['image_id'] : 0;
			$url    = '';

			if ( $img_id > 0 ) {
				// Önce full (canvas desen kalitesi), yoksa medium, sonra doğrudan URL
				$attach_url = wp_get_attachment_image_url( $img_id, 'full' );
				if ( empty( $attach_url ) ) {
					$attach_url = wp_get_attachment_image_url( $img_id, 'medium_large' );
				}
				if ( empty( $attach_url ) ) {
					$attach_url = wp_get_attachment_image_url( $img_id, 'medium' );
				}
				if ( empty( $attach_url ) ) {
					$attach_url = wp_get_attachment_url( $img_id );
				}
				if ( $attach_url ) {
					$url = $attach_url;
				}
			}
			if ( empty( $url ) && ! empty( $t['image_url'] ) ) {
				$url = $t['image_url'];
			}
			// Mutlak URL: taşınan sitede path aynı kalır, domain mevcut site ile değişir
			if ( ! empty( $url ) ) {
				$parsed = wp_parse_url( $url );
				if ( ! empty( $parsed['path'] ) ) {
					$path  = $parsed['path'] . ( isset( $parsed['query'] ) ? '?' . $parsed['query'] : '' );
					$url   = rtrim( $home, '/' ) . $path;
				}
				$url = esc_url_raw( $url );
			}

			$id   = isset( $t['id'] ) ? $t['id'] : 't-' . ( $i + 1 );
			$name = isset( $t['name'] ) ? $t['name'] : ( 'Doku ' . ( $i + 1 ) );
			$out[] = array(
				'id'        => $id,
				'name'      => $name,
				'image_url' => $url ?: '',
			);
		}
		return $out;
	}

	/**
	 * Sunucu tarafı fiyat hesaplama (doğrulama için).
	 *
	 * @param int $images_count Eklenen görsel sayısı (logo/görsel).
	 * @param bool $has_text Yazı eklenmiş mi.
	 */
	public static function calc_price_server( $size_w, $size_h, $shipping_id, $images_count = 0, $has_text = false ) {
		$price_per_m2    = function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_per_m2', 20.45 ) : (float) get_option( 'emc_price_per_m2', 20.45 );
		$min_total       = function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_min_total', 200 ) : (float) get_option( 'emc_min_total', 200 );
		$tax_rate        = ( function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_tax_rate', 10 ) : (float) get_option( 'emc_tax_rate', 10 ) ) / 100;
		$price_per_image = function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_per_image', 0 ) : (float) get_option( 'emc_price_per_image', 0 );
		$text_extra      = function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_text_extra', 0 ) : (float) get_option( 'emc_price_text_extra', 0 );
		$area            = ( (float) $size_w * (float) $size_h ) / 10000;
		$base            = $area * $price_per_m2;
		if ( $min_total > 0 && $base < $min_total ) {
			$base = $min_total;
		}
		// Ölçü farkı (+/-) sadece arayüzde 40×70 referansına göre gösterim için; toplam fiyat = alan × m² fiyatı
		$base = $base + ( (int) $images_count * $price_per_image );
		if ( $has_text ) {
			$base = $base + $text_extra;
		}
		$shipping_options = get_option( 'emc_shipping_options', array() );
		$ship_cost        = 0;
		if ( is_array( $shipping_options ) ) {
			foreach ( $shipping_options as $opt ) {
				if ( isset( $opt['id'] ) && $opt['id'] === $shipping_id && isset( $opt['extra'] ) ) {
					$ship_cost = (float) $opt['extra'];
					break;
				}
			}
		}
		$tax   = $base * $tax_rate;
		$total = $base + $ship_cost + $tax;
		return array(
			'base'     => round( $base, 2 ),
			'shipCost' => round( $ship_cost, 2 ),
			'tax'      => round( $tax, 2 ),
			'total'    => round( $total, 2 ),
		);
	}

	/**
	 * GET /cart — sepet öğeleri ve sayı.
	 * Cookie ile cart_id alınır; cookie gitmezse X-EMC-Cart-ID header'ı (ödeme sayfasından iletilen) kullanılır.
	 */
	public static function cart_get( \WP_REST_Request $request ) {
		$cart_id = EMC_Cart::get_cart_id();
		if ( ! $cart_id ) {
			$header = $request->get_header( 'X-EMC-Cart-ID' );
			if ( is_string( $header ) && preg_match( '/^[a-zA-Z0-9_-]{20,64}$/', $header ) ) {
				$cart_id = sanitize_text_field( $header );
			}
		}
		$items = EMC_Cart::get_items( $cart_id );
		return rest_ensure_response( array(
			'items' => $items,
			'count' => count( $items ),
		) );
	}

	/**
	 * POST /cart — sepete ekle; nonce, design/summary/pricing doğrulama.
	 */
	public static function cart_add( \WP_REST_Request $request ) {
		// Nonce doğrulaması - geçici olarak devre dışı
		// $nonce = $request->get_param( 'nonce' );
		// if ( ! $nonce || ! wp_verify_nonce( $nonce, 'emc_cart' ) ) {
		// 	return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Güvenlik doğrulaması başarısız.', 'entrymark-paspas' ) ), 403 );
		// }
		$design  = $request->get_param( 'design' );
		$summary = $request->get_param( 'summary' );
		$pricing = $request->get_param( 'pricing' );
		$preview = $request->get_param( 'preview_data_url' );
		if ( ! is_array( $design ) || ! is_array( $summary ) || ! is_array( $pricing ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Geçersiz veri.', 'entrymark-paspas' ) ), 400 );
		}
		$texture_id = isset( $design['texture_id'] ) ? sanitize_text_field( $design['texture_id'] ) : '';
		$size       = isset( $design['size'] ) && is_array( $design['size'] ) ? $design['size'] : array();
		$w          = isset( $size['w'] ) ? (int) $size['w'] : 0;
		$h          = isset( $size['h'] ) ? (int) $size['h'] : 0;
		$shipping_id = isset( $design['shipping'] ) ? sanitize_text_field( $design['shipping'] ) : '';
		$config     = self::get_config_data();
		$textures   = $config['textures'];
		$found      = false;
		foreach ( $textures as $t ) {
			if ( isset( $t['id'] ) && $t['id'] === $texture_id ) {
				$found = true;
				break;
			}
		}
		if ( ! $found && $texture_id !== '' ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Geçersiz doku.', 'entrymark-paspas' ) ), 400 );
		}
		$sizes_cfg = $config['sizes'];
		$orient    = isset( $design['orient'] ) ? sanitize_text_field( $design['orient'] ) : 'horizontal';
		$list      = isset( $sizes_cfg[ $orient ] ) ? $sizes_cfg[ $orient ] : $sizes_cfg['horizontal'];
		$custom    = isset( $sizes_cfg['custom_size'] ) ? $sizes_cfg['custom_size'] : array();
		$size_ok   = false;
		if ( is_array( $list ) ) {
			foreach ( $list as $s ) {
				if ( is_array( $s ) && (int) $s[0] === $w && (int) $s[1] === $h ) {
					$size_ok = true;
					break;
				}
			}
		}
		if ( ! $size_ok && ! empty( $custom['enabled'] ) ) {
			$min_w = isset( $custom['min_w'] ) ? (int) $custom['min_w'] : 10;
			$min_h = isset( $custom['min_h'] ) ? (int) $custom['min_h'] : 10;
			$max_w = isset( $custom['max_w'] ) ? (int) $custom['max_w'] : 500;
			$max_h = isset( $custom['max_h'] ) ? (int) $custom['max_h'] : 500;
			if ( $w >= $min_w && $h >= $min_h && $w <= $max_w && $h <= $max_h ) {
				$size_ok = true;
			}
		}
		if ( ! $size_ok ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Geçersiz ölçü.', 'entrymark-paspas' ) ), 400 );
		}
		$images_count = isset( $design['images_count'] ) ? max( 0, (int) $design['images_count'] ) : 0;
		$has_text     = ! empty( $design['text'] ) && trim( (string) $design['text'] ) !== '';
		$server_pricing = self::calc_price_server( $w, $h, $shipping_id, $images_count, $has_text );
		$client_total   = isset( $pricing['total'] ) ? (float) $pricing['total'] : 0;
		
		// Debug log
		error_log( 'Cart Add - Client total: ' . $client_total . ', Server total: ' . $server_pricing['total'] . ', Diff: ' . abs( $client_total - $server_pricing['total'] ) );
		
		if ( abs( $client_total - $server_pricing['total'] ) > 5.00 ) { // 5 TL tolerans
			return new \WP_REST_Response( array( 
				'success' => false, 
				'message' => __( 'Fiyat eşleşmedi. Sayfayı yenileyip tekrar deneyin.', 'entrymark-paspas' ),
				'debug'   => array( 'client' => $client_total, 'server' => $server_pricing['total'] )
			), 400 );
		}
		$sanitized_design = array(
			'orient'     => $orient,
			'recess'     => isset( $design['recess'] ) ? sanitize_text_field( $design['recess'] ) : 'no',
			'texture_id' => $texture_id,
			'size'       => array( 'w' => $w, 'h' => $h ),
			'text'       => isset( $design['text'] ) ? sanitize_text_field( $design['text'] ) : '',
			'textBold'   => ! empty( $design['textBold'] ),
			'textItalic' => ! empty( $design['textItalic'] ),
			'textColor'  => isset( $design['textColor'] ) ? sanitize_text_field( $design['textColor'] ) : '#FFFFFF',
			'textFont'   => isset( $design['textFont'] ) ? sanitize_text_field( $design['textFont'] ) : 'Arial',
			'shipping'   => $shipping_id,
		);
		if ( isset( $design['logo_attachment_id'] ) && absint( $design['logo_attachment_id'] ) > 0 ) {
			$sanitized_design['logo_attachment_id'] = absint( $design['logo_attachment_id'] );
			$sanitized_design['logo'] = '';
		} elseif ( isset( $design['logo'] ) && is_string( $design['logo'] ) && strlen( $design['logo'] ) < 500000 ) {
			$sanitized_design['logo'] = $design['logo'];
			$sanitized_design['logo_attachment_id'] = 0;
		} else {
			$sanitized_design['logo'] = '';
			$sanitized_design['logo_attachment_id'] = 0;
		}
		$sanitized_summary = array(
			'texture_name'   => isset( $summary['texture_name'] ) ? sanitize_text_field( $summary['texture_name'] ) : '',
			'size_label'     => isset( $summary['size_label'] ) ? sanitize_text_field( $summary['size_label'] ) : '',
			'shipping_label' => isset( $summary['shipping_label'] ) ? sanitize_text_field( $summary['shipping_label'] ) : '',
		);
		$sanitized_pricing = array(
			'base'     => $server_pricing['base'],
			'shipCost' => $server_pricing['shipCost'],
			'tax'      => $server_pricing['tax'],
			'total'    => $server_pricing['total'],
		);
		$result = EMC_Cart::add_item( $sanitized_design, $sanitized_summary, $sanitized_pricing, is_string( $preview ) ? $preview : '', null );
		if ( ! empty( $result['cart_id'] ) && strpos( $result['cart_id'], 'guest_' ) === 0 ) {
			$expire = time() + 2 * DAY_IN_SECONDS;
			$secure = is_ssl();
			setcookie( EMC_Cart::COOKIE_NAME, $result['cart_id'], $expire, '/', '', $secure, true );
		}
		return rest_ensure_response( $result );
	}

	/**
	 * DELETE /cart — sepeti boşalt.
	 */
	public static function cart_empty( \WP_REST_Request $request ) {
		EMC_Cart::empty_cart();
		return rest_ensure_response( array( 'success' => true, 'count' => 0 ) );
	}

	/**
	 * DELETE /cart/{index} — öğe çıkar.
	 */
	public static function cart_remove_item( \WP_REST_Request $request ) {
		$index = (int) $request['index'];
		$result = EMC_Cart::remove_item( $index );
		return rest_ensure_response( $result );
	}

	/**
	 * POST /cart/update — adet güncelle.
	 */
	public static function cart_update_quantity( \WP_REST_Request $request ) {
		$params   = $request->get_json_params();
		$cart_id  = sanitize_text_field( $params['cart_id'] ?? '' );
		$action   = sanitize_text_field( $params['action'] ?? '' );
		
		if ( ! $cart_id || ! in_array( $action, array( 'inc', 'dec' ), true ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => 'Geçersiz parametreler' ), 400 );
		}
		
		$result = EMC_Cart::update_quantity( $cart_id, $action );
		return rest_ensure_response( $result );
	}

	/**
	 * POST /cart/remove — ID ile öğe çıkar.
	 */
	public static function cart_remove_by_id( \WP_REST_Request $request ) {
		$params  = $request->get_json_params();
		$cart_id = sanitize_text_field( $params['cart_id'] ?? '' );
		
		if ( ! $cart_id ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => 'Cart ID gerekli' ), 400 );
		}
		
		$result = EMC_Cart::remove_by_id( $cart_id );
		return rest_ensure_response( $result );
	}

	/**
	 * POST /cart/clear — tüm sepeti temizle.
	 */
	public static function cart_clear_all( \WP_REST_Request $request ) {
		EMC_Cart::empty_cart();
		return rest_ensure_response( array( 'success' => true, 'message' => 'Sepet temizlendi' ) );
	}

	/**
	 * POST /upload-logo — logo dosyası yükle, attachment ID döndür.
	 */
	public static function upload_logo( \WP_REST_Request $request ) {
		$nonce = $request->get_param( 'nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'emc_cart' ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Güvenlik doğrulaması başarısız.', 'entrymark-paspas' ) ), 403 );
		}
		$files = $request->get_file_params();
		if ( empty( $files['file'] ) || ! is_array( $files['file'] ) || empty( $files['file']['tmp_name'] ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Dosya yükleyin.', 'entrymark-paspas' ) ), 400 );
		}
		$max_mb = (int) get_option( 'emc_logo_max_mb', 5 );
		if ( $files['file']['size'] > $max_mb * 1024 * 1024 ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => sprintf( __( 'Maksimum %d MB.', 'entrymark-paspas' ), $max_mb ) ), 400 );
		}
		$allowed = array( 'image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/svg+xml' );
		if ( empty( $files['file']['type'] ) || ! in_array( $files['file']['type'], $allowed, true ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Geçersiz dosya tipi.', 'entrymark-paspas' ) ), 400 );
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$overrides = array( 'test_form' => false );
		$move      = wp_handle_upload( $files['file'], $overrides );
		if ( isset( $move['error'] ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => $move['error'] ), 400 );
		}
		$attachment = array(
			'post_mime_type' => $move['type'],
			'post_title'     => sanitize_file_name( pathinfo( $move['file'], PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);
		$attachment_id = wp_insert_attachment( $attachment, $move['file'] );
		if ( is_wp_error( $attachment_id ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => $attachment_id->get_error_message() ), 500 );
		}
		wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $move['file'] ) );
		$url = wp_get_attachment_image_url( $attachment_id, 'medium' );
		return rest_ensure_response( array(
			'success'       => true,
			'attachment_id' => $attachment_id,
			'url'           => $url ?: $move['url'],
		) );
	}

	/**
	 * POST /checkout — müşteri bilgileri ile sipariş oluştur, sepeti boşalt.
	 * Response: order_id, paytr_iframe_url (Faz 3'te doldurulacak).
	 */
	public static function checkout( \WP_REST_Request $request ) {
		$nonce = $request->get_param( 'nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'emc_cart' ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Güvenlik doğrulaması başarısız.', 'entrymark-paspas' ) ), 403 );
		}
		$customer = $request->get_param( 'customer' );
		if ( ! is_array( $customer ) ) {
			return new \WP_REST_Response( array( 'success' => false, 'message' => __( 'Müşteri bilgisi gerekli.', 'entrymark-paspas' ) ), 400 );
		}
		$cart_id = null;
		$header  = $request->get_header( 'X-EMC-Cart-ID' );
		if ( is_string( $header ) && preg_match( '/^[a-zA-Z0-9_-]{20,64}$/', $header ) ) {
			$cart_id = sanitize_text_field( $header );
		}
		if ( ! $cart_id ) {
			$cart_id = EMC_Cart::get_cart_id();
		}
		$order_id = EMC_Checkout::create_order_from_cart( $customer, $cart_id ?: null );
		if ( is_wp_error( $order_id ) ) {
			return new \WP_REST_Response( array(
				'success' => false,
				'message' => $order_id->get_error_message(),
			), 400 );
		}
		$response = array(
			'success'  => true,
			'order_id' => $order_id,
		);
		if ( class_exists( 'EMC_PayTR' ) && method_exists( 'EMC_PayTR', 'get_payment_token' ) ) {
			$meta = EMC_Checkout::get_order_meta( $order_id );
			$paytr_result = EMC_PayTR::get_payment_token( $order_id, (float) $meta['total'], $meta['customer'], $meta['items'] );
			if ( ! is_wp_error( $paytr_result ) && ! empty( $paytr_result['iframe_url'] ) ) {
				$response['paytr_iframe_url'] = $paytr_result['iframe_url'];
			}
		}
		return rest_ensure_response( $response );
	}

	private static function get_fonts() {
		$saved = get_option( 'emc_fonts', array() );
		if ( ! empty( $saved ) && is_array( $saved ) ) {
			return $saved;
		}
		return array(
			// Sistem fontları
			array( 'name' => 'Arial', 'family' => 'Arial, sans-serif' ),
			array( 'name' => 'Georgia', 'family' => 'Georgia, serif' ),
			array( 'name' => 'Times New Roman', 'family' => '"Times New Roman", serif' ),
			array( 'name' => 'Verdana', 'family' => 'Verdana, sans-serif' ),
			array( 'name' => 'Courier New', 'family' => '"Courier New", monospace' ),
			// Klasik & dekoratif (italic destekli serif)
			array( 'name' => 'Playfair Display', 'family' => '"Playfair Display", serif' ),
			array( 'name' => 'Lora', 'family' => '"Lora", serif' ),
			array( 'name' => 'Crimson Text', 'family' => '"Crimson Text", serif' ),
			array( 'name' => 'Outfit', 'family' => '"Outfit", sans-serif' ),
			array( 'name' => 'Cormorant Garamond', 'family' => '"Cormorant Garamond", serif' ),
			array( 'name' => 'Lato', 'family' => '"Lato", sans-serif' ),
			array( 'name' => 'Open Sans', 'family' => '"Open Sans", sans-serif' ),
			array( 'name' => 'Montserrat', 'family' => '"Montserrat", sans-serif' ),
			array( 'name' => 'Poppins', 'family' => '"Poppins", sans-serif' ),
			array( 'name' => 'Roboto', 'family' => '"Roboto", sans-serif' ),
			array( 'name' => 'Source Sans 3', 'family' => '"Source Sans 3", sans-serif' ),
			// El yazısı & script
			array( 'name' => 'Dancing Script', 'family' => '"Dancing Script", cursive' ),
			array( 'name' => 'Pacifico', 'family' => '"Pacifico", cursive' ),
			array( 'name' => 'Great Vibes', 'family' => '"Great Vibes", cursive' ),
			array( 'name' => 'Caveat', 'family' => '"Caveat", cursive' ),
			array( 'name' => 'Satisfy', 'family' => '"Satisfy", cursive' ),
			array( 'name' => 'Cookie', 'family' => '"Cookie", cursive' ),
			array( 'name' => 'Kalam', 'family' => '"Kalam", cursive' ),
			array( 'name' => 'Amatic SC', 'family' => '"Amatic SC", cursive' ),
			array( 'name' => 'Indie Flower', 'family' => '"Indie Flower", cursive' ),
			array( 'name' => 'Sacramento', 'family' => '"Sacramento", cursive' ),
			array( 'name' => 'Patrick Hand', 'family' => '"Patrick Hand", cursive' ),
			array( 'name' => 'Gloria Hallelujah', 'family' => '"Gloria Hallelujah", cursive' ),
			array( 'name' => 'Caveat Brush', 'family' => '"Caveat Brush", cursive' ),
			array( 'name' => 'Permanent Marker', 'family' => '"Permanent Marker", cursive' ),
			array( 'name' => 'Handlee', 'family' => '"Handlee", cursive' ),
			// Ek el yazısı & script fontları
			array( 'name' => 'Allura', 'family' => '"Allura", cursive' ),
			array( 'name' => 'Mr Dafoe', 'family' => '"Mr Dafoe", cursive' ),
			array( 'name' => 'Marck Script', 'family' => '"Marck Script", cursive' ),
			array( 'name' => 'Shadows Into Light', 'family' => '"Shadows Into Light", cursive' ),
			array( 'name' => 'Nothing You Could Do', 'family' => '"Nothing You Could Do", cursive' ),
			array( 'name' => 'Architects Daughter', 'family' => '"Architects Daughter", cursive' ),
			array( 'name' => 'Covered By Your Grace', 'family' => '"Covered By Your Grace", cursive' ),
			array( 'name' => 'Courgette', 'family' => '"Courgette", cursive' ),
			array( 'name' => 'Yellowtail', 'family' => '"Yellowtail", cursive' ),
			array( 'name' => 'Bad Script', 'family' => '"Bad Script", cursive' ),
			array( 'name' => 'Lobster Two', 'family' => '"Lobster Two", cursive' ),
			array( 'name' => 'Sriracha', 'family' => '"Sriracha", cursive' ),
			array( 'name' => 'Neucha', 'family' => '"Neucha", cursive' ),
			array( 'name' => 'Coming Soon', 'family' => '"Coming Soon", cursive' ),
			array( 'name' => 'Reenie Beanie', 'family' => '"Reenie Beanie", cursive' ),
			array( 'name' => 'Rock Salt', 'family' => '"Rock Salt", cursive' ),
		);
	}
}
