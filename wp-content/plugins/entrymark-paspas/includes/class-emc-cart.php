<?php
/**
 * Sepet: oturum/transient tabanlı; design, summary, pricing, preview saklar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EMC_Cart {

	const COOKIE_NAME = 'emc_cart_id';
	const TRANSIENT_PREFIX = 'emc_cart_';
	const TRANSIENT_TTL = 2 * DAY_IN_SECONDS;

	/**
	 * Sepet kimliği: giriş yapmışsa user_id, değilse cookie'den veya yeni oluşturulur.
	 */
	public static function get_cart_id() {
		if ( get_current_user_id() ) {
			return 'user_' . get_current_user_id();
		}
		if ( isset( $_COOKIE[ self::COOKIE_NAME ] ) && is_string( $_COOKIE[ self::COOKIE_NAME ] ) && preg_match( '/^[a-zA-Z0-9_-]{20,64}$/', $_COOKIE[ self::COOKIE_NAME ] ) ) {
			return sanitize_text_field( $_COOKIE[ self::COOKIE_NAME ] );
		}
		return '';
	}

	/**
	 * Yeni cart_id oluştur (cookie'ye yazılacak).
	 */
	public static function create_cart_id() {
		return 'guest_' . bin2hex( random_bytes( 16 ) );
	}

	private static function transient_key( $cart_id ) {
		return self::TRANSIENT_PREFIX . $cart_id;
	}

	/**
	 * Sepet öğelerini getir.
	 *
	 * @return array Array of items: [ ['design' => ..., 'summary' => ..., 'pricing' => ..., 'preview_data_url' => ...], ... ]
	 */
	public static function get_items( $cart_id = null ) {
		if ( $cart_id === null ) {
			$cart_id = self::get_cart_id();
		}
		if ( ! $cart_id ) {
			return array();
		}
		$data = get_transient( self::transient_key( $cart_id ) );
		if ( ! is_array( $data ) || ! isset( $data['items'] ) ) {
			return array();
		}
		return $data['items'];
	}

	/**
	 * Sepete öğe ekle.
	 *
	 * @param array  $design  orient, recess, texture_id, size (w,h), text, textBold, textItalic, textColor, textFont, logo (base64 veya attachment_id).
	 * @param array  $summary  texture_name, size_label, shipping_label.
	 * @param array  $pricing  base, shipCost, tax, total (sayısal TL).
	 * @param string $preview_data_url  Opsiyonel canvas data URL.
	 * @param string $cart_id  Boşsa mevcut cart_id kullanılır.
	 * @return array [ 'success' => bool, 'cart_id' => string, 'count' => int, 'message' => string ]
	 */
	public static function add_item( $design, $summary, $pricing, $preview_data_url = '', $cart_id = null ) {
		if ( $cart_id === null ) {
			$cart_id = self::get_cart_id();
		}
		if ( ! $cart_id ) {
			$cart_id = self::create_cart_id();
		}

		$key = self::transient_key( $cart_id );
		$data = get_transient( $key );
		if ( ! is_array( $data ) ) {
			$data = array( 'items' => array() );
		}
		if ( ! isset( $data['items'] ) || ! is_array( $data['items'] ) ) {
			$data['items'] = array();
		}

		$item = array(
			'cart_id' => uniqid( 'item_' ),
			'design'  => $design,
			'summary' => $summary,
			'pricing' => $pricing,
			'quantity' => 1,
		);
		if ( $preview_data_url && is_string( $preview_data_url ) && strlen( $preview_data_url ) < 500000 ) {
			$item['preview_data_url'] = $preview_data_url;
		} else {
			$item['preview_data_url'] = '';
		}

		$data['items'][] = $item;
		set_transient( $key, $data, self::TRANSIENT_TTL );

		return array(
			'success'  => true,
			'cart_id'  => $cart_id,
			'count'    => count( $data['items'] ),
			'message'  => __( 'Sepete eklendi.', 'entrymark-paspas' ),
		);
	}

	/**
	 * Öğe çıkar (index 0 tabanlı).
	 */
	public static function remove_item( $index, $cart_id = null ) {
		if ( $cart_id === null ) {
			$cart_id = self::get_cart_id();
		}
		if ( ! $cart_id ) {
			return array( 'success' => false, 'count' => 0 );
		}
		$key  = self::transient_key( $cart_id );
		$data = get_transient( $key );
		if ( ! is_array( $data ) || ! isset( $data['items'] ) ) {
			return array( 'success' => true, 'count' => 0 );
		}
		$idx = (int) $index;
		if ( isset( $data['items'][ $idx ] ) ) {
			array_splice( $data['items'], $idx, 1 );
			set_transient( $key, $data, self::TRANSIENT_TTL );
		}
		return array( 'success' => true, 'count' => count( $data['items'] ) );
	}

	/**
	 * Öğe çıkar (cart_id ile).
	 */
	public static function remove_by_id( $cart_item_id, $cart_id = null ) {
		if ( $cart_id === null ) {
			$cart_id = self::get_cart_id();
		}
		if ( ! $cart_id ) {
			return array( 'success' => false, 'message' => 'Cart ID bulunamadı' );
		}
		$key  = self::transient_key( $cart_id );
		$data = get_transient( $key );
		if ( ! is_array( $data ) || ! isset( $data['items'] ) ) {
			return array( 'success' => false, 'message' => 'Sepet boş' );
		}
		
		// Find and remove item by cart_item_id
		$found = false;
		foreach ( $data['items'] as $idx => $item ) {
			if ( isset( $item['cart_id'] ) && $item['cart_id'] === $cart_item_id ) {
				array_splice( $data['items'], $idx, 1 );
				$found = true;
				break;
			}
		}
		
		if ( $found ) {
			set_transient( $key, $data, self::TRANSIENT_TTL );
			return array( 'success' => true, 'count' => count( $data['items'] ) );
		}
		
		return array( 'success' => false, 'message' => 'Ürün bulunamadı' );
	}

	/**
	 * Adet güncelle.
	 */
	public static function update_quantity( $cart_item_id, $action, $cart_id = null ) {
		if ( $cart_id === null ) {
			$cart_id = self::get_cart_id();
		}
		if ( ! $cart_id ) {
			return array( 'success' => false, 'message' => 'Cart ID bulunamadı' );
		}
		$key  = self::transient_key( $cart_id );
		$data = get_transient( $key );
		if ( ! is_array( $data ) || ! isset( $data['items'] ) ) {
			return array( 'success' => false, 'message' => 'Sepet boş' );
		}
		
		// Find item by cart_id
		$found = false;
		foreach ( $data['items'] as $idx => $item ) {
			if ( isset( $item['cart_id'] ) && $item['cart_id'] === $cart_item_id ) {
				$current_qty = isset( $item['quantity'] ) ? intval( $item['quantity'] ) : 1;
				
				if ( $action === 'inc' ) {
					$data['items'][ $idx ]['quantity'] = $current_qty + 1;
				} elseif ( $action === 'dec' && $current_qty > 1 ) {
					$data['items'][ $idx ]['quantity'] = $current_qty - 1;
				}
				
				$found = true;
				break;
			}
		}
		
		if ( $found ) {
			set_transient( $key, $data, self::TRANSIENT_TTL );
			return array( 'success' => true, 'count' => count( $data['items'] ) );
		}
		
		return array( 'success' => false, 'message' => 'Ürün bulunamadı' );
	}

	/**
	 * Sepeti boşalt.
	 */
	public static function empty_cart( $cart_id = null ) {
		if ( $cart_id === null ) {
			$cart_id = self::get_cart_id();
		}
		if ( ! $cart_id ) {
			return;
		}
		delete_transient( self::transient_key( $cart_id ) );
	}

	/**
	 * Sepet verilerini getir (sayfa için).
	 */
	public static function get_cart( $cart_id = null ) {
		if ( $cart_id === null ) {
			$cart_id = self::get_cart_id();
		}
		if ( ! $cart_id ) {
			return array(
				'items'    => array(),
				'subtotal' => 0,
				'shipping' => 0,
				'tax'      => 0,
				'total'    => 0,
			);
		}
		
		$key  = self::transient_key( $cart_id );
		$data = get_transient( $key );
		if ( ! is_array( $data ) || ! isset( $data['items'] ) ) {
			return array(
				'items'    => array(),
				'subtotal' => 0,
				'shipping' => 0,
				'tax'      => 0,
				'total'    => 0,
			);
		}
		
		$items    = $data['items'];
		$subtotal = 0;
		$shipping = 0;
		$tax      = 0;
		
		// Calculate totals
		foreach ( $items as $item ) {
			$pricing = $item['pricing'] ?? array();
			$qty     = isset( $item['quantity'] ) ? intval( $item['quantity'] ) : 1;
			
			$item_total   = isset( $pricing['total'] ) ? floatval( $pricing['total'] ) : 0;
			$item_ship    = isset( $pricing['shipCost'] ) ? floatval( $pricing['shipCost'] ) : 0;
			$item_tax     = isset( $pricing['tax'] ) ? floatval( $pricing['tax'] ) : 0;
			
			$subtotal += ( $item_total - $item_ship - $item_tax ) * $qty;
			$shipping += $item_ship * $qty;
			$tax      += $item_tax * $qty;
		}
		
		return array(
			'items'    => $items,
			'subtotal' => $subtotal,
			'shipping' => $shipping,
			'tax'      => $tax,
			'total'    => $subtotal + $shipping + $tax,
		);
	}

	/**
	 * Sepet sayısı (öğe adedi).
	 */
	public static function get_count( $cart_id = null ) {
		return count( self::get_items( $cart_id ) );
	}

	/**
	 * Sepet öğelerini kaydet.
	 */
	public static function save_items( $cart_id, $items ) {
		if ( ! $cart_id ) {
			return false;
		}
		$key = self::transient_key( $cart_id );
		$data = array( 'items' => $items );
		return set_transient( $key, $data, self::TRANSIENT_TTL );
	}

	/**
	 * Sepeti temizle (alias for empty_cart).
	 */
	public static function clear( $cart_id = null ) {
		self::empty_cart( $cart_id );
	}
}
