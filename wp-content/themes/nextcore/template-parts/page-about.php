<?php
/**
 * Hakkımızda sayfası içeriği — Next Content (Hakkımızda) ile düzenlenebilir
 *
 * @package nextcore
 */

$about_get = function ( $key, $default = '' ) {
    return get_option( 'eternal_about_' . $key, $default );
};

$cta_primary_url   = $about_get( 'cta_btn_primary_url', '' );
$cta_secondary_url = $about_get( 'cta_btn_secondary_url', '' );
if ( empty( $cta_primary_url ) ) {
    $cta_primary_url = home_url( '/' );
}
if ( empty( $cta_secondary_url ) ) {
    $cta_secondary_url = home_url( '/iletisim' );
}
?>

<div class="about-page">

<section class="about-hero">
  <div class="about-hero-glow1"></div>
  <div class="about-hero-glow2"></div>
  <div class="about-hero-ring"></div>
  <div class="about-hero-ring"></div>
  <div class="about-hero-content">
    <div class="about-hero-eyebrow"><span></span><span class="about-hero-eyebrow-text"><?php echo esc_html( $about_get( 'hero_eyebrow', 'Hakkımızda' ) ); ?></span><span></span></div>
    <h1><?php echo wp_kses_post( $about_get( 'hero_heading', "30 Yıllık <em>Tutku</em> ile<br>Üretim Mükemmelliği" ) ); ?></h1>
    <p class="about-hero-desc"><?php echo esc_html( $about_get( 'hero_desc', "1994'ten bu yana, ECONYL® sertifikalı malzemeler ve CNC lazer teknolojisi ile dünyanın dört bir yanına premium halı ve paspas üretiyoruz." ) ); ?></p>
  </div>
  <div class="about-scroll"><span class="about-scroll-text"><?php echo esc_html( $about_get( 'hero_scroll_text', 'Keşfet' ) ); ?></span><div class="about-scroll-line"></div></div>
</section>

<div class="stats-bar">
  <div class="stats-bar-inner">
    <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
    <div class="stat-item">
      <div class="stat-number"><span class="count" data-target="<?php echo esc_attr( $about_get( 'stat' . $i . '_number', [ '30', '500', '40', '10000' ][ $i - 1 ] ) ); ?>">0</span><span class="stat-suffix"><?php echo esc_html( $about_get( 'stat' . $i . '_suffix', '+' ) ); ?></span></div>
      <div class="stat-label"><?php echo esc_html( $about_get( 'stat' . $i . '_label', [ 'Yıllık Deneyim', 'Otel Partneri', 'Ülkeye Teslimat', 'Tamamlanan Proje' ][ $i - 1 ] ) ); ?></div>
    </div>
    <?php endfor; ?>
  </div>
</div>

<section class="story-section">
  <div class="story-visual reveal">
    <div class="story-img-main"><div class="story-img-main-inner"><div class="story-logo-big">Entry<br>Mark<span>Carpets</span></div></div></div>
    <div class="story-img-float"><div class="float-num"><?php echo esc_html( $about_get( 'story_float_num', '30' ) ); ?></div><div class="float-text"><?php echo esc_html( $about_get( 'story_float_text', 'Yıllık Tecrübe' ) ); ?></div></div>
  </div>
  <div class="story-text reveal">
    <div class="story-eyebrow"><span class="story-eyebrow-dot"></span><span class="story-eyebrow-label"><?php echo esc_html( $about_get( 'story_eyebrow', 'Hikayemiz' ) ); ?></span></div>
    <h2 class="story-heading"><?php echo wp_kses_post( $about_get( 'story_heading', "Bir Atölyeden<br><em>Global Markaya</em>" ) ); ?></h2>
    <div class="story-body">
      <p><?php echo wp_kses_post( nl2br( esc_html( $about_get( 'story_p1', "1994 yılında İstanbul'da küçük bir atölyede başlayan yolculuğumuz, bugün 40'tan fazla ülkeye ihracat yapan, sektörün lider markalarından biri haline gelmiştir." ) ) ) ); ?></p>
      <p><?php echo wp_kses_post( nl2br( esc_html( $about_get( 'story_p2', 'Her bir ürünümüz, ECONYL® sertifikalı %100 poliamid elyaf ve son teknoloji CNC lazer kesim ile üretilmektedir. Kalite kontrol süreçlerimiz EN ISO 10874 ve EN 13501-1 standartlarına tam uygunluk sağlar.' ) ) ) ); ?></p>
      <p><?php echo wp_kses_post( nl2br( esc_html( $about_get( 'story_p3', 'Otellerden kurumsal ofislere, restoranlardan konutlara kadar her mekâna özel çözümler sunuyor, müşterilerimizin hayallerini gerçeğe dönüştürüyoruz.' ) ) ) ); ?></p>
    </div>
    <div class="story-signature">
      <div class="signature-avatar"><span>EM</span></div>
      <div><div class="signature-name"><?php echo esc_html( $about_get( 'story_signature_name', 'Kurucu & CEO' ) ); ?></div><div class="signature-role"><?php echo esc_html( $about_get( 'story_signature_role', 'Entry Mark Carpets' ) ); ?></div></div>
    </div>
  </div>
