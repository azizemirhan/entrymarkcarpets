<?php
/**
 * PayTR: iframe token alma, bildirim (callback) işleme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EMC_PayTR {

	const GET_TOKEN_URL = 'https://www.paytr.com/odeme/api/get-token';
	const IFRAME_BASE   = 'https://www.paytr.com/odeme/guvenli/';

	/**
	 * Ödeme token'ı al; iframe URL döndür.
	 *
	 * @param int   $order_id   emc_order post ID (merchant_oid olarak kullanılır).
	 * @param float $total_tl   Toplam tutar (TL).
	 * @param array $customer   ad, soyad, email, telefon, adres.
	 * @param array $items       Sepet öğeleri (user_basket için).
	 * @return array|WP_Error  ['iframe_url' => '...'] veya WP_Error
	 */
	public static function get_payment_token( $order_id, $total_tl, $customer, $items = array() ) {
		$merchant_id  = get_option( 'emc_paytr_merchant_id', '' );
		$merchant_key  = get_option( 'emc_paytr_merchant_key', '' );
		$merchant_salt = get_option( 'emc_paytr_merchant_salt', '' );
		if ( ! $merchant_id || ! $merchant_key || ! $merchant_salt ) {
			return new \WP_Error( 'paytr_config', __( 'PayTR ayarları eksik.', 'entrymark-paspas' ) );
		}
		$test_mode = (int) get_option( 'emc_paytr_test_mode', 1 );
		$payment_amount = (int) round( $total_tl * 100 );
		if ( $payment_amount <= 0 ) {
			return new \WP_Error( 'paytr_amount', __( 'Geçersiz tutar.', 'entrymark-paspas' ) );
		}
		$merchant_oid = (string) $order_id;
		$email        = isset( $customer['email'] ) ? $customer['email'] : '';
		$user_name    = trim( ( isset( $customer['ad'] ) ? $customer['ad'] : '' ) . ' ' . ( isset( $customer['soyad'] ) ? $customer['soyad'] : '' ) );
		$user_address = isset( $customer['adres'] ) ? substr( $customer['adres'], 0, 400 ) : '';
		$user_phone   = isset( $customer['telefon'] ) ? substr( $customer['telefon'], 0, 20 ) : '';

		$user_basket = self::build_user_basket( $items, $total_tl );
		$user_ip     = self::get_client_ip();
		$no_installment = 0;
		$max_installment = 0;
		$currency = 'TL';

		$hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
		$paytr_token = base64_encode( hash_hmac( 'sha256', $hash_str . $merchant_salt, $merchant_key, true ) );

		$callback_url = add_query_arg( array( 'emc_paytr_callback' => '1' ), home_url( '/' ) );
		$success_url  = add_query_arg( array( 'emc_order_success' => '1', 'emc_order_id' => $order_id ), get_permalink( (int) get_option( 'emc_checkout_page_id', 0 ) ) ?: home_url( '/' ) );
		$fail_url     = add_query_arg( array( 'emc_order_fail' => '1', 'emc_order_id' => $order_id ), get_permalink( (int) get_option( 'emc_checkout_page_id', 0 ) ) ?: home_url( '/' ) );

		$post_vals = array(
			'merchant_id'       => $merchant_id,
			'user_ip'           => $user_ip,
			'merchant_oid'      => $merchant_oid,
			'email'             => $email,
			'payment_amount'    => $payment_amount,
			'paytr_token'       => $paytr_token,
			'user_basket'       => $user_basket,
			'debug_on'          => 0,
			'no_installment'    => $no_installment,
			'max_installment'   => $max_installment,
			'user_name'         => $user_name,
			'user_address'      => $user_address,
			'user_phone'        => $user_phone,
			'merchant_ok_url'   => $success_url,
			'merchant_fail_url' => $fail_url,
			'timeout_limit'     => '30',
			'currency'          => $currency,
			'test_mode'         => $test_mode,
		);

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, self::GET_TOKEN_URL );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_vals );
		curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
		$result = @curl_exec( $ch );
		if ( curl_errno( $ch ) ) {
			curl_close( $ch );
			return new \WP_Error( 'paytr_connection', 'PayTR bağlantı hatası: ' . curl_error( $ch ) );
		}
		curl_close( $ch );

		$result = json_decode( $result, true );
		if ( empty( $result['status'] ) || $result['status'] !== 'success' || empty( $result['token'] ) ) {
			$reason = isset( $result['reason'] ) ? $result['reason'] : __( 'Bilinmeyen hata', 'entrymark-paspas' );
			return new \WP_Error( 'paytr_token', $reason );
		}
		return array( 'iframe_url' => self::IFRAME_BASE . $result['token'] );
	}

	private static function build_user_basket( $items, $total_tl ) {
		$rows = array();
		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				$summary = isset( $item['summary'] ) ? $item['summary'] : array();
				$name   = isset( $summary['texture_name'] ) ? $summary['texture_name'] : 'Paspas';
				if ( ! empty( $summary['size_label'] ) ) {
					$name .= ' ' . $summary['size_label'];
				}
				$pr = isset( $item['pricing'] ) && is_array( $item['pricing'] ) ? $item['pricing'] : array();
				$price = isset( $pr['total'] ) ? number_format( (float) $pr['total'], 2, '.', '' ) : number_format( $total_tl, 2, '.', '' );
				$rows[] = array( $name, $price, 1 );
			}
		}
		if ( empty( $rows ) ) {
			$rows[] = array( 'Özelleştirilmiş Paspas', number_format( $total_tl, 2, '.', '' ), 1 );
		}
		return base64_encode( wp_json_encode( $rows ) );
	}

	private static function get_client_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			return sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
		}
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ips = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
			return sanitize_text_field( trim( $ips[0] ) );
		}
		return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '127.0.0.1';
	}

	/**
	 * PayTR bildirim URL'sinden gelen POST'u işle; hash doğrula, sipariş durumunu güncelle.
	 * Yanıt: yalnızca "OK" (öncesi/sonrası çıktı olmamalı).
	 */
	public static function handle_callback() {
		if ( ! isset( $_GET['emc_paytr_callback'] ) || $_GET['emc_paytr_callback'] !== '1' ) {
			return;
		}
		if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || empty( $_POST['merchant_oid'] ) || empty( $_POST['hash'] ) ) {
			status_header( 400 );
			exit;
		}
		$merchant_key  = get_option( 'emc_paytr_merchant_key', '' );
		$merchant_salt = get_option( 'emc_paytr_merchant_salt', '' );
		if ( ! $merchant_key || ! $merchant_salt ) {
			status_header( 500 );
			exit;
		}
		$post = array(
			'merchant_oid' => isset( $_POST['merchant_oid'] ) ? sanitize_text_field( $_POST['merchant_oid'] ) : '',
			'status'       => isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '',
			'total_amount' => isset( $_POST['total_amount'] ) ? sanitize_text_field( $_POST['total_amount'] ) : '',
			'hash'         => isset( $_POST['hash'] ) ? sanitize_text_field( $_POST['hash'] ) : '',
		);
		$hash_calc = base64_encode( hash_hmac( 'sha256', $post['merchant_oid'] . $merchant_salt . $post['status'] . $post['total_amount'], $merchant_key, true ) );
		if ( ! hash_equals( $hash_calc, $post['hash'] ) ) {
			status_header( 400 );
			exit;
		}
		$order_id = (int) $post['merchant_oid'];
		$order = get_post( $order_id );
		if ( ! $order || $order->post_type !== 'emc_order' ) {
			header( 'Content-Type: text/plain; charset=utf-8' );
			echo 'OK';
			exit;
		}
		$current_status = get_post_meta( $order_id, '_emc_status', true );
		if ( $current_status === EMC_Checkout::ORDER_STATUS_PAID || $current_status === EMC_Checkout::ORDER_STATUS_FAILED ) {
			header( 'Content-Type: text/plain; charset=utf-8' );
			echo 'OK';
			exit;
		}
		if ( $post['status'] === 'success' ) {
			update_post_meta( $order_id, '_emc_status', EMC_Checkout::ORDER_STATUS_PAID );
			update_post_meta( $order_id, '_emc_paytr_oid', $post['merchant_oid'] );
			self::send_order_email( $order_id );
		} else {
			update_post_meta( $order_id, '_emc_status', EMC_Checkout::ORDER_STATUS_FAILED );
		}
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo 'OK';
		exit;
	}

	/**
	 * Sipariş ödendikten sonra müşteriye e-posta gönder.
	 */
	private static function send_order_email( $order_id ) {
		$meta = EMC_Checkout::get_order_meta( $order_id );
		$email = isset( $meta['customer']['email'] ) ? $meta['customer']['email'] : '';
		if ( ! $email || ! is_email( $email ) ) {
			return;
		}
		$name = trim( ( $meta['customer']['ad'] ?? '' ) . ' ' . ( $meta['customer']['soyad'] ?? '' ) );
		$total = number_format( $meta['total'], 2, ',', '.' );
		$lines = array();
		foreach ( $meta['items'] as $item ) {
			$sum = isset( $item['summary'] ) ? $item['summary'] : array();
			$pr  = isset( $item['pricing'] ) ? $item['pricing'] : array();
			$lines[] = ( isset( $sum['texture_name'] ) ? $sum['texture_name'] : 'Paspas' ) . ' — ' . ( isset( $sum['size_label'] ) ? $sum['size_label'] : '' ) . ' — ' . number_format( isset( $pr['total'] ) ? $pr['total'] : 0, 2, ',', '.' ) . ' TL';
		}
		$subject = sprintf( __( 'Siparişiniz alındı #%d', 'entrymark-paspas' ), $order_id );
		$body = sprintf( __( 'Merhaba %s,', 'entrymark-paspas' ), $name ) . "\n\n"
			. sprintf( __( 'Sipariş numaranız: #%d', 'entrymark-paspas' ), $order_id ) . "\n\n"
			. __( 'Sipariş özeti:', 'entrymark-paspas' ) . "\n"
			. implode( "\n", $lines ) . "\n\n"
			. __( 'Toplam:', 'entrymark-paspas' ) . ' ' . $total . ' TL' . "\n\n"
			. __( 'Teşekkür ederiz.', 'entrymark-paspas' );
		wp_mail( $email, $subject, $body, array( 'Content-Type: text/plain; charset=UTF-8' ) );
	}
}
