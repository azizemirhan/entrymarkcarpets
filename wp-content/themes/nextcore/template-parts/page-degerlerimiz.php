<?php
/**
 * Değerlerimiz sayfası — sitenin diğer tasarımlarına uyumlu, değer kartları
 *
 * @package nextcore
 */

$deger_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_deger_' . $key, $default );
};
$eyebrow  = $deger_get( 'eyebrow', 'Hakkımızda' );
$subtitle = $deger_get( 'subtitle', 'Entry Mark Carpets olarak bizi tanımlayan temel değerlerimiz.' );

$values = array(
	array(
		'icon'   => 'star',
		'title'  => 'Kalite',
		'desc'   => 'Her üründe en yüksek kalite standartlarını hedefliyoruz. Premium malzemeler ve titiz el işçiliği ile dayanıklı, estetik çözümler sunuyoruz.',
	),
	array(
		'icon'   => 'users',
		'title'  => 'Müşteri Odaklılık',
		'desc'   => 'Müşteri memnuniyeti önceliğimizdir. Özel tasarım taleplerinizi dinliyor, beklentilerinizi karşılayacak çözümler üretiyoruz.',
	),
	array(
		'icon'   => 'lightbulb',
		'title'  => 'Yenilik',
		'desc'   => 'Teknoloji ve tasarımı bir araya getirerek yenilikçi ürünler geliştiriyoruz. Sektörde öncü çözümlerle fark yaratıyoruz.',
	),
	array(
		'icon'   => 'shield',
		'title'  => 'Güvenilirlik',
		'desc'   => 'Sözümüzde duruyoruz. Teslimat sürelerine uyum, şeffaf iletişim ve dürüst fiyatlandırma ile uzun vadeli güven inşa ediyoruz.',
	),
	array(
		'icon'   => 'leaf',
		'title'  => 'Sürdürülebilirlik',
		'desc'   => 'Çevreye saygılı üretim süreçleri ve geri dönüştürülebilir malzemelerle geleceğe yatırım yapıyoruz.',
	),
	array(
		'icon'   => 'craft',
		'title'  => 'Zanaat',
		'desc'   => 'El işçiliği ve geleneksel ustalık, her ürünümüzün temelidir. Detaylara verdiğimiz özen bizi farklı kılar.',
	),
);
?>
<div class="degerlerimiz-page">
<section class="degerlerimiz-hero">
	<div class="degerlerimiz-hero-glow1"></div>
	<div class="degerlerimiz-hero-glow2"></div>
	<div class="degerlerimiz-hero-content">
		<div class="degerlerimiz-hero-eyebrow"><span></span><span class="degerlerimiz-hero-eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span><span></span></div>
		<h1 class="degerlerimiz-hero-title"><em>Değerlerimiz</em></h1>
		<p class="degerlerimiz-hero-desc"><?php echo esc_html( $subtitle ); ?></p>
	</div>
</section>
<section class="degerlerimiz-content">
	<div class="degerlerimiz-content-inner">
		<?php
		while ( have_posts() ) :
			the_post();
			$content = get_the_content();
			if ( ! empty( trim( $content ) ) ) {
				?><div class="degerlerimiz-body entry-content"><?php the_content(); ?></div><?php
			} else {
				?>
				<div class="degerlerimiz-intro">
					<p><strong>Entry Mark Carpets</strong> olarak, her projemizde bizi yönlendiren temel ilkelerimiz vardır. Bu değerler, kaliteli ürünler ve mutlu müşteriler için çalışmamızın temelini oluşturur.</p>
				</div>
				<div class="degerlerimiz-grid">
					<?php foreach ( $values as $v ) : ?>
					<div class="deger-card">
						<div class="deger-card-icon">
							<?php
							if ( 'star' === $v['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3 7h7l-5.5 5 2 7-6.5-4.5-6.5 4.5 2-7-5.5-5h7z"/></svg><?php
							elseif ( 'users' === $v['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg><?php
							elseif ( 'lightbulb' === $v['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15 8a3 3 0 0 0-6 0c0 1.5.5 2.5 1 3.5.5 1 .5 2 .5 3h3c0-1 .5-2 .5-3 .5-1 1-2 1-3.5z"/><path d="M12 2v1"/><path d="M12 2a4 4 0 0 1 4 4c0 1.5-.5 2.5-1 3.5-.5 1-.5 2-.5 3H10c0-1-.5-2-.5-3-.5-1-1-2-1-3.5a4 4 0 0 1 4-4z"/></svg><?php
							elseif ( 'shield' === $v['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg><?php
							elseif ( 'leaf' === $v['icon'] ) :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.5 18 4c0 0 0 0 0 0c0 0 0 0 0 0c0 1.5.5 3 1.5 4.5 1 1.5 1.5 2.5 1.5 4.5C21 16 21 20 11 20z"/><path d="M2 21c0-9 1.5-14 9-14"/></svg><?php
							else :
								?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg><?php
							endif;
							?>
						</div>
						<h3 class="deger-card-title"><?php echo esc_html( $v['title'] ); ?></h3>
						<p class="deger-card-desc"><?php echo esc_html( $v['desc'] ); ?></p>
					</div>
					<?php endforeach; ?>
				</div>
				<?php
			}
		endwhile;
		?>
	</div>
</section>
<div class="degerlerimiz-back">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="degerlerimiz-back-btn">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
		Ana Sayfaya Dön
	</a>
</div>
</div>