</section>

<section class="values-section">
  <div class="values-header reveal">
    <div class="section-eyebrow"><span class="ey-line"></span><span class="ey-text"><?php echo esc_html( $about_get( 'values_eyebrow', 'Değerlerimiz' ) ); ?></span><span class="ey-line"></span></div>
    <h2 class="values-title"><?php echo wp_kses_post( $about_get( 'values_title', 'Bizi Biz Yapan <em>Değerler</em>' ) ); ?></h2>
    <p class="values-subtitle"><?php echo esc_html( $about_get( 'values_subtitle', 'Her ürünümüzde bu değerleri yaşatıyor, müşterilerimize en iyisini sunuyoruz.' ) ); ?></p>
  </div>
  <div class="values-grid">
    <?php
    $value_titles = [ $about_get( 'value1_title', 'Kalite' ), $about_get( 'value2_title', 'İnovasyon' ), $about_get( 'value3_title', 'Sürdürülebilirlik' ), $about_get( 'value4_title', 'Müşteri Odaklılık' ) ];
    $value_descs  = [
        $about_get( 'value1_desc', 'ECONYL® sertifikalı malzemeler, CE belgesi ve uluslararası standartlara tam uygunluk ile üstün kalite garantisi.' ),
        $about_get( 'value2_desc', 'CNC lazer teknolojisi, online tasarım stüdyosu ve sürekli AR-GE yatırımı ile sektörün öncüsü olmaya devam ediyoruz.' ),
        $about_get( 'value3_desc', 'Geri dönüştürülmüş ECONYL® elyaf kullanımı, çevre dostu üretim süreçleri ve karbon ayak izi azaltma hedeflerimiz.' ),
        $about_get( 'value4_desc', 'Ücretsiz tasarımcı desteği, 24 saat onay süreci ve kişiye özel çözümler ile tam memnuniyet odaklı hizmet.' ),
    ];
    $value_icons = [
        '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
        '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
        '<path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m7 10 2 2 6-6"/><path d="m7 16 2 2 6-6"/>',
        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    ];
    for ( $i = 0; $i < 4; $i++ ) :
    ?>
    <div class="value-card reveal"><span class="value-card-num"><?php echo sprintf( '%02d', $i + 1 ); ?></span><div class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo $value_icons[ $i ]; ?></svg></div><div class="value-card-title"><?php echo esc_html( $value_titles[ $i ] ); ?></div><div class="value-card-desc"><?php echo esc_html( $value_descs[ $i ] ); ?></div></div>
    <?php endfor; ?>
  </div>
</section>

<section class="timeline-section">
  <div class="timeline-header reveal">
    <div class="section-eyebrow"><span class="ey-line"></span><span class="ey-text"><?php echo esc_html( $about_get( 'timeline_eyebrow', 'Kilometre Taşlarımız' ) ); ?></span><span class="ey-line"></span></div>
    <h2 class="values-title"><?php echo wp_kses_post( $about_get( 'timeline_title', 'Yolculuğumuzun <em>Hikayesi</em>' ) ); ?></h2>
  </div>
  <div class="timeline">
    <?php
    $timeline_defaults = [
        [ 'year' => '1994', 'title' => 'Kuruluş', 'desc' => "İstanbul'da küçük bir atölyede halı üretimine başlandı. İlk yılda 500 adet paspas üretildi." ],
        [ 'year' => '2003', 'title' => 'İlk İhracat', 'desc' => "Avrupa pazarına ilk ihracat gerçekleştirildi. Almanya ve İngiltere'ye düzenli sevkiyat başladı." ],
        [ 'year' => '2010', 'title' => 'CE Sertifikası', 'desc' => 'Avrupa Birliği CE sertifikası alınarak uluslararası kalite standartlarına tam uygunluk sağlandı.' ],
        [ 'year' => '2016', 'title' => 'ECONYL® Partnerliği', 'desc' => 'ECONYL® markası ile partnerlik anlaşması imzalandı. %100 geri dönüştürülmüş elyaf kullanımına geçildi.' ],
        [ 'year' => '2020', 'title' => 'CNC Lazer Teknolojisi', 'desc' => 'Son teknoloji CNC lazer kesim sistemleri devreye alınarak üretim kalitesi ve hızı artırıldı.' ],
        [ 'year' => '2024', 'title' => 'Online Tasarım Stüdyosu', 'desc' => 'Müşterilerin kendi tasarımlarını oluşturabildiği online interaktif tasarım platformu hizmete açıldı.' ],
    ];
    for ( $i = 1; $i <= 6; $i++ ) :
        $td = $timeline_defaults[ $i - 1 ];
        $year  = $about_get( 'timeline_' . $i . '_year', $td['year'] );
        $title = $about_get( 'timeline_' . $i . '_title', $td['title'] );
        $desc  = $about_get( 'timeline_' . $i . '_desc', $td['desc'] );
    ?>
    <div class="timeline-item reveal"><div class="timeline-dot"></div><div class="timeline-content"><div class="timeline-year"><?php echo esc_html( $year ); ?></div><div class="timeline-title"><?php echo esc_html( $title ); ?></div><div class="timeline-desc"><?php echo esc_html( $desc ); ?></div></div></div>
    <?php endfor; ?>
  </div>
