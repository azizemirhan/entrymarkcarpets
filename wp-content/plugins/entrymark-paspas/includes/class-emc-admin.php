<?php
/**
 * Admin: Entry Mark Paspas — Dokular, Ölçüler, Fiyatlandırma, Gönderim, Yazı/Logo, PayTR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EMC_Admin {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ), 10, 1 );
	}

	public static function add_menu() {
		add_menu_page(
			__( 'Entry Mark Paspas', 'entrymark-paspas' ),
			__( 'Paspas', 'entrymark-paspas' ),
			'manage_options',
			'entrymark-paspas',
			array( __CLASS__, 'page_dashboard' ),
			'dashicons-grid-view',
			58
		);
		add_submenu_page( 'entrymark-paspas', __( 'Dokular (Yüzey Görselleri)', 'entrymark-paspas' ), __( 'Dokular', 'entrymark-paspas' ), 'manage_options', 'emc-textures', array( __CLASS__, 'page_textures' ) );
		add_submenu_page( 'entrymark-paspas', __( 'Ölçüler', 'entrymark-paspas' ), __( 'Ölçüler', 'entrymark-paspas' ), 'manage_options', 'emc-sizes', array( __CLASS__, 'page_sizes' ) );
		add_submenu_page( 'entrymark-paspas', __( 'Fiyatlandırma & Gönderim', 'entrymark-paspas' ), __( 'Fiyat & Gönderim', 'entrymark-paspas' ), 'manage_options', 'emc-pricing', array( __CLASS__, 'page_pricing' ) );
		add_submenu_page( 'entrymark-paspas', __( 'Sepet Sayfası', 'entrymark-paspas' ), __( 'Sepet Sayfası', 'entrymark-paspas' ), 'manage_options', 'emc-cart-page', array( __CLASS__, 'page_cart_settings' ) );
		add_submenu_page( 'entrymark-paspas', __( 'Yazı & Logo', 'entrymark-paspas' ), __( 'Yazı & Logo', 'entrymark-paspas' ), 'manage_options', 'emc-text-logo', array( __CLASS__, 'page_text_logo' ) );
		add_submenu_page( 'entrymark-paspas', __( 'PayTR Ödeme', 'entrymark-paspas' ), __( 'PayTR', 'entrymark-paspas' ), 'manage_options', 'emc-paytr', array( __CLASS__, 'page_paytr' ) );
		add_submenu_page( 'entrymark-paspas', __( 'Siparişler', 'entrymark-paspas' ), __( 'Siparişler', 'entrymark-paspas' ), 'manage_options', 'emc-orders', array( __CLASS__, 'page_orders' ) );
	}

	public static function register_settings() {
		// Fiyatlandırma
		register_setting( 'emc_pricing', 'emc_price_per_m2', array( 'type' => 'number', 'default' => 2050, 'sanitize_callback' => 'floatval' ) );
		register_setting( 'emc_pricing', 'emc_min_total', array( 'type' => 'number', 'default' => 200, 'sanitize_callback' => 'floatval' ) );
		register_setting( 'emc_pricing', 'emc_tax_rate', array( 'type' => 'number', 'default' => 10, 'sanitize_callback' => 'floatval' ) );
		register_setting( 'emc_pricing', 'emc_price_per_image', array( 'type' => 'number', 'default' => 0, 'sanitize_callback' => 'floatval' ) );
		register_setting( 'emc_pricing', 'emc_price_text_extra', array( 'type' => 'number', 'default' => 0, 'sanitize_callback' => 'floatval' ) );
		register_setting( 'emc_pricing', 'emc_shipping_options', array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize_shipping' ) ) );

		// Ölçü limitleri (özel ölçü)
		register_setting( 'emc_sizes', 'emc_custom_size_enabled', array( 'type' => 'integer', 'default' => 1 ) );
		register_setting( 'emc_sizes', 'emc_custom_size_min_w', array( 'type' => 'integer', 'default' => 10 ) );
		register_setting( 'emc_sizes', 'emc_custom_size_min_h', array( 'type' => 'integer', 'default' => 10 ) );
		register_setting( 'emc_sizes', 'emc_custom_size_max_w', array( 'type' => 'integer', 'default' => 500 ) );
		register_setting( 'emc_sizes', 'emc_custom_size_max_h', array( 'type' => 'integer', 'default' => 500 ) );
		register_setting( 'emc_sizes', 'emc_sizes_horizontal', array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize_sizes' ) ) );
		register_setting( 'emc_sizes', 'emc_sizes_vertical', array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize_sizes' ) ) );
		register_setting( 'emc_sizes', 'emc_sizes_round', array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize_sizes' ) ) );

		// Yazı & Logo
		register_setting( 'emc_text_logo', 'emc_text_max_length', array( 'type' => 'integer', 'default' => 100 ) );
		register_setting( 'emc_text_logo', 'emc_logo_max_mb', array( 'type' => 'integer', 'default' => 5 ) );
		register_setting( 'emc_text_logo', 'emc_fonts', array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize_fonts' ) ) );

		// PayTR
		register_setting( 'emc_paytr', 'emc_paytr_merchant_id', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'emc_paytr', 'emc_paytr_merchant_key', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'emc_paytr', 'emc_paytr_merchant_salt', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'emc_paytr', 'emc_paytr_test_mode', array( 'type' => 'integer', 'default' => 1 ) );
		register_setting( 'emc_paytr', 'emc_checkout_page_id', array( 'type' => 'integer', 'default' => 0 ) );

		// WhatsApp Sipariş Hattı
		register_setting( 'emc_pricing', 'emc_whatsapp_enabled', array( 'type' => 'integer', 'default' => 0 ) );
		register_setting( 'emc_pricing', 'emc_whatsapp_number', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'emc_pricing', 'emc_whatsapp_button_text', array( 'type' => 'string', 'default' => 'WhatsApp ile Sipariş Ver', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'emc_pricing', 'emc_whatsapp_message', array( 'type' => 'string', 'default' => 'Merhaba, paspas tasarımım hakkında bilgi almak istiyorum.', 'sanitize_callback' => 'sanitize_textarea_field' ) );

		// Sepet Sayfası Ayarları
		register_setting( 'emc_cart', 'emc_cart_title', array( 'type' => 'string', 'default' => 'Sepetim', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'emc_cart', 'emc_cart_subtitle', array( 'type' => 'string', 'default' => 'ürün', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'emc_cart', 'emc_trust_items', array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize_trust_items' ) ) );
		register_setting( 'emc_cart', 'emc_payment_logos', array( 'type' => 'string', 'sanitize_callback' => 'esc_url_raw' ) );
	}

	public static function sanitize_shipping( $input ) {
		if ( ! is_array( $input ) ) return array();
		$out = array();
		foreach ( $input as $row ) {
			if ( empty( $row['id'] ) ) continue;
			$out[] = array(
				'id'    => sanitize_text_field( $row['id'] ),
				'label' => sanitize_text_field( isset( $row['label'] ) ? $row['label'] : '' ),
				'extra' => isset( $row['extra'] ) ? floatval( $row['extra'] ) : 0,
				'days'  => sanitize_text_field( isset( $row['days'] ) ? $row['days'] : '' ),
			);
		}
		return $out;
	}

	public static function sanitize_sizes( $input ) {
		if ( ! is_array( $input ) ) return array();
		$out = array();
		foreach ( $input as $row ) {
			$w = isset( $row[0] ) ? absint( $row[0] ) : ( isset( $row['w'] ) ? absint( $row['w'] ) : 0 );
			$h = isset( $row[1] ) ? absint( $row[1] ) : ( isset( $row['h'] ) ? absint( $row['h'] ) : 0 );
			if ( $w && $h ) $out[] = array( $w, $h );
		}
		return $out;
	}

	public static function sanitize_fonts( $input ) {
		if ( ! is_array( $input ) ) return array();
		$out = array();
		foreach ( $input as $row ) {
			$name = isset( $row['name'] ) ? sanitize_text_field( $row['name'] ) : '';
			$fam  = isset( $row['family'] ) ? sanitize_text_field( $row['family'] ) : '';
			if ( $name || $fam ) $out[] = array( 'name' => $name, 'family' => $fam );
		}
		return $out;
	}

	public static function enqueue( $hook ) {
		if ( strpos( $hook, 'entrymark-paspas' ) === false && strpos( $hook, 'emc-' ) === false ) return;
		wp_enqueue_media();
		wp_enqueue_style( 'emc-admin', EMC_PLUGIN_URL . 'assets/admin.css', array(), EMC_VERSION );
		wp_enqueue_script( 'emc-admin', EMC_PLUGIN_URL . 'assets/admin.js', array( 'jquery' ), EMC_VERSION, true );
	}

	/** Ana sayfa: kısa bilgi + shortcode */
	public static function page_dashboard() {
		$config_url = rest_url( 'entrymark-paspas/v1/config' );
		?>
		<div class="wrap emc-admin">
			<h1><?php esc_html_e( 'Entry Mark Paspas', 'entrymark-paspas' ); ?></h1>
			<p><?php esc_html_e( 'Paspas özelleştirici ve satış eklentisi. Dokular, ölçüler ve fiyatları aşağıdaki sayfalardan yönetin; ödeme PayTR ile alınır.', 'entrymark-paspas' ); ?></p>
			<div class="emc-card" style="max-width:600px;margin-top:1em;">
				<h3><?php esc_html_e( 'Özelleştirici sayfası', 'entrymark-paspas' ); ?></h3>
				<p><?php esc_html_e( 'Herhangi bir sayfaya şu shortcode\'u ekleyin:', 'entrymark-paspas' ); ?></p>
				<code>[entrymark_paspas_customizer]</code>
				<p class="description"><?php esc_html_e( 'Config API:', 'entrymark-paspas' ); ?> <a href="<?php echo esc_url( $config_url ); ?>" target="_blank"><?php echo esc_html( $config_url ); ?></a></p>
			</div>
			<form method="post" action="options.php" class="emc-card" style="max-width:600px;margin-top:1em;">
				<?php settings_fields( 'emc_paytr' ); ?>
				<h3><?php esc_html_e( 'Checkout sayfası', 'entrymark-paspas' ); ?></h3>
				<p><?php esc_html_e( 'Ödeme sayfası olarak kullanılacak sayfayı seçin. Bu sayfada [entrymark_paspas_checkout] shortcode\'u olmalı.', 'entrymark-paspas' ); ?></p>
				<p>
					<label for="emc_checkout_page_id"><?php esc_html_e( 'Sayfa', 'entrymark-paspas' ); ?></label><br>
					<?php
					wp_dropdown_pages( array(
						'selected'          => get_option( 'emc_checkout_page_id', 0 ),
						'name'              => 'emc_checkout_page_id',
						'id'                => 'emc_checkout_page_id',
						'show_option_none' => __( '— Seçin —', 'entrymark-paspas' ),
					) );
					?>
				</p>
				<p><button type="submit" class="button button-primary"><?php esc_html_e( 'Kaydet', 'entrymark-paspas' ); ?></button></p>
			</form>
		</div>
		<?php
	}

	/** Dokular: yüzey görselleri listesi + ekle/düzenle */
	public static function page_textures() {
		$textures = get_option( 'emc_textures', array() );
		if ( ! is_array( $textures ) ) $textures = array();

		if ( isset( $_POST['emc_texture_save'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'emc_textures' ) ) {
			$id    = isset( $_POST['emc_texture_id'] ) ? sanitize_text_field( $_POST['emc_texture_id'] ) : '';
			$name  = isset( $_POST['emc_texture_name'] ) ? sanitize_text_field( $_POST['emc_texture_name'] ) : '';
			$img_id = isset( $_POST['emc_texture_image_id'] ) ? absint( $_POST['emc_texture_image_id'] ) : 0;
			if ( $name && $img_id ) {
				if ( $id ) {
					foreach ( $textures as $i => $t ) {
						if ( isset( $t['id'] ) && $t['id'] === $id ) {
							$textures[ $i ] = array( 'id' => $id, 'name' => $name, 'image_id' => $img_id );
							break;
						}
					}
				} else {
					$textures[] = array( 'id' => 't-' . ( count( $textures ) + 1 ), 'name' => $name, 'image_id' => $img_id );
				}
				update_option( 'emc_textures', $textures );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'Doku kaydedildi.', 'entrymark-paspas' ) . '</p></div>';
			}
			$textures = get_option( 'emc_textures', array() );
		}
		if ( isset( $_GET['emc_delete_texture'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'emc_delete_texture_' . $_GET['emc_delete_texture'] ) ) {
			$del = sanitize_text_field( $_GET['emc_delete_texture'] );
			$textures = array_values( array_filter( $textures, function( $t ) use ( $del ) { return ( isset( $t['id'] ) && $t['id'] !== $del ); } ) );
			update_option( 'emc_textures', $textures );
			wp_safe_redirect( remove_query_arg( array( 'emc_delete_texture', '_wpnonce' ) ) );
			exit;
		}
		if ( isset( $_GET['emc_texture_move'] ) && isset( $_GET['emc_texture_id'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'emc_texture_move_' . $_GET['emc_texture_id'] ) ) {
			$move = $_GET['emc_texture_move'] === 'up' ? -1 : 1;
			$tid  = sanitize_text_field( $_GET['emc_texture_id'] );
			$idx  = -1;
			foreach ( $textures as $i => $t ) {
				if ( isset( $t['id'] ) && $t['id'] === $tid ) { $idx = $i; break; }
			}
			if ( $idx >= 0 ) {
				$new_idx = $idx + $move;
				if ( $new_idx >= 0 && $new_idx < count( $textures ) ) {
					$tmp = $textures[ $idx ];
					$textures[ $idx ] = $textures[ $new_idx ];
					$textures[ $new_idx ] = $tmp;
					update_option( 'emc_textures', $textures );
				}
			}
			wp_safe_redirect( remove_query_arg( array( 'emc_texture_move', 'emc_texture_id', '_wpnonce' ) ) );
			exit;
		}
		?>
		<div class="wrap emc-admin">
			<h1><?php esc_html_e( 'Dokular (Yüzey Görselleri)', 'entrymark-paspas' ); ?></h1>
			<p><?php esc_html_e( 'Frontend\'de "renk" olarak gösterilecek; kullanıcı bir doku seçtiğinde paspas arka planı bu görsel ile kaplanır. Gerçek renk kodu kullanılmaz.', 'entrymark-paspas' ); ?></p>

			<form method="post" class="emc-card" style="margin:1em 0;">
				<?php wp_nonce_field( 'emc_textures' ); ?>
				<input type="hidden" name="emc_texture_id" id="emc_texture_id" value="">
				<h3><?php esc_html_e( 'Yeni doku ekle / Düzenle', 'entrymark-paspas' ); ?></h3>
				<p>
					<label><?php esc_html_e( 'Ad', 'entrymark-paspas' ); ?></label><br>
					<input type="text" name="emc_texture_name" id="emc_texture_name" placeholder="<?php esc_attr_e( 'Örn. Siyah P0020', 'entrymark-paspas' ); ?>" style="width:280px;">
				</p>
				<p>
					<label><?php esc_html_e( 'Yüzey dokusu görseli', 'entrymark-paspas' ); ?></label><br>
					<button type="button" class="button emc-upload-texture"><?php esc_html_e( 'Görsel seç', 'entrymark-paspas' ); ?></button>
					<input type="hidden" name="emc_texture_image_id" id="emc_texture_image_id" value="">
					<span id="emc_texture_preview"></span>
				</p>
				<p>
					<button type="submit" name="emc_texture_save" class="button button-primary"><?php esc_html_e( 'Kaydet', 'entrymark-paspas' ); ?></button>
				</p>
			</form>

			<table class="wp-list-table widefat fixed striped">
				<thead><tr><th style="width:80px;"><?php esc_html_e( 'Sıra', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'Önizleme', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'Ad', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'İşlem', 'entrymark-paspas' ); ?></th></tr></thead>
				<tbody>
					<?php foreach ( $textures as $i => $t ) :
						$img_id = isset( $t['image_id'] ) ? (int) $t['image_id'] : 0;
						$url   = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';
						$tid   = isset( $t['id'] ) ? $t['id'] : '';
						?>
						<tr>
							<td>
								<?php if ( $i > 0 ) : ?>
									<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'emc_texture_move' => 'up', 'emc_texture_id' => $tid ) ), 'emc_texture_move_' . $tid ) ); ?>"><?php esc_html_e( 'Yukarı', 'entrymark-paspas' ); ?></a>
								<?php else : ?>—<?php endif; ?>
								<?php if ( $i < count( $textures ) - 1 ) : ?>
									| <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'emc_texture_move' => 'down', 'emc_texture_id' => $tid ) ), 'emc_texture_move_' . $tid ) ); ?>"><?php esc_html_e( 'Aşağı', 'entrymark-paspas' ); ?></a>
								<?php endif; ?>
							</td>
							<td><?php if ( $url ) { ?><img src="<?php echo esc_url( $url ); ?>" alt="" style="max-width:60px;height:auto;"><?php } else { echo '—'; } ?></td>
							<td><?php echo esc_html( isset( $t['name'] ) ? $t['name'] : '' ); ?></td>
							<td>
								<a href="#" class="emc-edit-texture" data-id="<?php echo esc_attr( $tid ); ?>" data-name="<?php echo esc_attr( isset( $t['name'] ) ? $t['name'] : '' ); ?>" data-image-id="<?php echo esc_attr( $img_id ); ?>"><?php esc_html_e( 'Düzenle', 'entrymark-paspas' ); ?></a>
								| <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'emc_delete_texture', $tid ), 'emc_delete_texture_' . $tid ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Bu dokuyu silmek istediğinize emin misiniz?', 'entrymark-paspas' ); ?>');"><?php esc_html_e( 'Sil', 'entrymark-paspas' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
					<?php if ( empty( $textures ) ) : ?>
						<tr><td colspan="4"><?php esc_html_e( 'Henüz doku eklenmedi. Yukarıdan ekleyin.', 'entrymark-paspas' ); ?></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/** Ölçüler: yatay/dikey/yuvarlak listeleri + özel ölçü limitleri */
	public static function page_sizes() {
		if ( isset( $_POST['emc_sizes_save'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'emc_sizes' ) ) {
			update_option( 'emc_custom_size_enabled', ! empty( $_POST['emc_custom_size_enabled'] ) ? 1 : 0 );
			update_option( 'emc_custom_size_min_w', absint( $_POST['emc_custom_size_min_w'] ) );
			update_option( 'emc_custom_size_min_h', absint( $_POST['emc_custom_size_min_h'] ) );
			update_option( 'emc_custom_size_max_w', absint( $_POST['emc_custom_size_max_w'] ) );
			update_option( 'emc_custom_size_max_h', absint( $_POST['emc_custom_size_max_h'] ) );
			// Ölçü listeleri: textarea’dan satır satır "en boy" veya "en x boy"
			foreach ( array( 'horizontal', 'vertical', 'round' ) as $key ) {
				$raw = isset( $_POST[ 'emc_sizes_' . $key ] ) ? sanitize_textarea_field( $_POST[ 'emc_sizes_' . $key ] ) : '';
				$arr = array();
				foreach ( preg_split( '/\r?\n/', $raw ) as $line ) {
					$line = trim( $line );
					if ( $line === '' ) continue;
					// "40 x 70" / "40x70" / "40 x 70 +50" / "40x70+50" / "50 x 70 -20" (fiyat farkı TL; boşluksuz da kabul)
					if ( preg_match( '/^(\d+)\s*[x×]\s*(\d+)(?:\s*([+-]?\d+(?:[.,]\d+)?))?\s*$/i', $line, $m ) ) {
						$offset = isset( $m[3] ) && $m[3] !== '' ? (float) str_replace( ',', '.', trim( $m[3] ) ) : 0;
						$arr[] = array( (int) $m[1], (int) $m[2], $offset );
					} elseif ( preg_match( '/^(\d+)\s+(\d+)(?:\s*([+-]?\d+(?:[.,]\d+)?))?\s*$/', $line, $m ) ) {
						$offset = isset( $m[3] ) && $m[3] !== '' ? (float) str_replace( ',', '.', trim( $m[3] ) ) : 0;
						$arr[] = array( (int) $m[1], (int) $m[2], $offset );
					}
				}
				update_option( 'emc_sizes_' . $key, $arr );
			}
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Ölçüler kaydedildi.', 'entrymark-paspas' ) . '</p></div>';
		}
		$hor   = get_option( 'emc_sizes_horizontal', array() );
		$ver   = get_option( 'emc_sizes_vertical', array() );
		$round = get_option( 'emc_sizes_round', array() );
		$format_sizes = function( $arr ) {
			return implode( "\n", array_map( function( $r ) {
				if ( ! isset( $r[0], $r[1] ) ) return '';
				$line = $r[0] . ' x ' . $r[1];
				$off = isset( $r[2] ) ? (float) $r[2] : 0;
				if ( $off != 0 ) $line .= ( $off > 0 ? ' +' : ' ' ) . $off;
				return $line;
			}, $arr ) );
		};
		?>
		<div class="wrap emc-admin">
			<h1><?php esc_html_e( 'Ölçüler', 'entrymark-paspas' ); ?></h1>
			<p class="description" style="margin-bottom:1rem;">
				<?php esc_html_e( 'Ölçü listeleri ve isteğe bağlı fiyat farkları: Her satırda "40 x 70" veya "40 x 70 +50" / "40 x 70 -20" yazabilirsiniz. Ana fiyat Paspas → Fiyat & Gönderim\'deki m² fiyatı ile hesaplanır; + veya - yazdığınız tutar o ölçüye eklenir/çıkarılır.', 'entrymark-paspas' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=emc-pricing' ) ); ?>"><?php esc_html_e( 'Fiyat & Gönderim sayfasına git →', 'entrymark-paspas' ); ?></a>
			</p>
			<form method="post">
				<?php wp_nonce_field( 'emc_sizes' ); ?>
				<div class="emc-card">
					<h3><?php esc_html_e( 'Özel ölçü', 'entrymark-paspas' ); ?></h3>
					<p><label><input type="checkbox" name="emc_custom_size_enabled" value="1" <?php checked( get_option( 'emc_custom_size_enabled', 1 ), 1 ); ?>><?php esc_html_e( 'Özel ölçü girişine izin ver', 'entrymark-paspas' ); ?></label></p>
					<p>
						Min: <input type="number" name="emc_custom_size_min_w" value="<?php echo esc_attr( get_option( 'emc_custom_size_min_w', 10 ) ); ?>" min="1" max="500"> × <input type="number" name="emc_custom_size_min_h" value="<?php echo esc_attr( get_option( 'emc_custom_size_min_h', 10 ) ); ?>" min="1" max="500"> cm
						&nbsp; Max: <input type="number" name="emc_custom_size_max_w" value="<?php echo esc_attr( get_option( 'emc_custom_size_max_w', 500 ) ); ?>" min="1" max="500"> × <input type="number" name="emc_custom_size_max_h" value="<?php echo esc_attr( get_option( 'emc_custom_size_max_h', 500 ) ); ?>" min="1" max="500"> cm
					</p>
				</div>
				<div class="emc-card">
					<h3><?php esc_html_e( 'Yatay ölçüler (en × boy cm)', 'entrymark-paspas' ); ?></h3>
					<textarea name="emc_sizes_horizontal" rows="8" style="width:100%;max-width:400px;"><?php echo esc_textarea( $format_sizes( $hor ) ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Her satır: 40 x 70 veya fiyat farkı ile 40 x 70 +50 / 40 x 70 -20 (TL)', 'entrymark-paspas' ); ?></p>
				</div>
				<div class="emc-card">
					<h3><?php esc_html_e( 'Dikey ölçüler', 'entrymark-paspas' ); ?></h3>
					<textarea name="emc_sizes_vertical" rows="6" style="width:100%;max-width:400px;"><?php echo esc_textarea( $format_sizes( $ver ) ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Aynı format; fiyat farkı isteğe bağlı: +50 veya -20', 'entrymark-paspas' ); ?></p>
				</div>
				<div class="emc-card">
					<h3><?php esc_html_e( 'Yuvarlak ölçüler (çap cm)', 'entrymark-paspas' ); ?></h3>
					<textarea name="emc_sizes_round" rows="4" style="width:100%;max-width:400px;"><?php echo esc_textarea( $format_sizes( $round ) ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Yuvarlak için en = boy = çap (örn. 60 x 60). Fiyat farkı: 60 x 60 +30', 'entrymark-paspas' ); ?></p>
				</div>
				<p><button type="submit" name="emc_sizes_save" class="button button-primary"><?php esc_html_e( 'Kaydet', 'entrymark-paspas' ); ?></button></p>
			</form>
		</div>
		<?php
	}

	/** Fiyatlandırma & Gönderim */
	public static function page_pricing() {
		if ( isset( $_POST['emc_pricing_save'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'emc_pricing' ) ) {
			// Virgüllü ondalık desteği (20,45 → 20.45)
			$float_sanitize = function( $v ) {
				$v = isset( $v ) ? (string) $v : '0';
				return (float) str_replace( array( ',', ' ' ), array( '.', '' ), $v );
			};
			update_option( 'emc_price_per_m2', $float_sanitize( $_POST['emc_price_per_m2'] ?? 0 ) );
			update_option( 'emc_min_total', $float_sanitize( $_POST['emc_min_total'] ?? 200 ) );
			update_option( 'emc_tax_rate', $float_sanitize( $_POST['emc_tax_rate'] ?? 10 ) );
			update_option( 'emc_price_per_image', $float_sanitize( $_POST['emc_price_per_image'] ?? 0 ) );
			update_option( 'emc_price_text_extra', $float_sanitize( $_POST['emc_price_text_extra'] ?? 0 ) );
			$shipping = array();
			if ( ! empty( $_POST['emc_shipping_id'] ) && is_array( $_POST['emc_shipping_id'] ) ) {
				foreach ( $_POST['emc_shipping_id'] as $i => $id ) {
					$shipping[] = array(
						'id'    => sanitize_text_field( $id ),
						'label' => isset( $_POST['emc_shipping_label'][ $i ] ) ? sanitize_text_field( $_POST['emc_shipping_label'][ $i ] ) : '',
						'extra' => $float_sanitize( $_POST['emc_shipping_extra'][ $i ] ?? 0 ),
						'days'  => isset( $_POST['emc_shipping_days'][ $i ] ) ? sanitize_text_field( $_POST['emc_shipping_days'][ $i ] ) : '',
					);
				}
			}
			update_option( 'emc_shipping_options', $shipping );
			update_option( 'emc_whatsapp_enabled', isset( $_POST['emc_whatsapp_enabled'] ) ? 1 : 0 );
			update_option( 'emc_whatsapp_number', sanitize_text_field( $_POST['emc_whatsapp_number'] ?? '' ) );
			update_option( 'emc_whatsapp_button_text', sanitize_text_field( $_POST['emc_whatsapp_button_text'] ?? 'WhatsApp ile Sipariş Ver' ) );
			update_option( 'emc_whatsapp_message', sanitize_textarea_field( $_POST['emc_whatsapp_message'] ?? 'Merhaba, paspas tasarımım hakkında bilgi almak istiyorum.' ) );
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Fiyatlandırma kaydedildi.', 'entrymark-paspas' ) . '</p></div>';
		}
		$shipping = get_option( 'emc_shipping_options', array() );
		if ( ! is_array( $shipping ) ) $shipping = array();
		?>
		<div class="wrap emc-admin">
			<h1><?php esc_html_e( 'Fiyatlandırma & Gönderim', 'entrymark-paspas' ); ?></h1>
			<form method="post">
				<?php wp_nonce_field( 'emc_pricing' ); ?>
				<div class="emc-card">
					<h3><?php esc_html_e( 'Fiyat', 'entrymark-paspas' ); ?></h3>
					<p>
						<label><?php esc_html_e( 'm² başına fiyat (TL)', 'entrymark-paspas' ); ?></label>
						<input type="number" step="0.01" min="0" name="emc_price_per_m2" value="<?php echo esc_attr( function_exists( 'emc_get_float_option' ) ? emc_get_float_option( 'emc_price_per_m2', 20.45 ) : get_option( 'emc_price_per_m2', 20.45 ) ); ?>">
					</p>
					<p>
						<label><?php esc_html_e( 'Minimum sipariş tutarı (TL)', 'entrymark-paspas' ); ?></label>
						<input type="number" step="0.01" name="emc_min_total" value="<?php echo esc_attr( get_option( 'emc_min_total', 200 ) ); ?>">
					</p>
					<p>
						<label><?php esc_html_e( 'KDV oranı (%)', 'entrymark-paspas' ); ?></label>
						<input type="number" step="0.01" name="emc_tax_rate" value="<?php echo esc_attr( get_option( 'emc_tax_rate', 10 ) ); ?>">
					</p>
					<p>
						<label><?php esc_html_e( 'Görsel başına ek ücret (TL)', 'entrymark-paspas' ); ?></label>
						<input type="number" step="0.01" name="emc_price_per_image" value="<?php echo esc_attr( get_option( 'emc_price_per_image', 0 ) ); ?>">
						<span class="description"><?php esc_html_e( 'Eklenen her logo/görsel için toplam fiyata eklenecek tutar.', 'entrymark-paspas' ); ?></span>
					</p>
					<p>
						<label><?php esc_html_e( 'Yazı için ek ücret (TL)', 'entrymark-paspas' ); ?></label>
						<input type="number" step="0.01" name="emc_price_text_extra" value="<?php echo esc_attr( get_option( 'emc_price_text_extra', 0 ) ); ?>">
						<span class="description"><?php esc_html_e( 'Yazı eklendiğinde toplam fiyata eklenecek tutar (tek seferlik).', 'entrymark-paspas' ); ?></span>
					</p>
				</div>
				<div class="emc-card">
					<h3><?php esc_html_e( 'Gönderim seçenekleri', 'entrymark-paspas' ); ?></h3>
					<table class="widefat">
						<thead><tr><th>ID</th><th><?php esc_html_e( 'Etiket', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'Ek ücret (TL)', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'Tahmini süre', 'entrymark-paspas' ); ?></th></tr></thead>
						<tbody>
							<?php foreach ( $shipping as $s ) : ?>
								<tr>
									<td><input type="text" name="emc_shipping_id[]" value="<?php echo esc_attr( isset( $s['id'] ) ? $s['id'] : '' ); ?>" style="width:100px;"></td>
									<td><input type="text" name="emc_shipping_label[]" value="<?php echo esc_attr( isset( $s['label'] ) ? $s['label'] : '' ); ?>"></td>
									<td><input type="number" step="0.01" name="emc_shipping_extra[]" value="<?php echo esc_attr( isset( $s['extra'] ) ? $s['extra'] : 0 ); ?>"></td>
									<td><input type="text" name="emc_shipping_days[]" value="<?php echo esc_attr( isset( $s['days'] ) ? $s['days'] : '' ); ?>" placeholder="5-7"></td>
								</tr>
							<?php endforeach; ?>
							<tr>
								<td><input type="text" name="emc_shipping_id[]" value="" style="width:100px;" placeholder="standard"></td>
								<td><input type="text" name="emc_shipping_label[]" value="" placeholder="Yeni"></td>
								<td><input type="number" step="0.01" name="emc_shipping_extra[]" value="0"></td>
								<td><input type="text" name="emc_shipping_days[]" value="" placeholder="5-7"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="emc-card">
					<h3><?php esc_html_e( 'WhatsApp Sipariş Hattı', 'entrymark-paspas' ); ?></h3>
					<p>
						<label>
							<input type="checkbox" name="emc_whatsapp_enabled" value="1" <?php checked( get_option( 'emc_whatsapp_enabled', 0 ), 1 ); ?>>
							<?php esc_html_e( 'WhatsApp sipariş butonunu aktif et', 'entrymark-paspas' ); ?>
						</label>
					</p>
					<p>
						<label><?php esc_html_e( 'WhatsApp Numarası (90 ile başlamadan, örn: 5xx1234567)', 'entrymark-paspas' ); ?></label>
						<input type="text" name="emc_whatsapp_number" value="<?php echo esc_attr( get_option( 'emc_whatsapp_number', '' ) ); ?>" placeholder="5xx1234567">
					</p>
					<p>
						<label><?php esc_html_e( 'Buton Metni', 'entrymark-paspas' ); ?></label>
						<input type="text" name="emc_whatsapp_button_text" value="<?php echo esc_attr( get_option( 'emc_whatsapp_button_text', 'WhatsApp ile Sipariş Ver' ) ); ?>">
					</p>
					<p>
						<label><?php esc_html_e( 'Varsayılan Mesaj', 'entrymark-paspas' ); ?></label>
						<textarea name="emc_whatsapp_message" rows="3"><?php echo esc_textarea( get_option( 'emc_whatsapp_message', 'Merhaba, paspas tasarımım hakkında bilgi almak istiyorum.' ) ); ?></textarea>
						<span class="description"><?php esc_html_e( 'Müşteri WhatsApp\'a yönlendirildiğinde otomatik olarak yazılacak mesaj.', 'entrymark-paspas' ); ?></span>
					</p>
				</div>
				<p><button type="submit" name="emc_pricing_save" class="button button-primary"><?php esc_html_e( 'Kaydet', 'entrymark-paspas' ); ?></button></p>
			</form>
		</div>
		<?php
	}

	public static function page_text_logo() {
		if ( isset( $_POST['emc_text_logo_save'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'emc_text_logo' ) ) {
			update_option( 'emc_text_max_length', absint( $_POST['emc_text_max_length'] ) );
			update_option( 'emc_logo_max_mb', absint( $_POST['emc_logo_max_mb'] ) );
			$fonts = array();
			if ( ! empty( $_POST['emc_font_name'] ) && is_array( $_POST['emc_font_name'] ) ) {
				foreach ( $_POST['emc_font_name'] as $i => $name ) {
					$family = isset( $_POST['emc_font_family'][ $i ] ) ? $_POST['emc_font_family'][ $i ] : '';
					$name   = sanitize_text_field( $name );
					$family = sanitize_text_field( $family );
					if ( $name || $family ) {
						$fonts[] = array( 'name' => $name, 'family' => $family );
					}
				}
			}
			update_option( 'emc_fonts', $fonts );
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Kaydedildi.', 'entrymark-paspas' ) . '</p></div>';
		}
		$fonts = get_option( 'emc_fonts', array() );
		if ( ! is_array( $fonts ) ) {
			$fonts = array();
		}
		if ( empty( $fonts ) ) {
			$fonts = array(
				array( 'name' => 'Arial', 'family' => 'Arial, sans-serif' ),
				array( 'name' => 'Playfair Display', 'family' => '"Playfair Display", serif' ),
				array( 'name' => 'Outfit', 'family' => '"Outfit", sans-serif' ),
			);
		}
		?>
		<div class="wrap emc-admin">
			<h1><?php esc_html_e( 'Yazı & Logo', 'entrymark-paspas' ); ?></h1>
			<form method="post">
				<?php wp_nonce_field( 'emc_text_logo' ); ?>
				<div class="emc-card">
					<p><label><?php esc_html_e( 'Yazı alanı max karakter', 'entrymark-paspas' ); ?></label> <input type="number" name="emc_text_max_length" value="<?php echo esc_attr( get_option( 'emc_text_max_length', 100 ) ); ?>" min="1" max="500"></p>
					<p><label><?php esc_html_e( 'Logo max dosya boyutu (MB)', 'entrymark-paspas' ); ?></label> <input type="number" name="emc_logo_max_mb" value="<?php echo esc_attr( get_option( 'emc_logo_max_mb', 5 ) ); ?>" min="1" max="20"></p>
				</div>
				<div class="emc-card" style="margin-top:1em;">
					<h3><?php esc_html_e( 'Font listesi', 'entrymark-paspas' ); ?></h3>
					<p class="description"><?php esc_html_e( 'Özelleştiricide kullanılacak yazı fontları. Name: dropdown\'da görünen ad; Family: CSS font-family değeri.', 'entrymark-paspas' ); ?></p>
					<table class="widefat striped">
						<thead><tr><th><?php esc_html_e( 'Ad (Name)', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'Font family (CSS)', 'entrymark-paspas' ); ?></th></tr></thead>
						<tbody>
							<?php foreach ( $fonts as $f ) : ?>
								<tr>
									<td><input type="text" name="emc_font_name[]" value="<?php echo esc_attr( isset( $f['name'] ) ? $f['name'] : '' ); ?>" placeholder="Arial" style="width:100%;"></td>
									<td><input type="text" name="emc_font_family[]" value="<?php echo esc_attr( isset( $f['family'] ) ? $f['family'] : '' ); ?>" placeholder="Arial, sans-serif" style="width:100%;"></td>
								</tr>
							<?php endforeach; ?>
							<tr>
								<td><input type="text" name="emc_font_name[]" value="" placeholder="<?php esc_attr_e( 'Yeni font', 'entrymark-paspas' ); ?>" style="width:100%;"></td>
								<td><input type="text" name="emc_font_family[]" value="" placeholder="'Font Name', serif" style="width:100%;"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<p><button type="submit" name="emc_text_logo_save" class="button button-primary"><?php esc_html_e( 'Kaydet', 'entrymark-paspas' ); ?></button></p>
			</form>
		</div>
		<?php
	}

	/**
	 * Siparişler: liste veya order_id ile detay.
	 */
	public static function page_orders() {
		$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
		if ( $order_id ) {
			$order = get_post( $order_id );
			if ( $order && $order->post_type === 'emc_order' ) {
				self::page_order_detail( $order_id );
				return;
			}
		}
		self::page_orders_list();
	}

	private static function page_orders_list() {
		$orders = get_posts( array(
			'post_type'      => 'emc_order',
			'post_status'    => 'any',
			'posts_per_page' => 50,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		$status_labels = array(
			'pending_payment' => __( 'Ödeme bekliyor', 'entrymark-paspas' ),
			'paid'            => __( 'Ödendi', 'entrymark-paspas' ),
			'failed'          => __( 'Başarısız', 'entrymark-paspas' ),
			'processing'     => __( 'İşleniyor', 'entrymark-paspas' ),
			'completed'      => __( 'Tamamlandı', 'entrymark-paspas' ),
		);
		?>
		<div class="wrap emc-admin">
			<h1><?php esc_html_e( 'Siparişler', 'entrymark-paspas' ); ?></h1>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Tarih', 'entrymark-paspas' ); ?></th>
						<th><?php esc_html_e( 'Sipariş No', 'entrymark-paspas' ); ?></th>
						<th><?php esc_html_e( 'Müşteri', 'entrymark-paspas' ); ?></th>
						<th><?php esc_html_e( 'Toplam', 'entrymark-paspas' ); ?></th>
						<th><?php esc_html_e( 'Durum', 'entrymark-paspas' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $orders as $order ) :
						$meta = EMC_Checkout::get_order_meta( $order->ID );
						$customer = $meta['customer'];
						$name = trim( ( isset( $customer['ad'] ) ? $customer['ad'] : '' ) . ' ' . ( isset( $customer['soyad'] ) ? $customer['soyad'] : '' ) );
						$status = $meta['status'];
						$status_label = isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : $status;
						?>
						<tr>
							<td><?php echo esc_html( get_the_date( '', $order ) ); ?></td>
							<td><a href="<?php echo esc_url( add_query_arg( 'order_id', $order->ID, admin_url( 'admin.php?page=emc-orders' ) ) ); ?>">#<?php echo esc_html( $order->ID ); ?></a></td>
							<td><?php echo esc_html( $name ); ?> (<?php echo esc_html( isset( $customer['email'] ) ? $customer['email'] : '' ); ?>)</td>
							<td><?php echo esc_html( number_format( $meta['total'], 2, ',', '.' ) ); ?> TL</td>
							<td><?php echo esc_html( $status_label ); ?></td>
						</tr>
					<?php endforeach; ?>
					<?php if ( empty( $orders ) ) : ?>
						<tr><td colspan="5"><?php esc_html_e( 'Henüz sipariş yok.', 'entrymark-paspas' ); ?></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	private static function page_order_detail( $order_id ) {
		$order = get_post( $order_id );
		if ( ! $order || $order->post_type !== 'emc_order' ) {
			wp_safe_redirect( admin_url( 'admin.php?page=emc-orders' ) );
			exit;
		}
		$meta = EMC_Checkout::get_order_meta( $order_id );
		$status_labels = array(
			'pending_payment' => __( 'Ödeme bekliyor', 'entrymark-paspas' ),
			'paid'            => __( 'Ödendi', 'entrymark-paspas' ),
			'failed'          => __( 'Başarısız', 'entrymark-paspas' ),
			'processing'     => __( 'İşleniyor', 'entrymark-paspas' ),
			'completed'       => __( 'Tamamlandı', 'entrymark-paspas' ),
		);
		$status_label = isset( $status_labels[ $meta['status'] ] ) ? $status_labels[ $meta['status'] ] : $meta['status'];
		?>
		<div class="wrap emc-admin">
			<p><a href="<?php echo esc_url( admin_url( 'admin.php?page=emc-orders' ) ); ?>">&larr; <?php esc_html_e( 'Sipariş listesine dön', 'entrymark-paspas' ); ?></a></p>
			<h1><?php esc_html_e( 'Sipariş #', 'entrymark-paspas' ); ?><?php echo esc_html( $order_id ); ?></h1>
			<div class="emc-card" style="max-width:800px;">
				<h3><?php esc_html_e( 'Müşteri', 'entrymark-paspas' ); ?></h3>
				<p><strong><?php esc_html_e( 'Ad Soyad', 'entrymark-paspas' ); ?>:</strong> <?php echo esc_html( trim( ( $meta['customer']['ad'] ?? '' ) . ' ' . ( $meta['customer']['soyad'] ?? '' ) ) ); ?></p>
				<p><strong><?php esc_html_e( 'E-posta', 'entrymark-paspas' ); ?>:</strong> <?php echo esc_html( $meta['customer']['email'] ?? '' ); ?></p>
				<p><strong><?php esc_html_e( 'Telefon', 'entrymark-paspas' ); ?>:</strong> <?php echo esc_html( $meta['customer']['telefon'] ?? '' ); ?></p>
				<p><strong><?php esc_html_e( 'Adres', 'entrymark-paspas' ); ?>:</strong><br><?php echo nl2br( esc_html( $meta['customer']['adres'] ?? '' ) ); ?></p>
			</div>
			<div class="emc-card" style="max-width:800px;margin-top:1em;">
				<h3><?php esc_html_e( 'Sipariş özeti', 'entrymark-paspas' ); ?></h3>
				<p><strong><?php esc_html_e( 'Durum', 'entrymark-paspas' ); ?>:</strong> <?php echo esc_html( $status_label ); ?></p>
				<p><strong><?php esc_html_e( 'Toplam', 'entrymark-paspas' ); ?>:</strong> <?php echo esc_html( number_format( $meta['total'], 2, ',', '.' ) ); ?> TL</p>
				<table class="widefat striped" style="margin-top:0.5em;">
					<thead><tr><th><?php esc_html_e( 'Ürün', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'Ölçü', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'Gönderim', 'entrymark-paspas' ); ?></th><th><?php esc_html_e( 'Tutar', 'entrymark-paspas' ); ?></th></tr></thead>
					<tbody>
						<?php foreach ( $meta['items'] as $item ) :
							$sum = isset( $item['summary'] ) ? $item['summary'] : array();
							$pr = isset( $item['pricing'] ) ? $item['pricing'] : array();
							?>
							<tr>
								<td><?php echo esc_html( isset( $sum['texture_name'] ) ? $sum['texture_name'] : 'Paspas' ); ?></td>
								<td><?php echo esc_html( isset( $sum['size_label'] ) ? $sum['size_label'] : '—' ); ?></td>
								<td><?php echo esc_html( isset( $sum['shipping_label'] ) ? $sum['shipping_label'] : '—' ); ?></td>
								<td><?php echo esc_html( number_format( isset( $pr['total'] ) ? $pr['total'] : 0, 2, ',', '.' ) ); ?> TL</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php
			$has_preview = false;
			foreach ( $meta['items'] as $item ) {
				if ( ! empty( $item['preview_data_url'] ) ) {
					$has_preview = true;
					break;
				}
			}
			if ( $has_preview ) :
				$first_preview = '';
				foreach ( $meta['items'] as $item ) {
					if ( ! empty( $item['preview_data_url'] ) ) {
						$first_preview = $item['preview_data_url'];
						break;
					}
				}
				if ( $first_preview ) :
					?>
			<div class="emc-card" style="max-width:800px;margin-top:1em;">
				<h3><?php esc_html_e( 'Tasarım önizlemesi', 'entrymark-paspas' ); ?></h3>
				<img src="<?php echo esc_url( $first_preview ); ?>" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;">
			</div>
			<?php
				endif;
			endif;
			?>
		</div>
		<?php
	}

	public static function page_paytr() {
		if ( isset( $_POST['emc_paytr_save'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'emc_paytr' ) ) {
			update_option( 'emc_paytr_merchant_id', sanitize_text_field( $_POST['emc_paytr_merchant_id'] ?? '' ) );
			update_option( 'emc_paytr_merchant_key', sanitize_text_field( $_POST['emc_paytr_merchant_key'] ?? '' ) );
			update_option( 'emc_paytr_merchant_salt', sanitize_text_field( $_POST['emc_paytr_merchant_salt'] ?? '' ) );
			update_option( 'emc_paytr_test_mode', ! empty( $_POST['emc_paytr_test_mode'] ) ? 1 : 0 );
			echo '<div class="notice notice-success"><p>' . esc_html__( 'PayTR ayarları kaydedildi.', 'entrymark-paspas' ) . '</p></div>';
		}
		?>
		<div class="wrap emc-admin">
			<h1><?php esc_html_e( 'PayTR Ödeme', 'entrymark-paspas' ); ?></h1>
			<form method="post">
				<?php wp_nonce_field( 'emc_paytr' ); ?>
				<div class="emc-card">
					<p><label><?php esc_html_e( 'Merchant ID', 'entrymark-paspas' ); ?></label><br><input type="text" name="emc_paytr_merchant_id" value="<?php echo esc_attr( get_option( 'emc_paytr_merchant_id', '' ) ); ?>" class="regular-text"></p>
					<p><label><?php esc_html_e( 'Merchant Key', 'entrymark-paspas' ); ?></label><br><input type="text" name="emc_paytr_merchant_key" value="<?php echo esc_attr( get_option( 'emc_paytr_merchant_key', '' ) ); ?>" class="regular-text"></p>
					<p><label><?php esc_html_e( 'Merchant Salt', 'entrymark-paspas' ); ?></label><br><input type="text" name="emc_paytr_merchant_salt" value="<?php echo esc_attr( get_option( 'emc_paytr_merchant_salt', '' ) ); ?>" class="regular-text"></p>
					<p><label><input type="checkbox" name="emc_paytr_test_mode" value="1" <?php checked( get_option( 'emc_paytr_test_mode', 1 ), 1 ); ?>><?php esc_html_e( 'Test modu', 'entrymark-paspas' ); ?></label></p>
				</div>
				<p><button type="submit" name="emc_paytr_save" class="button button-primary"><?php esc_html_e( 'Kaydet', 'entrymark-paspas' ); ?></button></p>
			</form>
			<div class="emc-card" style="margin-top:1em;">
				<h3><?php esc_html_e( 'Bildirim URL (Callback)', 'entrymark-paspas' ); ?></h3>
				<p><?php esc_html_e( 'PayTR Mağaza Paneli > Destek & Kurulum > Ayarlar > Bildirim URL bölümüne aşağıdaki adresi ekleyin:', 'entrymark-paspas' ); ?></p>
				<code style="display:block;padding:8px;background:#f5f5f5;word-break:break-all;"><?php echo esc_html( add_query_arg( array( 'emc_paytr_callback' => '1' ), home_url( '/' ) ) ); ?></code>
			</div>
			<p class="description" style="display:none;"><?php esc_html_e( 'Ödeme iframe ve callback URL’leri eklenti içinde oluşturulacak (ileride).', 'entrymark-paspas' ); ?></p>
		</div>
		<?php
	}

	// Sepet Sayfası Ayarları
	public static function sanitize_trust_items( $input ) {
		if ( ! is_array( $input ) ) return array();
		$out = array();
		foreach ( $input as $row ) {
			$out[] = array(
				'icon'  => sanitize_text_field( $row['icon'] ?? '' ),
				'title' => sanitize_text_field( $row['title'] ?? '' ),
				'desc'  => sanitize_text_field( $row['desc'] ?? '' ),
			);
		}
		return $out;
	}

	public static function page_cart_settings() {
		if ( isset( $_POST['emc_cart_save'] ) ) {
			update_option( 'emc_cart_title', sanitize_text_field( $_POST['emc_cart_title'] ?? 'Sepetim' ) );
			update_option( 'emc_cart_subtitle', sanitize_text_field( $_POST['emc_cart_subtitle'] ?? 'ürün' ) );
			update_option( 'emc_payment_logos', esc_url_raw( $_POST['emc_payment_logos'] ?? '' ) );
			if ( isset( $_POST['emc_trust_items'] ) ) {
				update_option( 'emc_trust_items', self::sanitize_trust_items( $_POST['emc_trust_items'] ) );
			}
			echo '<div class="notice notice-success"><p>Ayarlar kaydedildi.</p></div>';
		}

		$title = get_option( 'emc_cart_title', 'Sepetim' );
		$subtitle = get_option( 'emc_cart_subtitle', 'ürün' );
		$payment_logos = get_option( 'emc_payment_logos', '' );
		$trust_items = get_option( 'emc_trust_items', array(
			array( 'icon' => 'shield', 'title' => 'Güvenli Ödeme', 'desc' => '256-bit SSL şifreleme' ),
			array( 'icon' => 'truck', 'title' => 'Ücretsiz Kargo', 'desc' => '500 TL üzeri siparişlerde' ),
			array( 'icon' => 'refresh', 'title' => 'Kolay İade', 'desc' => '14 gün içinde ücretsiz' ),
		) );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Sepet Sayfası Ayarları', 'entrymark-paspas' ); ?></h1>
			<form method="post">
				<?php wp_nonce_field( 'emc_cart_settings' ); ?>
				
				<h2 class="title">Sayfa Başlığı</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="emc_cart_title">Ana Başlık</label></th>
						<td><input type="text" id="emc_cart_title" name="emc_cart_title" value="<?php echo esc_attr( $title ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="emc_cart_subtitle">Ürün Etiketi</label></th>
						<td><input type="text" id="emc_cart_subtitle" name="emc_cart_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="regular-text" placeholder="örn: ürün, adet, item">
						<p class="description">Sepet başlığında parantez içinde gösterilir: "Sepetim (3 ürün)"</p></td>
					</tr>
				</table>

				<h2 class="title">Güven Rozetleri (Trust Strip)</h2>
				<table class="form-table">
					<?php $icons = array( 'shield' => 'Güvenlik/Kalkan', 'truck' => 'Kargo/Truck', 'refresh' => 'İade/Refresh', 'credit-card' => 'Kredi Kartı', 'lock' => 'Kilit', 'check' => 'Onay' ); ?>
					<?php for ( $i = 0; $i < 3; $i++ ) : ?>
					<tr>
						<th scope="row">Rozet <?php echo $i + 1; ?></th>
						<td>
							<select name="emc_trust_items[<?php echo $i; ?>][icon]" style="width:120px;">
								<?php foreach ( $icons as $key => $label ) : ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $trust_items[$i]['icon'] ?? '', $key ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
							<input type="text" name="emc_trust_items[<?php echo $i; ?>][title]" value="<?php echo esc_attr( $trust_items[$i]['title'] ?? '' ); ?>" placeholder="Başlık" style="width:150px;">
							<input type="text" name="emc_trust_items[<?php echo $i; ?>][desc]" value="<?php echo esc_attr( $trust_items[$i]['desc'] ?? '' ); ?>" placeholder="Açıklama" style="width:200px;">
						</td>
					</tr>
					<?php endfor; ?>
				</table>

				<h2 class="title">Ödeme Logoları</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="emc_payment_logos">Logolar Görseli</label></th>
						<td>
							<input type="url" id="emc_payment_logos" name="emc_payment_logos" value="<?php echo esc_attr( $payment_logos ); ?>" class="regular-text" placeholder="https://...">
							<button type="button" class="button" onclick="var uploader=wp.media({title:'Logolar Görseli Seç',library:{type:'image'},multiple:false}); uploader.on('select',function(){var attachment=uploader.state().get('selection').first().toJSON();jQuery('#emc_payment_logos').val(attachment.url);});uploader.open();">Görsel Seç</button>
							<p class="description">Tüm ödeme logolarını tek görselde birleştirin (Visa, MasterCard, v.b.) önerilen boyut: 400x40px</p>
							<?php if ( $payment_logos ) : ?>
								<br><img src="<?php echo esc_url( $payment_logos ); ?>" style="max-width:400px; margin-top:10px; border:1px solid #ddd; padding:5px;">
							<?php endif; ?>
						</td>
					</tr>
				</table>

				<?php submit_button( 'Ayarları Kaydet', 'primary', 'emc_cart_save' ); ?>
			</form>
		</div>
		<?php
	}
}
