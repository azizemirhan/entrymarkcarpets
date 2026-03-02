<?php
/**
 * Sürdürülebilirlik sayfası — sitenin diğer tasarımlarına uyumlu
 *
 * @package nextcore
 */

$sur_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_sur_' . $key, $default );
};
$eyebrow  = $sur_get( 'eyebrow', 'Taahhüdümüz' );
$subtitle = $sur_get( 'subtitle', 'Çevreye ve geleceğe saygıyla, sürdürülebilir üretim ilkelerimizi paylaşıyoruz.' );

$pillars = array(
	array(
		'icon'   => 'leaf',
		'title'  => 'Çevresel Sorumluluk',
		'desc'   => 'Üretim süreçlerimizde çevresel etkiyi minimize ediyoruz. Atık yönetimi, su tasarrufu ve karbon ayak izini azaltma hedeflerimizle ilerliyoruz.',
	),
	array(
		'icon'   => 'material',
		'title'  => 'Sürdürülebilir Malzemeler',
		'desc'   => 'Geri dönüştürülebilir ve yenilenebilir malzemeleri tercih ediyoruz. Doğal elyaflar ve çevre dostu bileşenlerle kaliteli ürünler sunuyoruz.',
	),
	array(
		'icon'   => 'energy',
		'title'  => 'Enerji Verimliliği',
		'desc'   => 'Üretim tesislerimizde enerji verimliliğini artırmak için sürekli iyileştirmeler yapıyoruz. Yenilenebilir enerji kullanımı hedeflerimiz mevcuttur.',
	),
	array(
		'icon'   => 'recycle',
		'title'  => 'Geri Dönüşüm',
		'desc'   => 'Atık malzemelerin geri kazanımı ve geri dönüşüm programlarıyla döngüsel ekonomiye katkı sağlıyoruz. Üretim artıklarının değerlendirilmesine önem veriyoruz.',
	),
	array(
		'icon'   => 'heart',
		'title'  => 'Sosyal Sorumluluk',
		'desc'   => 'Çalışanlarımızın hakları, adil çalışma koşulları ve yerel topluluklarla iş birliği sürdürülebilirlik anlayışımızın temel parçalarıdır.',
	),
	array(
		'icon'   => 'factory',
		'title'  => 'Sorumlu Üretim',
		'desc'   => 'Üretimde yeşil standartlara uyum sağlıyoruz. Kalite kontrol süreçlerimizde çevresel kriterleri de gözetiyoruz.',
	),
);
?>
<div class="surdurulebilirlik-page">
<section class="sur-hero">
	<div class="sur-hero-glow1"></div>
	<div class="sur-hero-glow2"></div>
	<div class="sur-hero-content">
		<div class="sur-hero-eyebrow"><span></span><span class="sur-hero-eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span><span></span></div>
		<h1 class="sur-hero-title"><em>Sürdürülebilirlik</em></h1>
		<p class="sur-hero-desc"><?php echo esc_html( $subtitle ); ?></p>
	</div>
</section>
<section class="sur-content">
	<div class="sur-content-inner">
		<?php
		while ( have_posts() ) :
			the_post();
			$content = get_the_content();
			if ( ! empty( trim( $content ) ) ) {
				?><div class="sur-body entry-content"><?php the_content(); ?></div><?php
			} else {
				?>
				<div class="sur-intro">
					<p><strong>Entry Mark Carpets</strong> olarak, çevreye ve topluma karşı sorumluluğumuzun farkındayız. Sürdürülebilirlik, iş yapış şeklimizin merkezinde yer alıyor. Aşağıda bu alandaki taahhütlerimizi ve uygulamalarımızı özetliyoruz.</p>
				</div>
				<div class="sur-grid">
					<?php foreach ( $pillars as $p ) : ?>
					<div class="sur-card">
						<div class="sur-card-icon">
							<?php
							if ( 'leaf' === $p['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.5 18 4c0 0 0 0 0 0c0 0 0 0 0 0c0 1.5.5 3 1.5 4.5 1 1.5 1.5 2.5 1.5 4.5C21 16 21 20 11 20z"/><path d="M2 21c0-9 1.5-14 9-14"/></svg><?php
							elseif ( 'material' === $p['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/></svg><?php
							elseif ( 'energy' === $p['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg><?php
							elseif ( 'recycle' === $p['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 3l4 4-4 4"/><path d="M20 7H8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9"/><path d="M8 21l-4-4 4-4"/><path d="M4 17h12a2 2 0 0 0 2-2V7"/></svg><?php
							elseif ( 'heart' === $p['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg><?php
							else :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M17 18h1"/><path d="M12 18h1"/></svg><?php
							endif;
							?>
						</div>
						<h3 class="sur-card-title"><?php echo esc_html( $p['title'] ); ?></h3>
						<p class="sur-card-desc"><?php echo esc_html( $p['desc'] ); ?></p>
					</div>
					<?php endforeach; ?>
				</div>
				<?php
			}
		endwhile;
		?>
	</div>
</section>
<div class="sur-back">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="sur-back-btn">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
		Ana Sayfaya Dön
	</a>
</div>
</div>
