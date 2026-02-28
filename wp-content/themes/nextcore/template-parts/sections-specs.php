<?php
/**
 * Anasayfa — Teknik Özellikler bölümü (sections2.html)
 * Next Content → Anasayfa → Teknik Özellikler ile aç/kapatılır.
 *
 * @package nextcore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_on = function( $prefix, $key ) {
	return function_exists( 'ece_section_active' ) ? ece_section_active( $prefix, $key ) : ( get_option( 'eternal_' . $prefix . '_' . $key . '_status', '1' ) === '1' );
};

if ( ! $section_on( 'home', 'specs' ) ) {
	return;
}

$home_get = function( $k, $d = '' ) {
	return function_exists( 'ece_home_get' ) ? ece_home_get( $k, $d ) : get_option( 'eternal_home_' . $k, $d );
};
$specs_eyebrow  = $home_get( 'specs_eyebrow', 'Teknik Detaylar' );
$specs_title    = $home_get( 'specs_title', 'Teknik Özellikler' );
$specs_subtitle = $home_get( 'specs_subtitle', 'ECONYL® sertifikalı malzemeler ve CNC lazer teknolojisi ile üretilen premium paspaslarımızın tüm teknik detaylarını keşfedin.' );
?>

<section class="specs-section" id="teknik-ozellikler">

  <div class="specs-deco-grid"></div>
  <div class="specs-deco-circle"></div>

  <div class="specs-header reveal">
    <div class="specs-eyebrow">
      <span class="specs-eyebrow-line"></span>
      <span class="specs-eyebrow-text"><?php echo esc_html( $specs_eyebrow ); ?></span>
      <span class="specs-eyebrow-line"></span>
    </div>
    <h2 class="specs-title"><?php echo wp_kses_post( $specs_title ); ?></h2>
    <p class="specs-subtitle"><?php echo esc_html( $specs_subtitle ); ?></p>
  </div>

  <div class="specs-counters reveal">
    <div class="counter-card">
      <div class="counter-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20.38 3.46L16 2 12 3.46 8 2 3.62 3.46a2 2 0 0 0-1.34 1.89v13.1a2 2 0 0 0 2.66 1.89L8 19l4-1.46L16 19l4.38-1.54a2 2 0 0 0 1.34-1.89V5.35a2 2 0 0 0-1.34-1.89z"/><path d="M12 22V8"/></svg>
      </div>
      <div class="counter-value"><span class="count" data-target="1900">0</span><span class="counter-unit">gr/m²</span></div>
      <div class="counter-label">Toplam Ağırlık</div>
    </div>
    <div class="counter-card">
      <div class="counter-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 3H3v18h18V3z"/><path d="M21 9H3"/><path d="M21 15H3"/></svg>
      </div>
      <div class="counter-value"><span class="count" data-target="10">0</span><span class="counter-unit">mm</span></div>
      <div class="counter-label">Kalınlık</div>
    </div>
    <div class="counter-card">
      <div class="counter-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <div class="counter-value"><span class="count" data-target="100">0</span><span class="counter-unit">%</span></div>
      <div class="counter-label">ECONYL® Poliamid</div>
    </div>
    <div class="counter-card">
      <div class="counter-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <div class="counter-value">Ev <span class="count" data-target="23">0</span></div>
      <div class="counter-label">Kullanım Sınıfı</div>
    </div>
  </div>

  <div class="specs-main">
    <div class="spec-panel reveal">
      <div class="spec-panel-header">
        <div class="spec-panel-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <div>
          <div class="spec-panel-title">Üretim & Malzeme</div>
          <div class="spec-panel-subtitle">Teknik spesifikasyonlar</div>
        </div>
      </div>
      <div class="spec-list">
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>CNC lazer teknolojisiyle</strong> üretilmiş yüksek kaliteli paspas</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>Kauçuk kenar</strong> ile güvenlik takviyeli yapı</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>NYLON & POLYAMID</strong> elyaflar · Kalınlık <span class="spec-highlight">10mm</span></div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>VINYL alt tabaka</strong> — iç ve dış mekân uygulamaları</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text">Dış malzeme: <strong>%100 ECONYL®</strong> markalı poliamid elyaf</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text">Arka yüzey: <strong>%100 nitril kauçuk</strong>, güvenlik kenarı takviyeli</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text">Toplam ağırlık: <strong>1900 gr/m²</strong> (+/- %15) <span class="spec-highlight">OIN 8543</span></div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text">Yanıcılık: <strong>Efl</strong> <span class="spec-highlight">EN 13501-1</span></div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text">Bakım: Halı için özel tasarlanmış temizleyici içeren <strong>sabunlu su ve bir fırça</strong> kullanın</div>
        </div>
      </div>
    </div>
    <div class="spec-panel reveal">
      <div class="spec-panel-header">
        <div class="spec-panel-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <div>
          <div class="spec-panel-title">Performans & Avantajlar</div>
          <div class="spec-panel-subtitle">Ürün özellikleri</div>
        </div>
      </div>
      <div class="spec-list">
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>Yüksek kir ve nem emme</strong> özelliği ile üstün hijyen</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>Takılma ve kayma riski</strong> minimuma indirilmiştir</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>Güvenlik kenarı</strong> güçlendirilmiş yapı</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>Kaymaz nitril kauçuk taban</strong> — maksimum stabilite</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>Zor alev alır</strong> — güvenlik standartlarına uygun <span class="spec-highlight gold">Efl Sınıfı</span></div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text">Yoğun alan kullanımı: <span class="spec-highlight">EN ISO 10874</span></div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>Üstün kalite ve hizmet garantisi</strong> ile müşteri memnuniyeti</div>
        </div>
        <div class="spec-item">
          <div class="spec-item-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="spec-item-text"><strong>Çok hızlı teslimat</strong> süreleri — Express 72 saat seçeneği</div>
        </div>
      </div>
    </div>
  </div>

  <div class="specs-detail-bar reveal">
    <div class="detail-item">
      <div class="detail-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <div>
        <div class="detail-label">Dış Malzeme</div>
        <div class="detail-value">%100 ECONYL®<span class="detail-sub">Poliamid elyaf</span></div>
      </div>
    </div>
    <div class="detail-item">
      <div class="detail-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>
      </div>
      <div>
        <div class="detail-label">Alt Tabaka</div>
        <div class="detail-value">%100 Nitril Kauçuk<span class="detail-sub">Güvenlik kenarı takviyeli</span></div>
      </div>
    </div>
    <div class="detail-item">
      <div class="detail-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M2 20h20"/><path d="M5 20V8l7-5 7 5v12"/><path d="M9 20v-6h6v6"/></svg>
      </div>
      <div>
        <div class="detail-label">Kullanım Sınıfı</div>
        <div class="detail-value">Ev 23, Yoğun Alanlar<span class="detail-sub">EN ISO 10874</span></div>
      </div>
    </div>
    <div class="detail-item">
      <div class="detail-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
      </div>
      <div>
        <div class="detail-label">Yanıcılık</div>
        <div class="detail-value">Efl Sınıfı<span class="detail-sub">EN 13501-1</span></div>
      </div>
    </div>
  </div>

  <div class="specs-certs reveal">
    <div class="certs-header">
      <h3 class="certs-title">Sertifikalar & Zemin Sembolleri</h3>
      <div class="certs-line"></div>
    </div>
    <div class="certs-grid">
      <div class="cert-card">
        <div class="cert-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        </div>
        <div class="cert-name">Yangın Dayanımı</div>
        <div class="cert-code">Efl — EN 13501-1</div>
      </div>
      <div class="cert-card">
        <div class="cert-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M2 20h20"/><path d="M5 20V8l7-5 7 5v12"/><path d="M9 20v-6h6v6"/></svg>
        </div>
        <div class="cert-name">Genel Kullanım</div>
        <div class="cert-code">Ev 23 — EN ISO 10874</div>
      </div>
      <div class="cert-card">
        <div class="cert-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20.38 3.46L16 2 12 3.46 8 2 3.62 3.46a2 2 0 0 0-1.34 1.89v13.1a2 2 0 0 0 2.66 1.89L8 19l4-1.46L16 19l4.38-1.54a2 2 0 0 0 1.34-1.89V5.35a2 2 0 0 0-1.34-1.89z"/></svg>
        </div>
        <div class="cert-name">Ağırlık Standardı</div>
        <div class="cert-code">1900 gr/m² — OIN 8543</div>
      </div>
      <div class="cert-card">
        <div class="cert-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <div class="cert-name">Kaymaz Zemin</div>
        <div class="cert-code">Nitril Kauçuk DS</div>
      </div>
      <div class="cert-card ce-card">
        <div class="ce-logo"><span>CE</span></div>
        <div class="ce-text-wrap">
          <div class="ce-title">CE Sertifikası</div>
          <div class="ce-desc">Avrupa Birliği uygunluk standartlarını karşılayan, güvenlik ve kalite sertifikalı üretim.</div>
        </div>
      </div>
    </div>
  </div>

  <div class="made-in-badge reveal">
    <div class="made-in-flag"></div>
    <div class="made-in-content">
      <div class="made-in-title">
        Tüm Paspaslar Türkiye'de Üretilmiştir
        <span class="turkey-tag">Made in Türkiye</span>
      </div>
      <div class="made-in-desc">Size üstün kalite ve hizmet garantisi veriyoruz! ECONYL® sertifikalı malzemeler, CNC lazer teknolojisi ve alanında uzman ekibimiz ile üretim yapmaktayız.</div>
    </div>
    <div class="made-in-badges">
      <div class="quality-badge" title="Kalite Garantisi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
      </div>
      <div class="quality-badge" title="Hızlı Teslimat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </div>
      <div class="quality-badge" title="Sürdürülebilir Üretim">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
      </div>
    </div>
  </div>

</section>
