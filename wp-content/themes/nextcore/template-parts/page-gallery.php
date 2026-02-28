<?php
/**
 * Galeri sayfası içeriği (tasarım: galery.html)
 * Grid ve lightbox JS ile doldurulur.
 *
 * @package nextcore
 */

$gallery_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_gallery_' . $key, $default );
};
$eyebrow   = $gallery_get( 'eyebrow', 'Koleksiyonlarımız' );
$title     = $gallery_get( 'title', 'İlham Veren<br><em>Tasarımlar</em>' );
$subtitle  = $gallery_get( 'subtitle', 'Otellerden kurumsal ofislere, restoranlardan konutlara — her mekâna özel üretilen premium halı ve paspas koleksiyonlarımızı keşfedin.' );
$customizer_url = $gallery_get( 'customizer_url', '' );
if ( empty( $customizer_url ) ) {
	$customizer_url = home_url( '/paspas-ozellestir/' );
}
?>

<div class="gallery-page">

<section class="gallery-section">

  <div class="gallery-header">
    <div class="gallery-header-left">
      <div class="gallery-eyebrow">
        <span class="gallery-eyebrow-dot"></span>
        <span class="gallery-eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span>
      </div>
      <h2 class="gallery-title"><?php echo wp_kses_post( $title ); ?></h2>
      <p class="gallery-subtitle"><?php echo esc_html( $subtitle ); ?></p>
    </div>

    <div class="gallery-filters" id="filterBar">
      <button type="button" class="filter-btn active" data-filter="all">Tümü<span class="filter-count">12</span></button>
      <button type="button" class="filter-btn" data-filter="hotel">Otel<span class="filter-count">3</span></button>
      <button type="button" class="filter-btn" data-filter="office">Ofis<span class="filter-count">3</span></button>
      <button type="button" class="filter-btn" data-filter="custom">Özel Tasarım<span class="filter-count">3</span></button>
      <button type="button" class="filter-btn" data-filter="residential">Konut<span class="filter-count">3</span></button>
    </div>
  </div>

  <div class="gallery-grid" id="galleryGrid"></div>

  <div class="gallery-load-more">
    <button type="button" class="load-more-btn" id="loadMoreBtn">
      <span>Daha Fazla Göster</span>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
    </button>
    <div class="gallery-counter"><strong id="shownCount">8</strong> / <span id="totalCount">12</span> ürün gösteriliyor</div>
  </div>

</section>

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
      <div class="lightbox-tag" id="lbTag">KOLEKSİYON</div>
      <h3 class="lightbox-title" id="lbTitle">Ürün Adı</h3>
      <div class="lightbox-collection" id="lbCollection">Koleksiyon</div>
      <p class="lightbox-desc" id="lbDesc">Açıklama metni</p>

      <div class="lightbox-specs">
        <div class="lightbox-spec">
          <div class="lightbox-spec-label">Malzeme</div>
          <div class="lightbox-spec-value" id="lbMaterial">Yün Karışım</div>
        </div>
        <div class="lightbox-spec">
          <div class="lightbox-spec-label">Boyut</div>
          <div class="lightbox-spec-value" id="lbSize">80 × 120 cm</div>
        </div>
        <div class="lightbox-spec">
          <div class="lightbox-spec-label">Kalınlık</div>
          <div class="lightbox-spec-value" id="lbThickness">12mm</div>
        </div>
        <div class="lightbox-spec">
          <div class="lightbox-spec-label">Fiyat</div>
          <div class="lightbox-spec-value" id="lbPrice">₺890</div>
        </div>
      </div>

      <div class="lightbox-colors" id="lbColors"></div>

      <div class="lightbox-cta">
        <a href="<?php echo esc_url( $customizer_url ); ?>" class="lightbox-btn-primary" id="lbCustomizeBtn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Özelleştir
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

</div>
