<?php
/**
 * Anasayfa bölümleri (Hero, Özellikler, CTA) — Next Content ile yönetim
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('ece_home_get')) {
    /**
     * Anasayfa ayarını getirir.
     *
     * @param string $key Option key (eternal_home_ öneki otomatik eklenir)
     * @param string $default Varsayılan değer
     * @return string|array
     */
    function ece_home_get($key, $default = '')
    {
        return get_option('eternal_home_' . $key, $default);
    }
}

function ece_home_settings_page()
{
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'hero';
    $tabs = [
        'hero'     => ['label' => 'Hero (Üst Alan)', 'icon' => 'fas fa-image'],
        'features' => ['label' => 'Özellikler Şeridi', 'icon' => 'fas fa-th-list'],
        'cta'      => ['label' => 'Nasıl Çalışır / CTA', 'icon' => 'fas fa-hand-point-right'],
        'specs'    => ['label' => 'Teknik Özellikler', 'icon' => 'fas fa-cogs'],
    ];
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="eceHomeForm" novalidate>
        <input type="hidden" name="action" value="ece_save_home">
        <?php wp_nonce_field('ece_save_home_action', 'ece_home_nonce'); ?>
        <input type="hidden" name="ece_save_home" value="1">
        <input type="hidden" name="ece_active_tab" value="<?php echo esc_attr($active_tab); ?>" id="eceHomeActiveTab">

        <div class="ece-submit-bar ece-submit-bar--sticky">
            <button type="submit" class="button button-primary button-hero">
                <i class="fas fa-save"></i> Ayarları Kaydet
            </button>
        </div>

        <div class="ece-page-header">
            <i class="fas fa-home"></i>
            <span>Next Content — Anasayfa Bölümleri</span>
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
            <?php ece_render_section_status('home', 'hero'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Hero — Sol Alan Arka Planı (Resim / Video)</div>
                <div class="ece-field">
                    <label>Arka plan türü</label>
                    <select name="ece[hero_bg_type]" id="eceHeroBgType">
                        <option value="default" <?php selected(ece_home_get('hero_bg_type', 'default'), 'default'); ?>>Varsayılan (gradient)</option>
                        <option value="image" <?php selected(ece_home_get('hero_bg_type'), 'image'); ?>>Resim</option>
                        <option value="video" <?php selected(ece_home_get('hero_bg_type'), 'video'); ?>>Video</option>
                    </select>
                </div>
                <div class="ece-field ece-hero-bg-image-field">
                    <label>Arka plan resmi (URL)</label>
                    <div class="ece-field-group">
                        <input type="url" name="ece[hero_bg_image]" value="<?php echo esc_attr(ece_home_get('hero_bg_image')); ?>" placeholder="https://...">
                        <button type="button" class="ece-upload-btn"><i class="fas fa-upload"></i> Yükle</button>
                    </div>
                    <div class="ece-image-preview">
                        <?php if (ece_home_get('hero_bg_image')): ?>
                            <img src="<?php echo esc_url(ece_home_get('hero_bg_image')); ?>" alt="Arka plan önizleme">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="ece-field ece-hero-bg-video-field">
                    <label>Arka plan video (MP4 URL)</label>
                    <input type="url" name="ece[hero_bg_video]" value="<?php echo esc_attr(ece_home_get('hero_bg_video')); ?>" placeholder="https://...video.mp4">
                    <p class="description">MP4 formatı önerilir. Video sessiz ve döngüde oynatılır.</p>
                </div>
                <div class="ece-field">
                    <label>Karartma (overlay) — metnin okunabilirliği (0 = yok, 1 = tam koyu)</label>
                    <input type="number" name="ece[hero_bg_overlay]" value="<?php echo esc_attr(ece_home_get('hero_bg_overlay', '0.45')); ?>" min="0" max="1" step="0.05">
                </div>
            </div>
            <div class="ece-card">
                <div class="ece-card-title">Hero — Üst Başlık Alanı</div>
                <div class="ece-field">
                    <label>Üst etiket (eyebrow)</label>
                    <input type="text" name="ece[hero_eyebrow]" value="<?php echo esc_attr(ece_home_get('hero_eyebrow', 'Custom Carpet Designer')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ana başlık — 1. satır</label>
                    <input type="text" name="ece[hero_heading_1]" value="<?php echo esc_attr(ece_home_get('hero_heading_1', 'Design Your')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ana başlık — 2. satır (vurgulu kelime italik)</label>
                    <input type="text" name="ece[hero_heading_2]" value="<?php echo esc_attr(ece_home_get('hero_heading_2', 'Perfect Carpet')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ana başlık — 3. satır</label>
                    <input type="text" name="ece[hero_heading_3]" value="<?php echo esc_attr(ece_home_get('hero_heading_3', 'in Minutes')); ?>">
                </div>
                <div class="ece-field">
                    <label>Alt metin (açıklama)</label>
                    <textarea name="ece[hero_subtext]" rows="3"><?php echo esc_textarea(ece_home_get('hero_subtext', 'Choose from 24+ premium colors, select your size, upload your logo, and watch your custom carpet come to life — all in our interactive design studio.')); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Birincil buton metni</label>
                    <input type="text" name="ece[hero_cta_primary_text]" value="<?php echo esc_attr(ece_home_get('hero_cta_primary_text', 'Start Designing')); ?>">
                </div>
                <div class="ece-field">
                    <label>Birincil buton linki (URL)</label>
                    <input type="url" name="ece[hero_cta_primary_url]" value="<?php echo esc_attr(ece_home_get('hero_cta_primary_url', '')); ?>">
                    <p class="description">Boş bırakılırsa kişiselleştirici sayfasına yönlendirilir (shortcode’lu sayfa).</p>
                </div>
                <div class="ece-field">
                    <label>İkincil buton metni</label>
                    <input type="text" name="ece[hero_cta_secondary_text]" value="<?php echo esc_attr(ece_home_get('hero_cta_secondary_text', 'See How It Works')); ?>">
                </div>
                <div class="ece-field">
                    <label>İkincil buton linki (URL veya #how-it-works)</label>
                    <input type="text" name="ece[hero_cta_secondary_url]" value="<?php echo esc_attr(ece_home_get('hero_cta_secondary_url', '#how-it-works')); ?>">
                </div>
            </div>
            <div class="ece-card">
                <div class="ece-card-title">Hero — İstatistikler (4 kutu)</div>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="ece-field">
                        <label>İstatistik <?php echo (int) $i; ?> — Sayı + birim (örn. 500+ veya 30yrs)</label>
                        <input type="text" name="ece[hero_stat<?php echo $i; ?>_number]" value="<?php echo esc_attr(ece_home_get('hero_stat' . $i . '_number', ['500+', '40+', '30yrs', '24h'][$i - 1])); ?>">
                    </div>
                    <div class="ece-field">
                        <label>İstatistik <?php echo (int) $i; ?> — Etiket</label>
                        <input type="text" name="ece[hero_stat<?php echo $i; ?>_label]" value="<?php echo esc_attr(ece_home_get('hero_stat' . $i . '_label', ['Hotels Worldwide', 'Countries Delivered', 'Of Excellence', 'Design Approval'][$i - 1])); ?>">
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- TAB: FEATURES RIBBON -->
        <div class="ece-tab-content <?php echo ($active_tab === 'features') ? 'ece-tab-content--active' : ''; ?>" id="tab-features">
            <?php ece_render_section_status('home', 'features'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Özellikler şeridi — 4 öğe</div>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="ece-field">
                        <label>Özellik <?php echo (int) $i; ?> — Başlık</label>
                        <input type="text" name="ece[features_title_<?php echo $i; ?>]" value="<?php echo esc_attr(ece_home_get('features_title_' . $i, ['Premium Quality', 'Global Shipping', 'Express Production', 'Designer Support'][$i - 1])); ?>">
                    </div>
                    <div class="ece-field">
                        <label>Özellik <?php echo (int) $i; ?> — Açıklama</label>
                        <input type="text" name="ece[features_desc_<?php echo $i; ?>]" value="<?php echo esc_attr(ece_home_get('features_desc_' . $i, ['Certified materials only', '40+ countries worldwide', '72-hour rush available', 'Free design consultation'][$i - 1])); ?>">
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- TAB: CTA / HOW IT WORKS -->
        <div class="ece-tab-content <?php echo ($active_tab === 'cta') ? 'ece-tab-content--active' : ''; ?>" id="tab-cta">
            <?php ece_render_section_status('home', 'cta'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Nasıl Çalışır — 4 adım kartı</div>
                <?php
                $step_titles = ['Renk Seçin', 'Ölçü Belirleyin', 'Logo & Yazı Ekleyin', 'Sipariş Verin'];
                $step_descs = [
                    '24+ premium renk seçeneğinden istediğinizi belirleyin',
                    'Standart veya özel ölçü ile mükemmel boyutu seçin',
                    'Logonuzu yükleyin veya metin ekleyerek kişiselleştirin',
                    'Tasarımınızı onaylayın ve kapınıza teslim edelim',
                ];
                for ($i = 1; $i <= 4; $i++):
                    ?>
                    <div class="ece-field">
                        <label>Adım <?php echo (int) $i; ?> — Başlık</label>
                        <input type="text" name="ece[cta_step<?php echo $i; ?>_title]" value="<?php echo esc_attr(ece_home_get('cta_step' . $i . '_title', $step_titles[$i - 1])); ?>">
                    </div>
                    <div class="ece-field">
                        <label>Adım <?php echo (int) $i; ?> — Açıklama</label>
                        <input type="text" name="ece[cta_step<?php echo $i; ?>_desc]" value="<?php echo esc_attr(ece_home_get('cta_step' . $i . '_desc', $step_descs[$i - 1])); ?>">
                    </div>
                <?php endfor; ?>
            </div>
            <div class="ece-card">
                <div class="ece-card-title">Sağ blok — Başlık ve butonlar</div>
                <div class="ece-field">
                    <label>Üst etiket (eyebrow)</label>
                    <input type="text" name="ece[cta_eyebrow]" value="<?php echo esc_attr(ece_home_get('cta_eyebrow', 'Nasıl Çalışır?')); ?>">
                </div>
                <div class="ece-field">
                    <label>Başlık (satır sonları &lt;br&gt; ile)</label>
                    <input type="text" name="ece[cta_heading]" value="<?php echo esc_attr(ece_home_get('cta_heading', 'Hayalinizdeki Paspası 4 Adımda Oluşturun')); ?>">
                </div>
                <div class="ece-field">
                    <label>Paragraf</label>
                    <textarea name="ece[cta_desc]" rows="3"><?php echo esc_textarea(ece_home_get('cta_desc', 'Online tasarım stüdyomuz ile logonuzu, renginizi ve ölçünüzü seçin. Tasarımcı ekibimiz üretim öncesi onayınız için size özel bir ön izleme sunacaktır.')); ?></textarea>
                </div>
                <div class="ece-card-title">Madde listesi (4 satır)</div>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="ece-field">
                        <label>Madde <?php echo (int) $i; ?></label>
                        <input type="text" name="ece[cta_list_<?php echo $i; ?>]" value="<?php echo esc_attr(ece_home_get('cta_list_' . $i, [
                            'Anlık canlı önizleme ile tasarımınızı görün',
                            'Yüksek çözünürlük JPG olarak indirin',
                            'Express 72 saat üretim seçeneği',
                            'Ücretsiz tasarımcı desteği ve danışmanlık',
                        ][$i - 1])); ?>">
                    </div>
                <?php endfor; ?>
                <div class="ece-field">
                    <label>Ana buton metni</label>
                    <input type="text" name="ece[cta_btn_text]" value="<?php echo esc_attr(ece_home_get('cta_btn_text', 'Tasarlamaya Başla')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ana buton linki (URL)</label>
                    <input type="url" name="ece[cta_btn_url]" value="<?php echo esc_attr(ece_home_get('cta_btn_url', '')); ?>">
                </div>
                <div class="ece-field">
                    <label>İkincil buton metni (örn. Numune İste)</label>
                    <input type="text" name="ece[cta_sample_text]" value="<?php echo esc_attr(ece_home_get('cta_sample_text', 'Numune İste')); ?>">
                </div>
                <div class="ece-field">
                    <label>İkincil buton linki</label>
                    <input type="text" name="ece[cta_sample_url]" value="<?php echo esc_attr(ece_home_get('cta_sample_url', '#')); ?>" placeholder="# veya https://...">
                </div>
            </div>
        </div>

        <!-- TAB: TEKNİK ÖZELLİKLER -->
        <div class="ece-tab-content <?php echo ($active_tab === 'specs') ? 'ece-tab-content--active' : ''; ?>" id="tab-specs">
            <?php ece_render_section_status('home', 'specs'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Teknik Özellikler — Başlık alanı</div>
                <div class="ece-field">
                    <label>Üst etiket (eyebrow)</label>
                    <input type="text" name="ece[specs_eyebrow]" value="<?php echo esc_attr(ece_home_get('specs_eyebrow', 'Teknik Detaylar')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ana başlık (HTML kullanılabilir, örn. Teknik &lt;em&gt;Özellikler&lt;/em&gt;)</label>
                    <input type="text" name="ece[specs_title]" value="<?php echo esc_attr(ece_home_get('specs_title', 'Teknik Özellikler')); ?>">
                </div>
                <div class="ece-field">
                    <label>Alt metin (açıklama)</label>
                    <textarea name="ece[specs_subtitle]" rows="3"><?php echo esc_textarea(ece_home_get('specs_subtitle', 'ECONYL® sertifikalı malzemeler ve CNC lazer teknolojisi ile üretilen premium paspaslarımızın tüm teknik detaylarını keşfedin.')); ?></textarea>
                </div>
            </div>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary">Kaydet</button>
        </p>
    </form>
    <script>
    (function(){
        var tabs = document.querySelectorAll('.ece-tab-content[id^="tab-"]');
        document.querySelectorAll('.ece-tabs .ece-tab').forEach(function(btn){
            btn.addEventListener('click', function(){
                var t = this.getAttribute('data-tab');
                document.getElementById('eceHomeActiveTab').value = t;
                tabs.forEach(function(c){ c.classList.remove('ece-tab-content--active'); });
                var el = document.getElementById('tab-' + t);
                if (el) el.classList.add('ece-tab-content--active');
                document.querySelectorAll('.ece-tabs .ece-tab').forEach(function(b){ b.classList.remove('ece-tab--active'); });
                this.classList.add('ece-tab--active');
            });
        });
        function toggleHeroBgFields() {
            var sel = document.getElementById('eceHeroBgType');
            var imgRow = document.querySelector('.ece-hero-bg-image-field');
            var vidRow = document.querySelector('.ece-hero-bg-video-field');
            if (!sel || !imgRow || !vidRow) return;
            var v = sel.value;
            imgRow.style.display = v === 'image' ? '' : 'none';
            vidRow.style.display = v === 'video' ? '' : 'none';
        }
        var heroBgSel = document.getElementById('eceHeroBgType');
        if (heroBgSel) {
            heroBgSel.addEventListener('change', toggleHeroBgFields);
            toggleHeroBgFields();
        }
    })();
    </script>
    <?php
}
