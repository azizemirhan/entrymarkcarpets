<?php
/**
 * Galeri sayfası içeriği — Next Content (Galeri) ile yönetilir; tüm bölümler entegre.
 *
 * @package nextcore
 */

if ( ! function_exists( 'ece_gallery_get' ) ) {
	function ece_gallery_get( $key, $default = '' ) {
		return get_option( 'eternal_gallery_' . $key, $default );
	}
}

// Eski ayar adlarıyla uyumluluk (eyebrow, title, subtitle, customizer_url)
$customizer_url = ece_gallery_get( 'hero_customizer_url', ece_gallery_get( 'customizer_url', '' ) );
if ( empty( $customizer_url ) ) {
	$customizer_url = home_url( '/paspas-ozellestir/' );
}

// Next Content ile bölüm aç/kapa
$show_hero    = function_exists( 'ece_section_active' ) ? ece_section_active( 'gallery', 'hero' ) : true;
$show_filters = function_exists( 'ece_section_active' ) ? ece_section_active( 'gallery', 'filters' ) : true;
$show_grid    = function_exists( 'ece_section_active' ) ? ece_section_active( 'gallery', 'grid' ) : true;
$show_lightbox = function_exists( 'ece_section_active' ) ? ece_section_active( 'gallery', 'lightbox' ) : true;

// Filtre listesi: her satır "Etiket|slug"
$filters_raw = ece_gallery_get( 'filters_items', "Tümü|all\nOtel|hotel\nOfis|office\nÖzel Tasarım|custom\nKonut|residential" );
$filter_rows = array_filter( array_map( 'trim', explode( "\n", $filters_raw ) ) );
$filters     = array();
foreach ( $filter_rows as $row ) {
	$parts = array_map( 'trim', explode( '|', $row, 2 ) );
	if ( count( $parts ) >= 2 ) {
		$filters[] = array( 'label' => $parts[0], 'slug' => $parts[1] );
	}
}
if ( empty( $filters ) ) {
	$filters = array(
		array( 'label' => 'Tümü', 'slug' => 'all' ),
		array( 'label' => 'Otel', 'slug' => 'hotel' ),
		array( 'label' => 'Ofis', 'slug' => 'office' ),
		array( 'label' => 'Özel Tasarım', 'slug' => 'custom' ),
		array( 'label' => 'Konut', 'slug' => 'residential' ),
	);
}
?>

<div class="gallery-page">

<?php if ( $show_hero || $show_filters ) : ?>
<section class="gallery-section gallery-section--header">
  <div class="gallery-header">
    <?php if ( $show_hero ) : ?>
    <div class="gallery-header-left">
      <div class="gallery-eyebrow">
        <span class="gallery-eyebrow-dot"></span>
        <span class="gallery-eyebrow-text"><?php echo esc_html( ece_gallery_get( 'hero_eyebrow', ece_gallery_get( 'eyebrow', 'Koleksiyonlarımız' ) ) ); ?></span>
      </div>
      <h2 class="gallery-title"><?php echo wp_kses_post( ece_gallery_get( 'hero_heading', ece_gallery_get( 'title', "İlham Veren<br><em>Tasarımlar</em>" ) ) ); ?></h2>
      <p class="gallery-subtitle"><?php echo esc_html( ece_gallery_get( 'hero_subtitle', ece_gallery_get( 'subtitle', 'Otellerden kurumsal ofislere, restoranlardan konutlara — her mekâna özel üretilen premium halı ve paspas koleksiyonlarımızı keşfedin.' ) ) ); ?></p>
    </div>
    <?php endif; ?>

    <?php if ( $show_filters ) : ?>
    <div class="gallery-filters" id="filterBar">
      <?php
      foreach ( $filters as $i => $f ) {
        $active = ( 0 === $i ) ? ' active' : '';
        $slug   = esc_attr( $f['slug'] );
        $label  = esc_html( $f['label'] );
        echo '<button type="button" class="filter-btn' . $active . '" data-filter="' . $slug . '">' . $label . '<span class="filter-count">0</span></button>';
      }
      ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ( $show_grid ) : ?>
