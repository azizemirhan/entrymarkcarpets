<?php
/**
 * Checkout: sepetten sipariş oluşturma, müşteri bilgileri, sepeti boşaltma.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EMC_Checkout {

	const ORDER_STATUS_PENDING = 'pending_payment';
	const ORDER_STATUS_PAID    = 'paid';
	const ORDER_STATUS_FAILED  = 'failed';
	const ORDER_STATUS_PROCESSING = 'processing';
	const ORDER_STATUS_COMPLETED  = 'completed';

	/**
	 * Sepetten sipariş oluştur.
	 *
	 * @param array $customer  ad, soyad, email, telefon, adres (string)
	 * @return int|WP_Error  order post ID veya hata
	 */
	public static function create_order_from_cart( $customer ) {
		$items = EMC_Cart::get_items();
		if ( empty( $items ) ) {
			return new \WP_Error( 'empty_cart', __( 'Sepetiniz boş.', 'entrymark-paspas' ) );
		}
		$customer = self::sanitize_customer( $customer );
		$err = self::validate_customer( $customer );
		if ( is_wp_error( $err ) ) {
			return $err;
		}
		$total = 0;
		foreach ( $items as $item ) {
			$p = isset( $item['pricing'] ) && is_array( $item['pricing'] ) ? $item['pricing'] : array();
			$total += isset( $p['total'] ) ? (float) $p['total'] : 0;
		}
		$order_title = sprintf(
			/* translators: 1: date, 2: customer name */
			__( 'Sipariş %1$s — %2$s', 'entrymark-paspas' ),
			wp_date( 'Y-m-d H:i' ),
			$customer['ad'] . ' ' . $customer['soyad']
		);
		$order_id = wp_insert_post( array(
			'post_type'   => 'emc_order',
			'post_title'  => $order_title,
			'post_status' => 'publish',
			'post_author' => 0,
		), true );
		if ( is_wp_error( $order_id ) ) {
			return $order_id;
		}
		update_post_meta( $order_id, '_emc_items', $items );
		update_post_meta( $order_id, '_emc_customer', $customer );
		update_post_meta( $order_id, '_emc_status', self::ORDER_STATUS_PENDING );
		update_post_meta( $order_id, '_emc_total', $total );
		update_post_meta( $order_id, '_emc_paytr_oid', '' );
		EMC_Cart::empty_cart();
		return $order_id;
	}

	public static function sanitize_customer( $customer ) {
		if ( ! is_array( $customer ) ) {
			$customer = array();
		}
		return array(
			'ad'     => isset( $customer['ad'] ) ? sanitize_text_field( $customer['ad'] ) : '',
			'soyad'  => isset( $customer['soyad'] ) ? sanitize_text_field( $customer['soyad'] ) : '',
			'email'  => isset( $customer['email'] ) ? sanitize_email( $customer['email'] ) : '',
			'telefon' => isset( $customer['telefon'] ) ? sanitize_text_field( $customer['telefon'] ) : '',
			'adres'  => isset( $customer['adres'] ) ? sanitize_textarea_field( $customer['adres'] ) : '',
		);
	}

	public static function validate_customer( $customer ) {
		if ( empty( $customer['ad'] ) ) {
			return new \WP_Error( 'invalid_customer', __( 'Ad gerekli.', 'entrymark-paspas' ) );
		}
		if ( empty( $customer['soyad'] ) ) {
			return new \WP_Error( 'invalid_customer', __( 'Soyad gerekli.', 'entrymark-paspas' ) );
		}
		if ( empty( $customer['email'] ) || ! is_email( $customer['email'] ) ) {
			return new \WP_Error( 'invalid_customer', __( 'Geçerli e-posta gerekli.', 'entrymark-paspas' ) );
		}
		if ( empty( $customer['telefon'] ) ) {
			return new \WP_Error( 'invalid_customer', __( 'Telefon gerekli.', 'entrymark-paspas' ) );
		}
		if ( empty( $customer['adres'] ) ) {
			return new \WP_Error( 'invalid_customer', __( 'Adres gerekli.', 'entrymark-paspas' ) );
		}
		return true;
	}

	/**
	 * Sipariş meta'larını getir.
	 */
	public static function get_order_meta( $order_id ) {
		$items    = get_post_meta( $order_id, '_emc_items', true );
		$customer = get_post_meta( $order_id, '_emc_customer', true );
		$status   = get_post_meta( $order_id, '_emc_status', true );
		$total    = get_post_meta( $order_id, '_emc_total', true );
		$paytr_oid = get_post_meta( $order_id, '_emc_paytr_oid', true );
		return array(
			'items'     => is_array( $items ) ? $items : array(),
			'customer'  => is_array( $customer ) ? $customer : array(),
			'status'    => $status ?: self::ORDER_STATUS_PENDING,
			'total'     => (float) $total,
			'paytr_oid' => $paytr_oid ?: '',
		);
	}
}
