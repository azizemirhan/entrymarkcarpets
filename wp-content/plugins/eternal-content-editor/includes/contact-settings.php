<?php
/**
 * İletişim sayfası — Next Content ile yönetim
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('ece_contact_get')) {
    function ece_contact_get($key, $default = '')
    {
        return get_option('eternal_contact_' . $key, $default);
    }
}

function ece_contact_settings_page()
{
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'hero';
    $tabs = [
        'hero'   => ['label' => 'Hero', 'icon' => 'fas fa-image'],
        'cards'  => ['label' => 'İletişim Kartları', 'icon' => 'fas fa-address-card'],
        'form'   => ['label' => 'Form & Ofis', 'icon' => 'fas fa-envelope'],
        'faq'    => ['label' => 'SSS', 'icon' => 'fas fa-question-circle'],
        'cta'    => ['label' => 'CTA', 'icon' => 'fas fa-hand-point-right'],
    ];
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="eceContactForm" novalidate>
        <input type="hidden" name="action" value="ece_save_contact">
        <?php wp_nonce_field('ece_save_contact_action', 'ece_contact_nonce'); ?>
        <input type="hidden" name="ece_save_contact" value="1">
        <input type="hidden" name="ece_active_tab" value="<?php echo esc_attr($active_tab); ?>" id="eceContactActiveTab">

        <div class="ece-submit-bar ece-submit-bar--sticky">
            <button type="submit" class="button button-primary button-hero">
                <i class="fas fa-save"></i> Ayarları Kaydet
            </button>
        </div>

        <div class="ece-page-header">
            <i class="fas fa-envelope"></i>
            <span>Next Content — İletişim Sayfası</span>
        </div>

        <div class="ece-tabs">
            <?php foreach ($tabs as $k => $tab): ?>
                <button type="button" class="ece-tab <?php echo ($active_tab === $k) ? 'ece-tab--active' : ''; ?>"
                    data-tab="<?php echo esc_attr($k); ?>">
                    <i class="<?php echo esc_attr($tab['icon']); ?>"></i>
                    <?php echo esc_html($tab['label']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- TAB: HERO -->
        <div class="ece-tab-content <?php echo ($active_tab === 'hero') ? 'ece-tab-content--active' : ''; ?>" id="tab-hero">
            <div class="ece-card">
                <div class="ece-card-title">Hero</div>
                <div class="ece-field">
                    <label>Üst etiket (eyebrow)</label>
                    <input type="text" name="ece[hero_eyebrow]" value="<?php echo esc_attr(ece_contact_get('hero_eyebrow', 'İletişim')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ana başlık (HTML: &lt;em&gt;, &lt;br&gt;)</label>
                    <textarea name="ece[hero_heading]" rows="2"><?php echo esc_textarea(ece_contact_get('hero_heading', "Sizinle <em>Tanışmak</em><br>İstiyoruz")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Açıklama</label>
                    <textarea name="ece[hero_desc]" rows="2"><?php echo esc_textarea(ece_contact_get('hero_desc', 'Projeniz hakkında konuşalım. Uzman ekibimiz sorularınızı yanıtlamak ve size özel çözümler sunmak için hazır.')); ?></textarea>
                </div>
            </div>
        </div>

        <!-- TAB: CARDS -->
        <div class="ece-tab-content <?php echo ($active_tab === 'cards') ? 'ece-tab-content--active' : ''; ?>" id="tab-cards">
            <div class="ece-card">
                <div class="ece-card-title">4 iletişim kartı (Telefon, E-posta, Adres, WhatsApp)</div>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="ece-field">
                    <label>Kart <?php echo $i; ?> — Başlık</label>
                    <input type="text" name="ece[card<?php echo $i; ?>_title]" value="<?php echo esc_attr(ece_contact_get('card' . $i . '_title', ['Telefon', 'E-posta', 'Adres', 'WhatsApp'][$i - 1])); ?>">
                </div>
                <div class="ece-field">
                    <label>Kart <?php echo $i; ?> — İçerik (HTML: &lt;a href="..."&gt;, satır sonu için Enter)</label>
                    <textarea name="ece[card<?php echo $i; ?>_value]" rows="3"><?php echo esc_textarea(ece_contact_get('card' . $i . '_value', [
                        "<a href=\"tel:+902121234567\">+90 212 123 45 67</a>\n<a href=\"tel:+902121234568\">+90 212 123 45 68</a>",
                        "<a href=\"mailto:info@entrymarkcarpets.com\">info@entrymarkcarpets.com</a>\n<a href=\"mailto:sales@entrymarkcarpets.com\">sales@entrymarkcarpets.com</a>",
                        "Organize Sanayi Bölgesi\nİstanbul, Türkiye",
                        '<a href="#">+90 532 123 45 67</a><br>7/24 Destek',
                    ][$i - 1])); ?></textarea>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- TAB: FORM & OFFICE -->
        <div class="ece-tab-content <?php echo ($active_tab === 'form') ? 'ece-tab-content--active' : ''; ?>" id="tab-form">
            <div class="ece-card">
                <div class="ece-card-title">Form başlığı</div>
                <div class="ece-field">
                    <label>Form başlığı</label>
                    <input type="text" name="ece[form_title]" value="<?php echo esc_attr(ece_contact_get('form_title', 'Bize Yazın')); ?>">
                </div>
                <div class="ece-field">
                    <label>Form alt metni</label>
                    <input type="text" name="ece[form_subtitle]" value="<?php echo esc_attr(ece_contact_get('form_subtitle', 'Formu doldurun, en kısa sürede dönüş yapalım')); ?>">
                </div>
                <div class="ece-field">
                    <label>Konu seçenekleri (her satıra bir seçenek)</label>
                    <textarea name="ece[form_subjects]" rows="8"><?php echo esc_textarea(ece_contact_get('form_subjects', "Fiyat Teklifi\nNumune Talebi\nÖzel Tasarım\nİhracat / Toptan\nTeknik Destek\nŞikâyet / Öneri\nDiğer")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Gönder butonu metni</label>
                    <input type="text" name="ece[form_submit_text]" value="<?php echo esc_attr(ece_contact_get('form_submit_text', 'Mesaj Gönder')); ?>">
                </div>
            </div>
            <div class="ece-card">
                <div class="ece-card-title">Harita placeholder</div>
                <div class="ece-field">
                    <label>Harita başlık</label>
                    <input type="text" name="ece[map_title]" value="<?php echo esc_attr(ece_contact_get('map_title', 'Entry Mark Carpets')); ?>">
                </div>
                <div class="ece-field">
                    <label>Harita alt metin</label>
                    <input type="text" name="ece[map_subtitle]" value="<?php echo esc_attr(ece_contact_get('map_subtitle', 'Organize Sanayi Bölgesi, İstanbul')); ?>">
                </div>
            </div>
            <div class="ece-card">
                <div class="ece-card-title">Ofis kartı</div>
                <div class="ece-field">
                    <label>Ofis adı</label>
                    <input type="text" name="ece[office_name]" value="<?php echo esc_attr(ece_contact_get('office_name', 'Merkez Ofis & Fabrika')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ofis türü / konum</label>
                    <input type="text" name="ece[office_type]" value="<?php echo esc_attr(ece_contact_get('office_type', 'İstanbul, Türkiye')); ?>">
                </div>
                <div class="ece-field">
                    <label>Adres (satır sonu için Enter)</label>
                    <textarea name="ece[office_address]" rows="2"><?php echo esc_textarea(ece_contact_get('office_address', "Organize Sanayi Bölgesi, No: 42\nEsenyurt / İstanbul, Türkiye 34510")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Telefon</label>
                    <input type="text" name="ece[office_phone]" value="<?php echo esc_attr(ece_contact_get('office_phone', '+90 212 123 45 67 · +90 212 123 45 68')); ?>">
                </div>
                <div class="ece-field">
                    <label>E-posta</label>
                    <input type="email" name="ece[office_email]" value="<?php echo esc_attr(ece_contact_get('office_email', 'info@entrymarkcarpets.com')); ?>">
                </div>
                <div class="ece-field">
                    <label>Çalışma saatleri başlığı</label>
                    <input type="text" name="ece[office_hours_title]" value="<?php echo esc_attr(ece_contact_get('office_hours_title', 'Çalışma Saatleri')); ?>">
                </div>
                <div class="ece-field">
                    <label>Çalışma saatleri (her satır: Gün|Saat)</label>
                    <textarea name="ece[office_hours]" rows="5"><?php echo esc_textarea(ece_contact_get('office_hours', "Pazartesi – Cuma|08:30 – 18:00\nCumartesi|09:00 – 14:00\nPazar|Kapalı\nWhatsApp|7/24")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Sosyal şerit metni</label>
                    <input type="text" name="ece[social_title]" value="<?php echo esc_attr(ece_contact_get('social_title', 'Bizi Takip Edin')); ?>">
                </div>
                <div class="ece-field">
                    <label>Instagram URL</label>
                    <input type="text" name="ece[social_instagram]" value="<?php echo esc_attr(ece_contact_get('social_instagram', '#')); ?>">
                </div>
                <div class="ece-field">
                    <label>Facebook URL</label>
                    <input type="text" name="ece[social_facebook]" value="<?php echo esc_attr(ece_contact_get('social_facebook', '#')); ?>">
                </div>
                <div class="ece-field">
                    <label>LinkedIn URL</label>
                    <input type="text" name="ece[social_linkedin]" value="<?php echo esc_attr(ece_contact_get('social_linkedin', '#')); ?>">
                </div>
                <div class="ece-field">
                    <label>Pinterest URL</label>
                    <input type="text" name="ece[social_pinterest]" value="<?php echo esc_attr(ece_contact_get('social_pinterest', '#')); ?>">
                </div>
                <div class="ece-field">
                    <label>YouTube URL</label>
                    <input type="text" name="ece[social_youtube]" value="<?php echo esc_attr(ece_contact_get('social_youtube', '#')); ?>">
                </div>
            </div>
        </div>

        <!-- TAB: FAQ -->
        <div class="ece-tab-content <?php echo ($active_tab === 'faq') ? 'ece-tab-content--active' : ''; ?>" id="tab-faq">
            <div class="ece-card">
                <div class="ece-card-title">SSS başlık alanı</div>
                <div class="ece-field">
                    <label>Üst etiket</label>
                    <input type="text" name="ece[faq_eyebrow]" value="<?php echo esc_attr(ece_contact_get('faq_eyebrow', 'Sıkça Sorulan Sorular')); ?>">
                </div>
                <div class="ece-field">
                    <label>Başlık (HTML: &lt;em&gt;)</label>
                    <input type="text" name="ece[faq_title]" value="<?php echo esc_attr(ece_contact_get('faq_title', 'Merak <em>Edilenler</em>')); ?>">
                </div>
                <div class="ece-field">
                    <label>Alt metin</label>
                    <input type="text" name="ece[faq_subtitle]" value="<?php echo esc_attr(ece_contact_get('faq_subtitle', 'Aklınıza takılan soruların yanıtlarını burada bulabilirsiniz.')); ?>">
                </div>
                <div class="ece-card-title">SSS öğeleri (her satır: Soru|Cevap)</div>
                <div class="ece-field">
                    <textarea name="ece[faq_items]" rows="15"><?php echo esc_textarea(ece_contact_get('faq_items', "Minimum sipariş miktarı nedir?|Minimum sipariş miktarımız 1 adet olup, toptan siparişlerde özel fiyatlandırma uygulanmaktadır. 10+ adet siparişlerde %15'e varan indirimlerden yararlanabilirsiniz.\nTeslimat süresi ne kadardır?|Standart üretim süresi 5–7 iş günüdür. Express seçeneği ile 72 saat içinde üretime başlanır. Kargo süresi yurt içi 1–3, yurt dışı 5–10 iş günüdür.\nHangi dosya formatlarını kabul ediyorsunuz?|Logo yükleme için PNG, JPG, SVG ve AI formatlarını kabul ediyoruz. Yüksek çözünürlüklü dosyalar (min 300 DPI) en iyi baskı kalitesini sağlar.\nÖzel ölçü sipariş verebilir miyim?|Evet! Online tasarım stüdyomuzda \"Özel Ölçü\" seçeneği ile istediğiniz boyutu cm cinsinden girebilirsiniz. Maximum 500×500 cm ölçüye kadar üretim yapılabilir.\nNumune talep edebilir miyim?|Elbette! Renk ve malzeme numuneleri ücretsiz olarak gönderilmektedir. İletişim formu üzerinden numune talebinizi iletebilirsiniz.\nİade ve değişim politikası nedir?|Standart ürünlerde 14 gün içinde ücretsiz iade/değişim yapılabilir. Özel tasarım ürünlerde üretim onayı sonrası iade kabul edilememektedir.")); ?></textarea>
                </div>
            </div>
        </div>

        <!-- TAB: CTA -->
        <div class="ece-tab-content <?php echo ($active_tab === 'cta') ? 'ece-tab-content--active' : ''; ?>" id="tab-cta">
            <div class="ece-card">
                <div class="ece-card-title">Alt CTA alanı</div>
                <div class="ece-field">
                    <label>Başlık (HTML: &lt;em&gt;)</label>
                    <input type="text" name="ece[cta_title]" value="<?php echo esc_attr(ece_contact_get('cta_title', 'Projenizi <em>Hemen</em> Başlatın')); ?>">
                </div>
                <div class="ece-field">
                    <label>Açıklama</label>
                    <textarea name="ece[cta_desc]" rows="2"><?php echo esc_textarea(ece_contact_get('cta_desc', 'Online tasarım stüdyomuz ile dakikalar içinde hayalinizdeki paspası oluşturun veya WhatsApp üzerinden bize ulaşın.')); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Tasarla buton metni</label>
                    <input type="text" name="ece[cta_design_text]" value="<?php echo esc_attr(ece_contact_get('cta_design_text', 'Tasarla')); ?>">
                </div>
                <div class="ece-field">
                    <label>Tasarla buton linki</label>
                    <input type="text" name="ece[cta_design_url]" value="<?php echo esc_attr(ece_contact_get('cta_design_url', '')); ?>" placeholder="<?php echo esc_attr(home_url('/')); ?>">
                </div>
                <div class="ece-field">
                    <label>WhatsApp buton linki</label>
                    <input type="text" name="ece[cta_whatsapp_url]" value="<?php echo esc_attr(ece_contact_get('cta_whatsapp_url', '#')); ?>">
                </div>
            </div>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary">Kaydet</button>
        </p>
    </form>
    <script>
    (function(){
        var form = document.getElementById('eceContactForm');
        if (!form) return;
        var tabs = form.querySelectorAll('.ece-tab-content[id^="tab-"]');
        form.querySelectorAll('.ece-tabs .ece-tab').forEach(function(btn){
            btn.addEventListener('click', function(){
                var t = this.getAttribute('data-tab');
                var input = document.getElementById('eceContactActiveTab');
                if (input) input.value = t;
                tabs.forEach(function(c){ c.classList.remove('ece-tab-content--active'); });
                var el = form.querySelector('#tab-' + t);
                if (el) el.classList.add('ece-tab-content--active');
                form.querySelectorAll('.ece-tabs .ece-tab').forEach(function(b){ b.classList.remove('ece-tab--active'); });
                this.classList.add('ece-tab--active');
            });
        });
    })();
    </script>
    <?php
}