<section class="gallery-section gallery-section--grid">
  <div class="gallery-grid" id="galleryGrid"></div>
  <div class="gallery-load-more">
    <button type="button" class="load-more-btn" id="loadMoreBtn">
      <span><?php echo esc_html( ece_gallery_get( 'grid_load_more_text', 'Daha Fazla Göster' ) ); ?></span>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
    </button>
    <div class="gallery-counter" id="galleryCounterWrap" data-counter-format="<?php echo esc_attr( ece_gallery_get( 'grid_counter_text', '%shown% / %total% ürün gösteriliyor' ) ); ?>"><span id="galleryCounterText">0 / 0 ürün gösteriliyor</span></div>
  </div>
</section>
<?php endif; ?>

<?php if ( $show_lightbox ) : ?>
<div class="lightbox" id="lightbox" aria-hidden="true">
  <button type="button" class="lightbox-close" id="lightboxClose" aria-label="Kapat">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
  </button>

  <div class="lightbox-inner">
    <div class="lightbox-visual">
      <button type="button" class="lightbox-nav prev" id="lbPrev" aria-label="Önceki"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></button>
      <div class="lightbox-carpet" id="lbCarpet">
        <div class="carpet-preview-texture"></div>
        <div class="carpet-preview-frame"></div>
        <div class="carpet-preview-logo"><svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
      </div>
      <button type="button" class="lightbox-nav next" id="lbNext" aria-label="Sonraki"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></button>
    </div>

    <div class="lightbox-details">
      <div class="lightbox-tag" id="lbTag"><?php echo esc_html( ece_gallery_get( 'lightbox_tag_default', 'KOLEKSİYON' ) ); ?></div>
      <h3 class="lightbox-title" id="lbTitle">Ürün Adı</h3>
      <div class="lightbox-collection" id="lbCollection">Koleksiyon</div>
      <p class="lightbox-desc" id="lbDesc">Açıklama metni</p>

      <div class="lightbox-specs">
        <div class="lightbox-spec">
          <div class="lightbox-spec-label"><?php echo esc_html( ece_gallery_get( 'lightbox_label_material', 'Malzeme' ) ); ?></div>
          <div class="lightbox-spec-value" id="lbMaterial">—</div>
        </div>
        <div class="lightbox-spec">
          <div class="lightbox-spec-label"><?php echo esc_html( ece_gallery_get( 'lightbox_label_size', 'Boyut' ) ); ?></div>
          <div class="lightbox-spec-value" id="lbSize">—</div>
        </div>
        <div class="lightbox-spec">
          <div class="lightbox-spec-label"><?php echo esc_html( ece_gallery_get( 'lightbox_label_thickness', 'Kalınlık' ) ); ?></div>
          <div class="lightbox-spec-value" id="lbThickness">—</div>
        </div>
        <div class="lightbox-spec">
          <div class="lightbox-spec-label"><?php echo esc_html( ece_gallery_get( 'lightbox_label_price', 'Fiyat' ) ); ?></div>
          <div class="lightbox-spec-value" id="lbPrice">—</div>
        </div>
      </div>

      <div class="lightbox-colors" id="lbColors"></div>

      <div class="lightbox-cta">
        <a href="<?php echo esc_url( $customizer_url ); ?>" class="lightbox-btn-primary" id="lbCustomizeBtn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          <?php echo esc_html( ece_gallery_get( 'lightbox_btn_customize', 'Özelleştir' ) ); ?>
        </a>
        <button type="button" class="lightbox-btn-icon" title="Favorilere ekle" id="lbFav">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>
        <button type="button" class="lightbox-btn-icon" title="Paylaş" id="lbShare">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        </button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

</div>