</section>

<section class="team-section">
  <div class="team-header reveal">
    <div class="section-eyebrow"><span class="ey-line"></span><span class="ey-text"><?php echo esc_html( $about_get( 'team_eyebrow', 'Ekibimiz' ) ); ?></span><span class="ey-line"></span></div>
    <h2 class="values-title"><?php echo wp_kses_post( $about_get( 'team_title', 'Uzman <em>Kadromuz</em>' ) ); ?></h2>
    <p class="values-subtitle"><?php echo esc_html( $about_get( 'team_subtitle', 'Alanında deneyimli profesyonellerden oluşan ekibimizle hizmetinizdeyiz.' ) ); ?></p>
  </div>
  <div class="team-grid">
    <?php
    $team_defaults = [
        [ 'name' => 'Ahmet Yılmaz', 'role' => 'Kurucu & CEO', 'bio' => '30 yıllık sektör deneyimi ile şirketi global bir markaya dönüştürdü.', 'initials' => 'AY' ],
        [ 'name' => 'Elif Kaya', 'role' => 'Tasarım Direktörü', 'bio' => 'Modern ve geleneksel motifleri birleştiren koleksiyonların yaratıcısı.', 'initials' => 'EK' ],
        [ 'name' => 'Mehmet Demir', 'role' => 'Üretim Müdürü', 'bio' => 'CNC lazer sistemleri ve kalite kontrol süreçlerinin yöneticisi.', 'initials' => 'MD' ],
        [ 'name' => 'Zeynep Öztürk', 'role' => 'İhracat Direktörü', 'bio' => '40+ ülkeye ihracat operasyonları ve uluslararası müşteri ilişkileri.', 'initials' => 'ZÖ' ],
    ];
    for ( $i = 1; $i <= 4; $i++ ) :
        $tm = $team_defaults[ $i - 1 ];
        $name    = $about_get( 'team_' . $i . '_name', $tm['name'] );
        $role    = $about_get( 'team_' . $i . '_role', $tm['role'] );
        $bio     = $about_get( 'team_' . $i . '_bio', $tm['bio'] );
        $initials = $about_get( 'team_' . $i . '_initials', $tm['initials'] );
        $linkedin = $about_get( 'team_' . $i . '_linkedin', '#' );
        $twitter  = $about_get( 'team_' . $i . '_twitter', '' );
    ?>
    <div class="team-card reveal">
      <div class="team-card-img"><div class="team-avatar"><span><?php echo esc_html( $initials ); ?></span></div></div>
      <div class="team-card-info">
        <div class="team-name"><?php echo esc_html( $name ); ?></div>
        <div class="team-role"><?php echo esc_html( $role ); ?></div>
        <div class="team-bio"><?php echo esc_html( $bio ); ?></div>
        <div class="team-social">
          <?php if ( ! empty( $linkedin ) ) : ?><a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a><?php endif; ?>
          <?php if ( ! empty( $twitter ) ) : ?><a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53A4.48 4.48 0 0 0 12 7.5v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg></a><?php endif; ?>
        </div>
      </div>
    </div>
    <?php endfor; ?>
  </div>
</section>

<section class="cta-banner reveal">
  <div class="cta-banner-inner">
    <h2 class="cta-banner-title"><?php echo wp_kses_post( $about_get( 'cta_title', 'Hayalinizdeki Paspası <em>Birlikte</em> Tasarlayalım' ) ); ?></h2>
    <p class="cta-banner-desc"><?php echo esc_html( $about_get( 'cta_desc', 'Online tasarım stüdyomuz ile logonuzu, renginizi ve ölçünüzü seçin. Uzman ekibimiz size destek olsun.' ) ); ?></p>
    <div class="cta-banner-btns">
      <a href="<?php echo esc_url( $cta_primary_url ); ?>" class="cta-btn-gold"><?php echo esc_html( $about_get( 'cta_btn_primary_text', 'Tasarlamaya Başla' ) ); ?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7 7 7-7 7"/></svg></a>
      <a href="<?php echo esc_url( $cta_secondary_url ); ?>" class="cta-btn-outline"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg><?php echo esc_html( $about_get( 'cta_btn_secondary_text', 'Bize Ulaşın' ) ); ?></a>
    </div>
  </div>
</section>

</div>
