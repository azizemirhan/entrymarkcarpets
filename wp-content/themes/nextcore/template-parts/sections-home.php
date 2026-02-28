<?php
/**
 * Anasayfa bölümleri — Hero, Özellikler şeridi, CTA (Next Content’ten yönetilir)
 *
 * @package nextcore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_get = function( $k, $d = '' ) {
	return function_exists( 'ece_home_get' ) ? ece_home_get( $k, $d ) : get_option( 'eternal_home_' . $k, $d );
};
$section_on = function( $prefix, $key ) {
	return function_exists( 'ece_section_active' ) ? ece_section_active( $prefix, $key ) : ( get_option( 'eternal_' . $prefix . '_' . $key . '_status', '1' ) === '1' );
};

$customizer_url = $home_get( 'hero_cta_primary_url', '' );
if ( $customizer_url === '' ) {
	$customizer_url = home_url( '/ozellestir/' );
}
$cta_btn_url = $home_get( 'cta_btn_url', '' );
if ( $cta_btn_url === '' ) {
	$cta_btn_url = home_url( '/ozellestir/' );
}
$cta_secondary_url = $home_get( 'hero_cta_secondary_url', '#how-it-works' );
$cta_sample_url = $home_get( 'cta_sample_url', '#' );

$hero_bg_type   = $home_get( 'hero_bg_type', 'default' );
$hero_bg_image  = $home_get( 'hero_bg_image', '' );
$hero_bg_video  = $home_get( 'hero_bg_video', '' );
$hero_bg_overlay = str_replace( ',', '.', $home_get( 'hero_bg_overlay', '0.45' ) );
$hero_has_media = ( $hero_bg_type === 'image' && $hero_bg_image ) || ( $hero_bg_type === 'video' && $hero_bg_video );

// Hero ön izlemede logo: önce tema logosu (logo-footer.png), yoksa Next Content header logosu
$theme_logo_path = get_template_directory() . '/assets/img/logo-footer.png';
$site_logo_url  = file_exists( $theme_logo_path )
	? get_template_directory_uri() . '/assets/img/logo-footer.png'
	: get_option( 'eternal_general_header_logo', '' );

// Eklenti dokuları — ilk doku ön izlemede kullanılır
$emc_textures_raw = get_option( 'emc_textures', array() );
$hero_texture_url = '';
if ( is_array( $emc_textures_raw ) && ! empty( $emc_textures_raw ) ) {
	$first = reset( $emc_textures_raw );
	$img_id = isset( $first['image_id'] ) ? (int) $first['image_id'] : 0;
	if ( $img_id > 0 ) {
		$hero_texture_url = wp_get_attachment_image_url( $img_id, 'medium' );
	}
	if ( empty( $hero_texture_url ) && ! empty( $first['image_url'] ) ) {
		$hero_texture_url = function_exists( 'nextcore_fix_image_url' ) ? nextcore_fix_image_url( $first['image_url'] ) : $first['image_url'];
	}
}
?>

<?php if ( $section_on( 'home', 'hero' ) ) : ?>
<section class="hero">

	<div class="hero-content <?php echo $hero_has_media ? ' hero-content--has-media hero-content--' . esc_attr( $hero_bg_type ) : ''; ?>"
		<?php if ( $hero_bg_type === 'image' && $hero_bg_image ) : ?>
			style="--hero-bg-image: url(<?php echo esc_url( function_exists( 'nextcore_fix_image_url' ) ? nextcore_fix_image_url( $hero_bg_image ) : $hero_bg_image ); ?>); --hero-overlay: <?php echo esc_attr( $hero_bg_overlay ); ?>;"
		<?php elseif ( $hero_bg_type === 'video' && $hero_bg_video ) : ?>
			style="--hero-overlay: <?php echo esc_attr( $hero_bg_overlay ); ?>;"
		<?php endif; ?>>
		<?php if ( $hero_bg_type === 'video' && $hero_bg_video ) : ?>
			<div class="hero-content-bg-media">
				<video class="hero-content-bg-video" autoplay muted loop playsinline aria-hidden="true">
					<source src="<?php echo esc_url( $hero_bg_video ); ?>" type="video/mp4">
				</video>
			</div>
		<?php endif; ?>
		<?php if ( $hero_has_media ) : ?>
			<span class="hero-content-overlay" role="presentation"></span>
		<?php endif; ?>
		<div class="hero-content-inner">
		<div class="hero-eyebrow">
			<span class="hero-eyebrow-line"></span>
			<span class="hero-eyebrow-text"><?php echo esc_html( $home_get( 'hero_eyebrow', 'Custom Carpet Designer' ) ); ?></span>
		</div>

		<h1 class="hero-heading">
			<?php echo esc_html( $home_get( 'hero_heading_1', 'Design Your' ) ); ?>
			<span class="line-break"><?php echo esc_html( $home_get( 'hero_heading_2', 'Perfect Carpet' ) ); ?></span>
			<span class="line-break"><?php echo esc_html( $home_get( 'hero_heading_3', 'in Minutes' ) ); ?></span>
		</h1>

		<p class="hero-subtext">
			<?php echo wp_kses_post( nl2br( esc_html( $home_get( 'hero_subtext', 'Choose from 24+ premium colors, select your size, upload your logo, and watch your custom carpet come to life — all in our interactive design studio.' ) ) ) ); ?>
		</p>

		<div class="hero-cta-group">
			<a href="<?php echo esc_url( $customizer_url ); ?>" class="hero-cta-primary">
				<?php echo esc_html( $home_get( 'hero_cta_primary_text', 'Start Designing' ) ); ?>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
			</a>
			<a href="<?php echo esc_url( $cta_secondary_url ); ?>" class="hero-cta-secondary">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3" fill="rgba(255,255,255,0.1)"/></svg>
				<?php echo esc_html( $home_get( 'hero_cta_secondary_text', 'See How It Works' ) ); ?>
			</a>
		</div>

		<div class="hero-stats">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
			<div class="hero-stat">
				<div class="hero-stat-number"><?php echo esc_html( $home_get( 'hero_stat' . $i . '_number', [ '500+', '40+', '30yrs', '24h' ][ $i - 1 ] ) ); ?></div>
				<div class="hero-stat-label"><?php echo esc_html( $home_get( 'hero_stat' . $i . '_label', [ 'Hotels Worldwide', 'Countries Delivered', 'Of Excellence', 'Design Approval' ][ $i - 1 ] ) ); ?></div>
			</div>
			<?php endfor; ?>
		</div>
		</div><!-- .hero-content-inner -->
	</div>

	<div class="hero-visual">
		<div class="hero-visual-bg"></div>
		<div class="geo-ring"></div>
		<div class="geo-ring"></div>
		<div class="geo-ring"></div>

		<div class="float-card float-card-1">
			<div class="float-card-inner" style="background:#2E5A1C;">
				<div class="fc-texture"></div>
				<div class="float-card-tag">Forest Green</div>
			</div>
		</div>
		<div class="float-card float-card-2">
			<div class="float-card-inner" style="background:#C4A265;">
				<div class="fc-texture"></div>
				<div class="float-card-tag">Golden</div>
			</div>
		</div>

		<div class="showcase">
			<div class="showcase-main" id="showcaseCard">
				<div class="showcase-main-inner<?php echo $hero_texture_url ? ' showcase-main-inner--has-texture' : ''; ?>" id="showcaseInner"<?php echo $hero_texture_url ? ' style="--showcase-texture: url(' . esc_url( function_exists( 'nextcore_fix_image_url' ) ? nextcore_fix_image_url( $hero_texture_url ) : $hero_texture_url ) . ');"' : ''; ?>>
					<div class="carpet-texture"></div>
					<div class="carpet-frame"></div>
					<div class="carpet-logo-placeholder">
						<?php if ( ! empty( $site_logo_url ) ) : ?>
							<img src="<?php echo esc_url( function_exists( 'nextcore_fix_image_url' ) ? nextcore_fix_image_url( $site_logo_url ) : $site_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="carpet-logo-img">
						<?php else : ?>
							<div class="carpet-logo-icon">
								<svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
							</div>
							<div class="carpet-logo-text">Your Logo</div>
						<?php endif; ?>
					</div>
					<div class="carpet-bottom-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
					<div class="showcase-size-tag">40 × 70 cm</div>
				</div>
			</div>

			<div class="color-preview-bar" id="colorBar">
				<div class="color-dot active" data-color="#1a1a1e" data-name="Siyah" style="background:#1a1a1e;"></div>
				<div class="color-dot" data-color="#1B4F8A" data-name="Lacivert" style="background:#1B4F8A;"></div>
				<div class="color-dot" data-color="#800020" data-name="Bordo" style="background:#800020;"></div>
				<div class="color-dot" data-color="#2E5A1C" data-name="Yeşil" style="background:#2E5A1C;"></div>
				<div class="color-dot" data-color="#C4A265" data-name="Altın" style="background:#C4A265;"></div>
				<div class="color-dot" data-color="#4a4a4a" data-name="Antrasit" style="background:#4a4a4a;"></div>
				<span class="color-label" id="colorLabel">Siyah</span>
			</div>
		</div>
	</div>

</section>
<?php endif; ?>

<?php if ( $section_on( 'home', 'features' ) ) : ?>
<div class="features-ribbon">
	<div class="features-ribbon-inner">
		<?php
		$feature_icons = [
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>',
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
		];
		for ( $i = 1; $i <= 4; $i++ ) :
			?>
		<div class="feature-item">
			<div class="feature-icon">
				<?php echo $feature_icons[ $i - 1 ]; ?>
			</div>
			<div class="feature-text">
				<strong><?php echo esc_html( $home_get( 'features_title_' . $i, [ 'Premium Quality', 'Global Shipping', 'Express Production', 'Designer Support' ][ $i - 1 ] ) ); ?></strong>
				<span><?php echo esc_html( $home_get( 'features_desc_' . $i, [ 'Certified materials only', '40+ countries worldwide', '72-hour rush available', 'Free design consultation' ][ $i - 1 ] ) ); ?></span>
			</div>
		</div>
		<?php endfor; ?>
	</div>
</div>
<?php endif; ?>

<?php if ( $section_on( 'home', 'cta' ) ) : ?>
<section class="cta-section" id="how-it-works">
	<div class="cta-grid">

		<div class="cta-visual">
			<div class="step-cards">
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<div class="step-card">
					<span class="step-card-number"><?php echo sprintf( '%02d', $i ); ?></span>
					<div class="step-card-icon">
						<?php if ( $i === 1 ) : ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="13.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="10.5" r="2.5"/><circle cx="8.5" cy="7.5" r="2.5"/><circle cx="6.5" cy="12" r="2.5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>
						<?php elseif ( $i === 2 ) : ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 3v18"/></svg>
						<?php elseif ( $i === 3 ) : ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
						<?php else : ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
						<?php endif; ?>
					</div>
					<div class="step-card-title"><?php echo esc_html( $home_get( 'cta_step' . $i . '_title', [ 'Renk Seçin', 'Ölçü Belirleyin', 'Logo & Yazı Ekleyin', 'Sipariş Verin' ][ $i - 1 ] ) ); ?></div>
					<div class="step-card-desc"><?php echo esc_html( $home_get( 'cta_step' . $i . '_desc', [ '24+ premium renk seçeneğinden istediğinizi belirleyin', 'Standart veya özel ölçü ile mükemmel boyutu seçin', 'Logonuzu yükleyin veya metin ekleyerek kişiselleştirin', 'Tasarımınızı onaylayın ve kapınıza teslim edelim' ][ $i - 1 ] ) ); ?></div>
				</div>
				<?php endfor; ?>
			</div>
		</div>

		<div class="cta-text">
			<div class="cta-eyebrow">
				<span class="cta-eyebrow-dot"></span>
				<span class="cta-eyebrow-label"><?php echo esc_html( $home_get( 'cta_eyebrow', 'Nasıl Çalışır?' ) ); ?></span>
			</div>

			<h2 class="cta-heading">
				<?php echo wp_kses( str_replace( [ "\n", "\r" ], '<br>', esc_html( $home_get( 'cta_heading', 'Hayalinizdeki Paspası 4 Adımda Oluşturun' ) ) ), [ 'br' => [] ] ); ?>
			</h2>

			<p class="cta-desc">
				<?php echo wp_kses_post( nl2br( esc_html( $home_get( 'cta_desc', "Online tasarım stüdyomuz ile logonuzu, renginizi ve ölçünüzü seçin. Tasarımcı ekibimiz üretim öncesi onayınız için size özel bir ön izleme sunacaktır." ) ) ) ); ?>
			</p>

			<ul class="cta-features-list">
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<li>
					<span class="check-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></span>
					<?php echo esc_html( $home_get( 'cta_list_' . $i, 'Anlık canlı önizleme ile tasarımınızı görün' ) ); ?>
				</li>
				<?php endfor; ?>
			</ul>

			<div class="cta-btn-group">
				<a href="<?php echo esc_url( $cta_btn_url ); ?>" class="cta-main-btn">
					<?php echo esc_html( $home_get( 'cta_btn_text', 'Tasarlamaya Başla' ) ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
				</a>
				<a href="<?php echo esc_url( $cta_sample_url ); ?>" class="cta-sample-btn">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
					<?php echo esc_html( $home_get( 'cta_sample_text', 'Numune İste' ) ); ?>
				</a>
			</div>
		</div>

	</div>
</section>
<?php endif; ?>
