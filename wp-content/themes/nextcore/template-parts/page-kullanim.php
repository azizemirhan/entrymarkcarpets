<?php
/**
 * Kullanım Koşulları sayfası — sitenin diğer tasarımlarına uyumlu
 *
 * @package nextcore
 */

$kullanim_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_kullanim_' . $key, $default );
};
$eyebrow   = $kullanim_get( 'eyebrow', 'Yasal' );
$subtitle  = $kullanim_get( 'subtitle', 'Web sitemizi ve hizmetlerimizi kullanım şartlarını açıklayan yasal metindir.' );
?>
<div class="kullanim-page">
<section class="kullanim-hero">
	<div class="kullanim-hero-glow1"></div>
	<div class="kullanim-hero-glow2"></div>
	<div class="kullanim-hero-content">
		<div class="kullanim-hero-eyebrow"><span></span><span class="kullanim-hero-eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span><span></span></div>
		<h1 class="kullanim-hero-title"><em>Kullanım</em> Koşulları</h1>
		<p class="kullanim-hero-desc"><?php echo esc_html( $subtitle ); ?></p>
	</div>
</section>
<section class="kullanim-content">
	<div class="kullanim-content-inner">
		<?php
		while ( have_posts() ) :
			the_post();
			$content = get_the_content();
			if ( ! empty( trim( $content ) ) ) {
				?><div class="kullanim-body entry-content"><?php the_content(); ?></div><?php
			} else {
				?>
				<div class="kullanim-body">
					<div class="kullanim-intro">
						<p><strong>Entry Mark Carpets</strong> web sitesini ve hizmetlerini kullanarak bu Kullanım Koşullarını kabul etmiş sayılırsınız. Lütfen koşulları dikkatle okuyunuz.</p>
					</div>
					<div class="kullanim-block"><h2>1. Genel Hükümler</h2><p>Bu koşullar, entrymarkcarpets.com web sitesi ve ilgili hizmetlerin kullanımına ilişkindir. Siteye erişim ve kullanım, bu koşulların kabulü anlamına gelir.</p></div>
					<div class="kullanim-block"><h2>2. Hizmetlerin Kapsamı</h2><p>Sitemiz üzerinden özel halı ve paspas tasarımı, sipariş verme, fiyat teklifi alma ve müşteri hizmetleri sunulmaktadır.</p></div>
					<div class="kullanim-block"><h2>3. Kullanıcı Yükümlülükleri</h2><p>Siteyi yasalara uygun şekilde kullanmanız, yanıltıcı bilgi vermemeniz ve başkalarının haklarına saygı göstermeniz gerekmektedir.</p></div>
					<div class="kullanim-block"><h2>4. Fikri Mülkiyet</h2><p>Sitedeki tüm içerik, logo ve tasarımlar Entry Mark Carpets'e aittir. İzinsiz kopyalama veya ticari kullanım yasaktır.</p></div>
					<div class="kullanim-block"><h2>5. Sipariş ve Ödeme</h2><p>Siparişler onaylandıktan sonra kesinleşir. Ödeme koşulları ve teslimat süreleri sipariş sayfasında belirtilir.</p></div>
					<div class="kullanim-block"><h2>6. Sorumluluk Sınırlaması</h2><p>Sitemiz "olduğu gibi" sunulmaktadır. Dolaylı zararlardan sorumluluk kabul etmemekteyiz.</p></div>
					<div class="kullanim-block"><h2>7. İletişim</h2><p>Sorularınız için <a href="mailto:info@entrymarkcarpets.com">info@entrymarkcarpets.com</a> adresinden bize ulaşabilirsiniz.</p></div>
					<div class="kullanim-update"><p><strong>Son güncelleme:</strong> <?php echo esc_html( get_the_modified_date( 'd.m.Y' ) ); ?></p></div>
				</div>
				<?php
			}
		endwhile;
		?>
	</div>
</section>
<div class="kullanim-back">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="kullanim-back-btn">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
		Ana Sayfaya Dön
	</a>
</div>
</div>
