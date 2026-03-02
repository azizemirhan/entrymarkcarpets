<?php
/**
 * Gizlilik Politikası sayfası — sitenin diğer tasarımlarına uyumlu
 *
 * @package nextcore
 */

$gizlilik_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_gizlilik_' . $key, $default );
};
$eyebrow   = $gizlilik_get( 'eyebrow', 'Yasal' );
$subtitle  = $gizlilik_get( 'subtitle', 'Kişisel verilerinizin nasıl toplandığını, kullanıldığını ve korunduğunu açıklayan politika metnidir.' );
?>

<div class="gizlilik-page">

<section class="gizlilik-hero">
	<div class="gizlilik-hero-glow1"></div>
	<div class="gizlilik-hero-glow2"></div>
	<div class="gizlilik-hero-content">
		<div class="gizlilik-hero-eyebrow"><span></span><span class="gizlilik-hero-eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span><span></span></div>
		<h1 class="gizlilik-hero-title"><em>Gizlilik</em> Politikası</h1>
		<p class="gizlilik-hero-desc"><?php echo esc_html( $subtitle ); ?></p>
	</div>
</section>

<section class="gizlilik-content">
	<div class="gizlilik-content-inner">
		<?php
		while ( have_posts() ) :
			the_post();
			$content = get_the_content();
			if ( ! empty( trim( $content ) ) ) {
				?>
				<div class="gizlilik-body entry-content">
					<?php the_content(); ?>
				</div>
				<?php
			} else {
				?>
				<div class="gizlilik-body">
					<div class="gizlilik-intro">
						<p><strong>Entry Mark Carpets</strong> olarak, gizliliğinize saygı duyuyoruz. Bu Gizlilik Politikası, web sitemizi ziyaret ettiğinizde veya hizmetlerimizi kullandığınızda kişisel verilerinizin nasıl toplandığını, kullanıldığını ve korunduğunu açıklamaktadır.</p>
					</div>

					<div class="gizlilik-block">
						<h2>1. Topladığımız Bilgiler</h2>
						<p>Ürün siparişleri, iletişim formları, bülten abonelikleri ve site kullanımı sırasında ad, soyad, e-posta, telefon, adres ve benzeri kişisel bilgilerinizi toplayabiliriz. Ayrıca çerezler ve benzeri teknolojiler aracılığıyla tarayıcı verileriniz toplanabilir.</p>
					</div>

					<div class="gizlilik-block">
						<h2>2. Bilgilerin Kullanım Amaçları</h2>
						<p>Toplanan veriler; siparişlerinizin işlenmesi, müşteri hizmetleri sunumu, pazarlama iletişimleri (izin verdiğiniz takdirde), yasal yükümlülüklerin yerine getirilmesi ve site deneyiminin iyileştirilmesi amacıyla kullanılmaktadır.</p>
					</div>

					<div class="gizlilik-block">
						<h2>3. Bilgilerin Paylaşımı</h2>
						<p>Kişisel verileriniz, ödeme işlemcileri, kargo firmaları, hosting sağlayıcıları gibi hizmet ortaklarımız ile yalnızca hizmet sunumu için gerekli olduğu ölçüde paylaşılır. Verileriniz izniniz olmadan üçüncü taraflara satılmaz veya ticari amaçla kullanılmaz.</p>
					</div>

					<div class="gizlilik-block">
						<h2>4. Veri Güvenliği</h2>
						<p>Verilerinizi yetkisiz erişime, kayba veya değişikliğe karşı korumak için teknik ve idari önlemler alıyoruz. SSL şifreleme, güvenli ödeme sistemleri ve erişim kontrolleri kullanılmaktadır.</p>
					</div>

					<div class="gizlilik-block">
						<h2>5. Haklarınız</h2>
						<p>KVKK kapsamında kişisel verilerinize erişim, düzeltme, silme talep etme ve işlemeye itiraz etme haklarına sahipsiniz. Bu haklarınızı kullanmak için <a href="mailto:kvkk@entrymarkcarpets.com">kvkk@entrymarkcarpets.com</a> adresinden bizimle iletişime geçebilirsiniz.</p>
					</div>

					<div class="gizlilik-block">
						<h2>6. İletişim</h2>
						<p>Gizlilik politikamız hakkında sorularınız için <a href="mailto:info@entrymarkcarpets.com">info@entrymarkcarpets.com</a> adresinden bize ulaşabilirsiniz.</p>
					</div>

					<div class="gizlilik-update">
						<p><strong>Son güncelleme:</strong> <?php echo esc_html( get_the_modified_date( 'd.m.Y' ) ); ?></p>
					</div>
				</div>
				<?php
			}
		endwhile;
		?>
	</div>
</section>

<div class="gizlilik-back">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gizlilik-back-btn">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
		Ana Sayfaya Dön
	</a>
</div>

</div>
