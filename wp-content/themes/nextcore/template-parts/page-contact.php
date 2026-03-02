<?php
/**
 * İletişim sayfası — Next Content (İletişim) ile düzenlenebilir
 *
 * @package nextcore
 */

$contact_get = function ( $key, $default = '' ) {
	return get_option( 'eternal_contact_' . $key, $default );
};

$privacy_url = get_privacy_policy_url();
if ( empty( $privacy_url ) && function_exists( 'nextcore_get_page_url' ) ) {
	$privacy_url = nextcore_get_page_url( 'gizlilik-politikasi', 'template-gizlilik.php' );
}
if ( empty( $privacy_url ) ) {
	$privacy_url = home_url( '/gizlilik-politikasi/' );
}
$form_action = admin_url( 'admin-post.php' );

// WhatsApp numarasından wa.me linki (sadece rakamlar kullanılır)
$contact_phone_raw = get_option( 'eternal_general_topbar_phone', $contact_get( 'office_phone', '+90 212 123 45 67' ) );
$whatsapp_number = preg_replace( '/\D/', '', $contact_phone_raw );
if ( $whatsapp_number === '' ) {
	$whatsapp_number = '905321234567';
}
$whatsapp_url = 'https://wa.me/' . $whatsapp_number;

// Sosyal medya: iletişim sayfası boş/# ise genel (header) ayarları kullan
$social_fallback = function ( $contact_key, $general_key ) use ( $contact_get ) {
	$val = $contact_get( $contact_key, '#' );
	if ( $val === '' || $val === '#' ) {
		$val = get_option( 'eternal_general_' . $general_key, '#' );
	}
	return $val;
};
?>

<div class="contact-page">

<section class="contact-hero">
  <div class="hero-glow1"></div>
  <div class="hero-glow2"></div>
  <div class="contact-hero-content">
    <div class="hero-eyebrow"><span></span><span class="hero-eyebrow-text"><?php echo esc_html( $contact_get( 'hero_eyebrow', 'İletişim' ) ); ?></span><span></span></div>
    <h1><?php echo wp_kses_post( $contact_get( 'hero_heading', "Sizinle <em>Tanışmak</em><br>İstiyoruz" ) ); ?></h1>
    <p class="contact-hero-desc"><?php echo esc_html( $contact_get( 'hero_desc', 'Projeniz hakkında konuşalım. Uzman ekibimiz sorularınızı yanıtlamak ve size özel çözümler sunmak için hazır.' ) ); ?></p>
  </div>
</section>

<div class="contact-cards">
  <div class="c-card reveal">
    <div class="c-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
    <div class="c-card-title"><?php echo esc_html( $contact_get( 'card1_title', 'Telefon' ) ); ?></div>
    <div class="c-card-value"><?php echo wp_kses_post( nl2br( $contact_get( 'card1_value', "<a href=\"tel:+902121234567\">+90 212 123 45 67</a>\n<a href=\"tel:+902121234568\">+90 212 123 45 68</a>" ) ) ); ?></div>
  </div>
  <div class="c-card reveal">
    <div class="c-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
    <div class="c-card-title"><?php echo esc_html( $contact_get( 'card2_title', 'E-posta' ) ); ?></div>
    <div class="c-card-value"><?php echo wp_kses_post( nl2br( $contact_get( 'card2_value', "<a href=\"mailto:info@entrymarkcarpets.com\">info@entrymarkcarpets.com</a>\n<a href=\"mailto:sales@entrymarkcarpets.com\">sales@entrymarkcarpets.com</a>" ) ) ); ?></div>
  </div>
  <div class="c-card reveal">
    <div class="c-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
    <div class="c-card-title"><?php echo esc_html( $contact_get( 'card3_title', 'Adres' ) ); ?></div>
    <div class="c-card-value"><?php echo wp_kses_post( nl2br( esc_html( $contact_get( 'card3_value', "Organize Sanayi Bölgesi\nİstanbul, Türkiye" ) ) ) ); ?></div>
  </div>
  <div class="c-card reveal">
    <div class="c-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></div>
    <div class="c-card-title"><?php echo esc_html( $contact_get( 'card4_title', 'WhatsApp' ) ); ?></div>
    <div class="c-card-value"><?php
	$card4 = $contact_get( 'card4_value', '<a href="' . esc_url( $whatsapp_url ) . '">+90 532 123 45 67</a><br>7/24 Destek' );
	if ( strpos( $card4, 'href="#"' ) !== false ) {
		$card4 = str_replace( 'href="#"', 'href="' . esc_url( $whatsapp_url ) . '"', $card4 );
	}
	echo wp_kses_post( nl2br( $card4 ) );
