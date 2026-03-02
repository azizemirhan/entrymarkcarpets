<?php
/**
 * KVKK Aydınlatma Metni sayfası — sitenin diğer tasarımlarına uyumlu
 *
 * @package nextcore
 */

$kvkk_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_kvkk_' . $key, $default );
};
$eyebrow   = $kvkk_get( 'eyebrow', 'Yasal' );
$subtitle  = $kvkk_get( 'subtitle', '6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında hazırlanmış aydınlatma metnidir.' );
?>

<div class="kvkk-page">

<section class="kvkk-hero">
	<div class="kvkk-hero-glow1"></div>
	<div class="kvkk-hero-glow2"></div>
	<div class="kvkk-hero-content">
		<div class="kvkk-hero-eyebrow"><span></span><span class="kvkk-hero-eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span><span></span></div>
		<h1 class="kvkk-hero-title">KVKK <em>Aydınlatma</em> Metni</h1>
		<p class="kvkk-hero-desc"><?php echo esc_html( $subtitle ); ?></p>
	</div>
</section>

<section class="kvkk-content">
	<div class="kvkk-content-inner">
		<?php
		while ( have_posts() ) :
			the_post();
			$content = get_the_content();
			if ( ! empty( trim( $content ) ) ) {
				?>
				<div class="kvkk-body entry-content">
					<?php the_content(); ?>
				</div>
				<?php
			} else {
				// Varsayılan KVKK metni — sayfa içeriği boşsa gösterilir
				?>
				<div class="kvkk-body">
					<div class="kvkk-intro">
						<p><strong>Entry Mark Carpets</strong> olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında veri sorumlusu sıfatıyla kişisel verilerinizi işlemekteyiz. Bu aydınlatma metni, kişisel verilerinizin işlenmesine ilişkin sizi bilgilendirmek amacıyla hazırlanmıştır.</p>
					</div>

					<div class="kvkk-block">
						<h2>1. Kişisel Verilerin İşlenme Amaçları</h2>
						<p>Toplanan kişisel verileriniz; ürün ve hizmet taleplerinizin karşılanması, sözleşme süreçlerinin yürütülmesi, iletişim faaliyetlerinin gerçekleştirilmesi, yasal yükümlülüklerimizin yerine getirilmesi ve meşru menfaatlerimiz kapsamında işlenmektedir.</p>
					</div>

					<div class="kvkk-block">
						<h2>2. İşlenen Kişisel Veri Kategorileri</h2>
						<ul>
							<li><strong>Kimlik bilgileri:</strong> Ad, soyad, T.C. kimlik numarası (zorunlu hallerde)</li>
							<li><strong>İletişim bilgileri:</strong> E-posta, telefon numarası, adres</li>
							<li><strong>İşlem güvenliği bilgileri:</strong> IP adresi, çerez kayıtları, oturum bilgileri</li>
							<li><strong>Müşteri işlem bilgileri:</strong> Sipariş bilgileri, fatura bilgileri</li>
						</ul>
					</div>

					<div class="kvkk-block">
						<h2>3. Veri İşleme Hukuki Sebepleri</h2>
						<p>Kişisel verileriniz; açık rızanız, sözleşmenin kurulması veya ifası, yasal zorunluluk ve meşru menfaatlerimiz kapsamında KVKK'nın 5. ve 6. maddelerinde belirtilen şartlara uygun olarak işlenmektedir.</p>
					</div>

					<div class="kvkk-block">
						<h2>4. Verilerin Aktarılması</h2>
						<p>Kişisel verileriniz; yasal zorunluluklar, hizmet sağlayıcılarımız (ödeme, kargo, hosting vb.) ile iş birliği çerçevesinde KVKK'nın 8. ve 9. maddelerinde öngörülen şartlara uygun olarak aktarılabilmektedir.</p>
					</div>

					<div class="kvkk-block">
						<h2>5. Haklarınız</h2>
						<p>KVKK'nın 11. maddesi kapsamında; kişisel verilerinizin işlenip işlenmediğini öğrenme, işlenmişse buna ilişkin bilgi talep etme, işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme, yurt içinde veya yurt dışında aktarıldığı üçüncü kişileri bilme, eksik veya yanlış işlenmiş olması hâlinde düzeltilmesini isteme, silinmesini veya yok edilmesini isteme, otomatik sistemler vasıtasıyla analiz edilmesi suretiyle aleyhinize bir sonucun ortaya çıkmasına itiraz etme ve Kanun’un 7. maddesinde öngörülen şartlar çerçevesinde verilerin silinmesini veya yok edilmesini talep etme haklarına sahipsiniz.</p>
						<p>Başvurularınızı <a href="mailto:kvkk@entrymarkcarpets.com">kvkk@entrymarkcarpets.com</a> adresine veya yazılı olarak şirket adresimize iletebilirsiniz. Başvurular en geç 30 gün içinde değerlendirilerek sonuçlandırılacaktır.</p>
					</div>

					<div class="kvkk-block">
						<h2>6. Veri Güvenliği</h2>
						<p>Kişisel verilerinizin güvenliği için teknik ve idari tedbirler almaktayız. Verileriniz yetkisiz erişime, kayba veya değişikliğe karşı korunmaktadır.</p>
					</div>

					<div class="kvkk-update">
						<p><strong>Son güncelleme:</strong> <?php echo esc_html( get_the_modified_date( 'd.m.Y' ) ); ?></p>
					</div>
				</div>
				<?php
			}
		endwhile;
		?>
	</div>
</section>

<div class="kvkk-back">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="kvkk-back-btn">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
		Ana Sayfaya Dön
	</a>
</div>

</div>
