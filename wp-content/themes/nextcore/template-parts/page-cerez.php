<?php
/**
 * Çerez Politikası sayfası — sitenin diğer tasarımlarına uyumlu
 *
 * @package nextcore
 */

$cerez_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_cerez_' . $key, $default );
};
$eyebrow   = $cerez_get( 'eyebrow', 'Yasal' );
$subtitle  = $cerez_get( 'subtitle', 'Bu web sitesinde çerezlerin kullanımına ilişkin bilgilendirme metnidir.' );
?>

<div class="cerez-page">

<section class="cerez-hero">
	<div class="cerez-hero-glow1"></div>
	<div class="cerez-hero-glow2"></div>
	<div class="cerez-hero-content">
		<div class="cerez-hero-eyebrow"><span></span><span class="cerez-hero-eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span><span></span></div>
		<h1 class="cerez-hero-title"><em>Çerez</em> Politikası</h1>
		<p class="cerez-hero-desc"><?php echo esc_html( $subtitle ); ?></p>
	</div>
</section>

<section class="cerez-content">
	<div class="cerez-content-inner">
		<?php
		while ( have_posts() ) :
			the_post();
			$content = get_the_content();
			if ( ! empty( trim( $content ) ) ) {
				?>
				<div class="cerez-body entry-content">
					<?php the_content(); ?>
				</div>
				<?php
			} else {
				?>
				<div class="cerez-body">
					<div class="cerez-intro">
						<p><strong>Entry Mark Carpets</strong> olarak, web sitemizde kullanıcı deneyimini iyileştirmek ve site işlevselliğini sağlamak amacıyla çerezler kullanıyoruz. Bu politika, çerezlerin ne olduğunu, nasıl kullandığımızı ve tercihlerinizi nasıl yönetebileceğinizi açıklamaktadır.</p>
					</div>

					<div class="cerez-block">
						<h2>1. Çerez Nedir?</h2>
						<p>Çerezler, bir web sitesinin bilgisayarınıza veya mobil cihazınıza yerleştirdiği küçük metin dosyalarıdır. Tarayıcınız aracılığıyla tekrar sitemizi ziyaret ettiğinizde bu dosyalar okunur ve site sizin tercihlerinizi hatırlar. Çerezler, sitenin düzgün çalışması, güvenliğin sağlanması ve kullanıcı deneyiminin iyileştirilmesi için kullanılır.</p>
					</div>

					<div class="cerez-block">
						<h2>2. Kullandığımız Çerez Türleri</h2>
						<ul>
							<li><strong>Zorunlu çerezler:</strong> Sitenin temel işlevlerini yerine getirmesi için gereklidir. Sepet, oturum, güvenlik gibi özellikler için kullanılır.</li>
							<li><strong>İşlevsel çerezler:</strong> Dil tercihi, tema seçimi gibi kullanıcı ayarlarınızı hatırlamamızı sağlar.</li>
							<li><strong>Analitik çerezler:</strong> Sitenin nasıl kullanıldığını anlamamıza, trafik ve sayfa performansını ölçmemize yardımcı olur.</li>
							<li><strong>Pazarlama çerezleri:</strong> Size daha uygun reklamlar göstermek ve kampanya etkinliğini ölçmek için kullanılabilir.</li>
						</ul>
					</div>

					<div class="cerez-block">
						<h2>3. Çerezleri Nasıl Yönetebilirsiniz?</h2>
						<p>Tarayıcı ayarlarınızdan çerezleri engelleyebilir veya silinebilir hale getirebilirsiniz. Ancak çerezleri devre dışı bırakmanız halinde sitenin bazı özellikleri düzgün çalışmayabilir. Tarayıcı ayarlarına genellikle Ayarlar &gt; Gizlilik ve Güvenlik bölümünden ulaşabilirsiniz.</p>
					</div>

					<div class="cerez-block">
						<h2>4. Üçüncü Taraf Çerezleri</h2>
						<p>Sitemizde Google Analytics, reklam ağları veya sosyal medya eklentileri gibi üçüncü taraf hizmetlerin çerezleri de kullanılabilir. Bu hizmetlerin kendi gizlilik politikaları bulunmaktadır.</p>
					</div>

					<div class="cerez-block">
						<h2>5. Politika Güncellemeleri</h2>
						<p>Bu Çerez Politikası güncellendiğinde, değişiklikler bu sayfada yayınlanacaktır. Önemli değişikliklerde sizi bilgilendirebiliriz.</p>
					</div>

					<div class="cerez-block">
						<h2>6. İletişim</h2>
						<p>Çerez kullanımımız hakkında sorularınız varsa <a href="mailto:info@entrymarkcarpets.com">info@entrymarkcarpets.com</a> adresinden bize ulaşabilirsiniz.</p>
					</div>

					<div class="cerez-update">
						<p><strong>Son güncelleme:</strong> <?php echo esc_html( get_the_modified_date( 'd.m.Y' ) ); ?></p>
					</div>
				</div>
				<?php
			}
		endwhile;
		?>
	</div>
</section>

<div class="cerez-back">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cerez-back-btn">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
		Ana Sayfaya Dön
	</a>
</div>

</div>
