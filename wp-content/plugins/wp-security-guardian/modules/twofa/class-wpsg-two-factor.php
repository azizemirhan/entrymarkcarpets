<?php
/**
 * Two-Factor Authentication (TOTP).
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSG_Two_Factor {

	public function init() {
		if ( ! $this->is_available() ) {
			return;
		}

		add_action( 'show_user_profile', array( $this, 'profile_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'profile_fields' ) );
		add_action( 'personal_options_update', array( $this, 'save_profile' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_profile' ) );
		add_action( 'wp_authenticate_user', array( $this, 'validate_2fa' ), 10, 2 );
		add_filter( 'login_form_middle', array( $this, 'login_form_field' ) );
	}

	private function is_available() {
		return class_exists( 'PragmaRX\Google2FA\Google2FA' );
	}

	public function profile_fields( $user ) {
		if ( ! current_user_can( 'edit_user', $user->ID ) ) {
			return;
		}

		$enabled = get_user_meta( $user->ID, 'wpsg_2fa_enabled', true );
		$secret  = get_user_meta( $user->ID, 'wpsg_2fa_secret', true );

		if ( ! $secret ) {
			$secret = $this->generate_secret();
		}
		?>
		<h2><?php esc_html_e( 'İki Faktörlü Kimlik Doğrulama', 'wp-security-guardian' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( '2FA Durumu', 'wp-security-guardian' ); ?></th>
				<td>
					<?php if ( $enabled ) : ?>
						<p><?php esc_html_e( 'Etkin', 'wp-security-guardian' ); ?></p>
						<input type="hidden" name="wpsg_2fa_disable" value="0" />
						<label>
							<input type="checkbox" name="wpsg_2fa_disable" value="1" />
							<?php esc_html_e( '2FA\'yı devre dışı bırak', 'wp-security-guardian' ); ?>
						</label>
					<?php else : ?>
						<p><?php esc_html_e( 'Devre dışı', 'wp-security-guardian' ); ?></p>
						<?php
						$google2fa = new \PragmaRX\Google2FA\Google2FA();
						$qr_url = $google2fa->getQRCodeUrl(
							get_bloginfo( 'name' ),
							$user->user_email,
							$secret
						);
						?>
						<p>
							<label>
								<input type="checkbox" name="wpsg_2fa_enable" value="1" />
								<?php esc_html_e( '2FA\'yı etkinleştir', 'wp-security-guardian' ); ?>
							</label>
						</p>
						<p class="description">
							<?php esc_html_e( 'Google Authenticator veya benzeri bir uygulama kullanın. Anahtar:', 'wp-security-guardian' ); ?>
							<code><?php echo esc_html( $secret ); ?></code>
						</p>
						<p>
							<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo esc_url( $qr_url ); ?>" alt="QR Code" />
						</p>
						<input type="hidden" name="wpsg_2fa_secret" value="<?php echo esc_attr( $secret ); ?>" />
					<?php endif; ?>
				</td>
			</tr>
		</table>
		<?php
	}

	public function save_profile( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		if ( ! empty( $_POST['wpsg_2fa_disable'] ) ) {
			delete_user_meta( $user_id, 'wpsg_2fa_enabled' );
			delete_user_meta( $user_id, 'wpsg_2fa_secret' );
		} elseif ( ! empty( $_POST['wpsg_2fa_enable'] ) && ! empty( $_POST['wpsg_2fa_secret'] ) ) {
			$secret = sanitize_text_field( wp_unslash( $_POST['wpsg_2fa_secret'] ) );
			update_user_meta( $user_id, 'wpsg_2fa_secret', $secret );
			update_user_meta( $user_id, 'wpsg_2fa_enabled', 1 );
		}
	}

	private function generate_secret() {
		$google2fa = new \PragmaRX\Google2FA\Google2FA();
		return $google2fa->generateSecretKey( 16 );
	}

	public function login_form_field( $content ) {
		// Sadece varsayılan wp-login.php ekranında 2FA alanı göster; tema giriş/hesabım sayfalarında gösterme.
		if ( ! $this->is_wp_login_screen() ) {
			return $content . '<input type="hidden" name="wpsg_2fa_skip" value="1" />';
		}
		return $content . '
		<p>
			<label for="wpsg_2fa_code">' . esc_html__( 'İki Faktörlü Kod', 'wp-security-guardian' ) . '</label>
			<input type="text" name="wpsg_2fa_code" id="wpsg_2fa_code" class="input" size="20" autocomplete="one-time-code" placeholder="000000" />
		</p>';
	}

	/**
	 * Şu an varsayılan WordPress giriş ekranında mıyız (wp-login.php)?
	 */
	private function is_wp_login_screen() {
		global $pagenow;
		return ( isset( $pagenow ) && $pagenow === 'wp-login.php' );
	}

	public function validate_2fa( $user, $password ) {
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		// Giriş yap / Hesabım sayfalarında 2FA atlanır (tema tarafında alan gösterilmiyor).
		if ( ! empty( $_POST['wpsg_2fa_skip'] ) ) {
			return $user;
		}

		$enabled = get_user_meta( $user->ID, 'wpsg_2fa_enabled', true );
		if ( ! $enabled ) {
			return $user;
		}

		$code = isset( $_POST['wpsg_2fa_code'] ) ? sanitize_text_field( wp_unslash( $_POST['wpsg_2fa_code'] ) ) : '';
		$secret = get_user_meta( $user->ID, 'wpsg_2fa_secret', true );

		if ( empty( $secret ) || empty( $code ) ) {
			return new WP_Error(
				'wpsg_2fa_required',
				__( 'İki faktörlü doğrulama kodu gerekli.', 'wp-security-guardian' )
			);
		}

		$google2fa = new \PragmaRX\Google2FA\Google2FA();
		$valid = $google2fa->verifyKey( $secret, $code, 1 );

		if ( ! $valid ) {
			return new WP_Error(
				'wpsg_2fa_invalid',
				__( 'Geçersiz iki faktörlü doğrulama kodu.', 'wp-security-guardian' )
			);
		}

		return $user;
	}
}