?></div>
  </div>
</div>

<section class="contact-main">
  <div class="contact-grid">

    <div class="contact-form-wrap reveal">
      <div class="form-header">
        <div class="form-header-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
        <div class="form-header-text">
          <h3><?php echo esc_html( $contact_get( 'form_title', 'Bize Yazın' ) ); ?></h3>
          <p><?php echo esc_html( $contact_get( 'form_subtitle', 'Formu doldurun, en kısa sürede dönüş yapalım' ) ); ?></p>
        </div>
      </div>
      <div class="form-body">
        <form action="<?php echo esc_url( $form_action ); ?>" method="post" id="contactForm" class="contact-form">
          <input type="hidden" name="action" value="entrymark_contact_submit">
          <input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
          <?php wp_nonce_field( 'entrymark_contact', 'contact_nonce' ); ?>
          <div class="form-grid">
            <div class="form-group"><label class="form-label">Ad<span class="req">*</span></label><input class="form-input" name="first_name" id="cfName" placeholder="<?php echo esc_attr( $contact_get( 'form_placeholder_name', 'Adınız' ) ); ?>" required></div>
            <div class="form-group"><label class="form-label">Soyad<span class="req">*</span></label><input class="form-input" name="last_name" id="cfLast" placeholder="<?php echo esc_attr( $contact_get( 'form_placeholder_last', 'Soyadınız' ) ); ?>" required></div>
            <div class="form-group"><label class="form-label">E-posta<span class="req">*</span></label><input class="form-input" name="email" id="cfEmail" type="email" placeholder="<?php echo esc_attr( $contact_get( 'form_placeholder_email', 'ornek@email.com' ) ); ?>" required></div>
            <div class="form-group"><label class="form-label">Telefon</label><input class="form-input" name="phone" id="cfPhone" type="tel" placeholder="<?php echo esc_attr( $contact_get( 'form_placeholder_phone', '+90 5XX XXX XX XX' ) ); ?>"></div>
            <div class="form-group full"><label class="form-label">Şirket Adı</label><input class="form-input" name="company" id="cfCompany" placeholder="<?php echo esc_attr( $contact_get( 'form_placeholder_company', 'Şirketinizin adı (opsiyonel)' ) ); ?>"></div>
            <div class="form-group full"><label class="form-label">Konu<span class="req">*</span></label>
              <select class="form-input" name="subject" id="cfSubject" required>
                <option value=""><?php echo esc_html( $contact_get( 'form_subject_placeholder', 'Konu seçiniz' ) ); ?></option>
                <?php
                $subjects = $contact_get( 'form_subjects', "Fiyat Teklifi\nNumune Talebi\nÖzel Tasarım\nİhracat / Toptan\nTeknik Destek\nŞikâyet / Öneri\nDiğer" );
                $subjects = array_filter( array_map( 'trim', explode( "\n", $subjects ) ) );
                foreach ( $subjects as $sub ) {
                  echo '<option value="' . esc_attr( $sub ) . '">' . esc_html( $sub ) . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group full"><label class="form-label">Mesajınız<span class="req">*</span></label><textarea class="form-input" name="message" id="cfMsg" placeholder="<?php echo esc_attr( $contact_get( 'form_placeholder_message', 'Projeniz hakkında detayları paylaşın...' ) ); ?>" required></textarea></div>
            <div class="form-group full">
              <div class="form-checkbox"><input type="checkbox" name="consent" id="cfConsent" required><label for="cfConsent"><a href="<?php echo esc_url( $privacy_url ); ?>"><?php echo esc_html( $contact_get( 'form_consent_text', 'Gizlilik politikasını' ) ); ?></a> okudum ve kişisel verilerimin işlenmesini kabul ediyorum.</label></div>
            </div>
          </div>
          <button type="submit" class="form-submit" id="cfSubmit">
            <?php echo esc_html( $contact_get( 'form_submit_text', 'Mesaj Gönder' ) ); ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </form>
      </div>
    </div>

    <div class="contact-right">
      <?php
      $map_url = $contact_get( 'map_url', '' );
      if ( $map_url === '' || $map_url === '#' ) {
        $map_query = $contact_get( 'map_subtitle', 'Organize Sanayi Bölgesi, İstanbul' );
        if ( empty( $map_query ) ) {
          $map_query = $contact_get( 'office_address', 'Organize Sanayi Bölgesi, Esenyurt, İstanbul' );
        }
        $map_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( is_string( $map_query ) ? $map_query : 'Organize Sanayi Bölgesi, İstanbul' );
      }
      ?>
      <div class="map-wrap reveal">
        <div class="map-pin-dots"><span></span><span></span><span></span><span></span><span></span></div>
        <a href="<?php echo esc_url( $map_url ); ?>" class="map-placeholder map-placeholder-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Haritada göster', 'nextcore' ); ?>">
          <div class="map-placeholder-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <div class="map-placeholder-text"><?php echo esc_html( $contact_get( 'map_title', 'Entry Mark Carpets' ) ); ?></div>
          <div class="map-placeholder-sub"><?php echo esc_html( $contact_get( 'map_subtitle', 'Organize Sanayi Bölgesi, İstanbul' ) ); ?></div>
        </a>
      </div>

      <div class="office-card reveal">
        <div class="office-card-header">
          <div class="office-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M2 20h20"/><path d="M5 20V8l7-5 7 5v12"/><path d="M9 20v-6h6v6"/></svg></div>
          <div>
            <div class="office-card-name"><?php echo esc_html( $contact_get( 'office_name', 'Merkez Ofis & Fabrika' ) ); ?></div>
            <div class="office-card-type"><?php echo esc_html( $contact_get( 'office_type', 'İstanbul, Türkiye' ) ); ?></div>
          </div>
        </div>
        <div class="office-info">
          <?php
          $office_address = $contact_get( 'office_address', "Organize Sanayi Bölgesi, No: 42\nEsenyurt / İstanbul, Türkiye 34510" );
          $office_phone   = $contact_get( 'office_phone', '+90 212 123 45 67 · +90 212 123 45 68' );
          $office_email  = $contact_get( 'office_email', 'info@entrymarkcarpets.com' );
          $office_phone_digits = preg_replace( '/\D/', '', $office_phone );
          ?>
          <div class="office-row"><div class="office-row-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div><div class="office-row-text"><strong>Adres</strong><?php echo wp_kses_post( nl2br( esc_html( $office_address ) ) ); ?></div></div>
          <div class="office-row"><div class="office-row-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72"/></svg></div><div class="office-row-text"><strong>Telefon</strong><?php if ( $office_phone_digits !== '' ) : ?><a href="tel:<?php echo esc_attr( $office_phone_digits ); ?>"><?php echo esc_html( $office_phone ); ?></a><?php else : ?><?php echo esc_html( $office_phone ); ?><?php endif; ?></div></div>
          <div class="office-row"><div class="office-row-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div><div class="office-row-text"><strong>E-posta</strong><?php if ( is_email( trim( $office_email ) ) ) : ?><a href="mailto:<?php echo esc_attr( $office_email ); ?>"><?php echo esc_html( $office_email ); ?></a><?php else : ?><?php echo esc_html( $office_email ); ?><?php endif; ?></div></div>
        </div>
        <div class="office-hours">
          <div class="office-hours-title"><?php echo esc_html( $contact_get( 'office_hours_title', 'Çalışma Saatleri' ) ); ?></div>
          <div class="hours-grid">
            <?php
            $hours = $contact_get( 'office_hours', "Pazartesi – Cuma|08:30 – 18:00\nCumartesi|09:00 – 14:00\nPazar|Kapalı\nWhatsApp|7/24" );
            $hours_lines = array_filter( array_map( 'trim', explode( "\n", $hours ) ) );
            foreach ( $hours_lines as $line ) {
              $parts = array_map( 'trim', explode( '|', $line, 2 ) );
              $day = isset( $parts[0] ) ? $parts[0] : '';
              $time = isset( $parts[1] ) ? $parts[1] : '';
              $closed = ( stripos( $time, 'kapalı' ) !== false || stripos( $time, 'closed' ) !== false ) ? ' closed' : '';
              echo '<div class="hours-item' . esc_attr( $closed ) . '"><span class="day">' . esc_html( $day ) . '</span><span class="time">' . esc_html( $time ) . '</span></div>';
            }
            ?>
          </div>
        </div>
      </div>

      <div class="social-strip reveal">
        <span class="social-strip-text"><?php echo esc_html( $contact_get( 'social_title', 'Bizi Takip Edin' ) ); ?></span>
        <div class="social-strip-links">
          <?php
          $socials = array(
            'instagram' => array( 'url' => $social_fallback( 'social_instagram', 'social_instagram' ), 'title' => 'Instagram' ),
            'facebook'  => array( 'url' => $social_fallback( 'social_facebook', 'social_facebook' ), 'title' => 'Facebook' ),
            'linkedin'  => array( 'url' => $social_fallback( 'social_linkedin', 'social_linkedin' ), 'title' => 'LinkedIn' ),
            'pinterest' => array( 'url' => $social_fallback( 'social_pinterest', 'social_pinterest' ), 'title' => 'Pinterest' ),
            'youtube'   => array( 'url' => $social_fallback( 'social_youtube', 'social_youtube' ), 'title' => 'YouTube' ),
          );
          $social_icons = array(
            'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>',
            'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
            'linkedin'  => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
            'pinterest' => '<path d="M12 2C6.477 2 2 6.477 2 12c0 4.237 2.636 7.855 6.356 9.312-.088-.791-.167-2.005.035-2.868.181-.78 1.172-4.97 1.172-4.97s-.299-.598-.299-1.482c0-1.388.806-2.425 1.808-2.425.853 0 1.265.64 1.265 1.408 0 .858-.546 2.14-.828 3.33-.236.995.499 1.806 1.481 1.806 1.778 0 3.144-1.874 3.144-4.58 0-2.393-1.72-4.068-4.177-4.068-2.845 0-4.515 2.134-4.515 4.34 0 .859.331 1.781.744 2.282a.3.3 0 0 1 .069.288l-.278 1.133"/>',
            'youtube'   => '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.13C5.12 19.56 12 19.56 12 19.56s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.43z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/>',
          );
          foreach ( $socials as $key => $s ) {
            if ( empty( $s['url'] ) || $s['url'] === '#' ) continue;
            echo '<a href="' . esc_url( $s['url'] ) . '" class="social-link" target="_blank" rel="noopener noreferrer" title="' . esc_attr( $s['title'] ) . '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' . $social_icons[ $key ] . '</svg></a>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="faq-section">
  <div class="faq-header reveal">
    <div class="section-eyebrow"><span class="ey-line"></span><span class="ey-text"><?php echo esc_html( $contact_get( 'faq_eyebrow', 'Sıkça Sorulan Sorular' ) ); ?></span><span class="ey-line"></span></div>
    <h2 class="faq-title"><?php echo wp_kses_post( $contact_get( 'faq_title', 'Merak <em>Edilenler</em>' ) ); ?></h2>
    <p class="faq-sub"><?php echo esc_html( $contact_get( 'faq_subtitle', 'Aklınıza takılan soruların yanıtlarını burada bulabilirsiniz.' ) ); ?></p>
  </div>
  <div class="faq-grid" id="faqGrid">
    <?php
    $faq_items = $contact_get( 'faq_items', "Minimum sipariş miktarı nedir?|Minimum sipariş miktarımız 1 adet olup, toptan siparişlerde özel fiyatlandırma uygulanmaktadır. 10+ adet siparişlerde %15'e varan indirimlerden yararlanabilirsiniz.\nTeslimat süresi ne kadardır?|Standart üretim süresi 5–7 iş günüdür. Express seçeneği ile 72 saat içinde üretime başlanır. Kargo süresi yurt içi 1–3, yurt dışı 5–10 iş günüdür.\nHangi dosya formatlarını kabul ediyorsunuz?|Logo yükleme için PNG, JPG, SVG ve AI formatlarını kabul ediyoruz. Yüksek çözünürlüklü dosyalar (min 300 DPI) en iyi baskı kalitesini sağlar.\nÖzel ölçü sipariş verebilir miyim?|Evet! Online tasarım stüdyomuzda \"Özel Ölçü\" seçeneği ile istediğiniz boyutu cm cinsinden girebilirsiniz. Maximum 500×500 cm ölçüye kadar üretim yapılabilir.\nNumune talep edebilir miyim?|Elbette! Renk ve malzeme numuneleri ücretsiz olarak gönderilmektedir. İletişim formu üzerinden numune talebinizi iletebilirsiniz.\nİade ve değişim politikası nedir?|Standart ürünlerde 14 gün içinde ücretsiz iade/değişim yapılabilir. Özel tasarım ürünlerde üretim onayı sonrası iade kabul edilememektedir." );
    $faq_lines = array_filter( array_map( 'trim', explode( "\n", $faq_items ) ) );
    foreach ( $faq_lines as $line ) {
      $parts = array_map( 'trim', explode( '|', $line, 2 ) );
      $q = isset( $parts[0] ) ? $parts[0] : '';
      $a = isset( $parts[1] ) ? $parts[1] : '';
      if ( $q === '' ) continue;
      ?>
    <div class="faq-item"><div class="faq-question"><span class="faq-q-text"><?php echo esc_html( $q ); ?></span><div class="faq-q-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div><div class="faq-answer"><div class="faq-answer-inner"><?php echo esc_html( $a ); ?></div></div></div>
    <?php } ?>
  </div>
</section>

<section class="cta-banner reveal">
  <div class="cta-inner">
    <div class="cta-inner-text">
      <h2><?php echo wp_kses_post( $contact_get( 'cta_title', 'Projenizi <em>Hemen</em> Başlatın' ) ); ?></h2>
      <p><?php echo esc_html( $contact_get( 'cta_desc', 'Online tasarım stüdyomuz ile dakikalar içinde hayalinizdeki paspası oluşturun veya WhatsApp üzerinden bize ulaşın.' ) ); ?></p>
    </div>
    <div class="cta-inner-actions">
      <?php
          $cta_design_url = $contact_get( 'cta_design_url', '' );
          if ( $cta_design_url === '' || $cta_design_url === '#' ) {
            $cta_design_url = function_exists( 'nextcore_get_customizer_url' ) ? nextcore_get_customizer_url() : home_url( '/paspas-ozellestir/' );
          }
          ?>
      <a href="<?php echo esc_url( $cta_design_url ); ?>" class="cta-gold"><?php echo esc_html( $contact_get( 'cta_design_text', 'Tasarla' ) ); ?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7 7 7-7 7"/></svg></a>
      <?php
          $cta_wa_url = $contact_get( 'cta_whatsapp_url', $whatsapp_url );
          if ( $cta_wa_url === '' || $cta_wa_url === '#' ) {
            $cta_wa_url = $whatsapp_url;
          }
          ?>
      <a href="<?php echo esc_url( $cta_wa_url ); ?>" class="cta-whatsapp" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>WhatsApp</a>
    </div>
  </div>
</section>

<div class="toast" id="toast" aria-live="polite"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><span id="toastMsg"></span></div>

</div>
