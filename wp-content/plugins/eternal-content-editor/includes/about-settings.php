<?php
/**
 * Hakkımızda sayfası — Next Content ile yönetim
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('ece_about_get')) {
    /**
     * Hakkımızda ayarını getirir.
     *
     * @param string $key Option key (eternal_about_ öneki otomatik eklenir)
     * @param string $default Varsayılan değer
     * @return string|array
     */
    function ece_about_get($key, $default = '')
    {
        return get_option('eternal_about_' . $key, $default);
    }
}

function ece_about_settings_page()
{
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'hero';
    $tabs = [
        'hero'    => ['label' => 'Hero', 'icon' => 'fas fa-image'],
        'stats'   => ['label' => 'İstatistikler', 'icon' => 'fas fa-chart-bar'],
        'story'   => ['label' => 'Hikaye', 'icon' => 'fas fa-book'],
        'values'  => ['label' => 'Değerler', 'icon' => 'fas fa-star'],
        'timeline'=> ['label' => 'Zaman Çizelgesi', 'icon' => 'fas fa-history'],
        'team'    => ['label' => 'Ekip', 'icon' => 'fas fa-users'],
        'cta'     => ['label' => 'CTA (Çağrı)', 'icon' => 'fas fa-hand-point-right'],
    ];
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="eceAboutForm" novalidate>
        <input type="hidden" name="action" value="ece_save_about">
        <?php wp_nonce_field('ece_save_about_action', 'ece_about_nonce'); ?>
        <input type="hidden" name="ece_save_about" value="1">
        <input type="hidden" name="ece_active_tab" value="<?php echo esc_attr($active_tab); ?>" id="eceAboutActiveTab">

        <div class="ece-submit-bar ece-submit-bar--sticky">
            <button type="submit" class="button button-primary button-hero">
                <i class="fas fa-save"></i> Ayarları Kaydet
            </button>
        </div>

        <div class="ece-page-header">
            <i class="fas fa-info-circle"></i>
            <span>Next Content — Hakkımızda Sayfası</span>
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
                <div class="ece-card-title">Hero — Üst alan</div>
                <div class="ece-field">
                    <label>Üst etiket (eyebrow)</label>
                    <input type="text" name="ece[hero_eyebrow]" value="<?php echo esc_attr(ece_about_get('hero_eyebrow', 'Hakkımızda')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ana başlık (HTML: &lt;em&gt;italik&lt;/em&gt;, &lt;br&gt; satır sonu)</label>
                    <textarea name="ece[hero_heading]" rows="3"><?php echo esc_textarea(ece_about_get('hero_heading', "30 Yıllık <em>Tutku</em> ile<br>Üretim Mükemmelliği")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Açıklama metni</label>
                    <textarea name="ece[hero_desc]" rows="3"><?php echo esc_textarea(ece_about_get('hero_desc', "1994'ten bu yana, ECONYL® sertifikalı malzemeler ve CNC lazer teknolojisi ile dünyanın dört bir yanına premium halı ve paspas üretiyoruz.")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Scroll göstergesi metni</label>
                    <input type="text" name="ece[hero_scroll_text]" value="<?php echo esc_attr(ece_about_get('hero_scroll_text', 'Keşfet')); ?>">
                </div>
            </div>
        </div>

        <!-- TAB: STATS -->
        <div class="ece-tab-content <?php echo ($active_tab === 'stats') ? 'ece-tab-content--active' : ''; ?>" id="tab-stats">
            <div class="ece-card">
                <div class="ece-card-title">İstatistik çubuğu — 4 kutu</div>
                <?php
                $stat_defaults = [
                    ['number' => '30', 'suffix' => '+', 'label' => 'Yıllık Deneyim'],
                    ['number' => '500', 'suffix' => '+', 'label' => 'Otel Partneri'],
                    ['number' => '40', 'suffix' => '+', 'label' => 'Ülkeye Teslimat'],
                    ['number' => '10000', 'suffix' => '+', 'label' => 'Tamamlanan Proje'],
                ];
                for ($i = 1; $i <= 4; $i++):
                    $d = $stat_defaults[$i - 1];
                ?>
                <div class="ece-field">
                    <label>İstatistik <?php echo $i; ?> — Sayı (sayaç hedefi)</label>
                    <input type="text" name="ece[stat<?php echo $i; ?>_number]" value="<?php echo esc_attr(ece_about_get('stat' . $i . '_number', $d['number'])); ?>">
                </div>
                <div class="ece-field">
                    <label>İstatistik <?php echo $i; ?> — Sonek (örn. +)</label>
                    <input type="text" name="ece[stat<?php echo $i; ?>_suffix]" value="<?php echo esc_attr(ece_about_get('stat' . $i . '_suffix', $d['suffix'])); ?>">
                </div>
                <div class="ece-field">
                    <label>İstatistik <?php echo $i; ?> — Etiket</label>
                    <input type="text" name="ece[stat<?php echo $i; ?>_label]" value="<?php echo esc_attr(ece_about_get('stat' . $i . '_label', $d['label'])); ?>">
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- TAB: STORY -->
        <div class="ece-tab-content <?php echo ($active_tab === 'story') ? 'ece-tab-content--active' : ''; ?>" id="tab-story">
            <div class="ece-card">
                <div class="ece-card-title">Hikaye — Metin bloğu</div>
                <div class="ece-field">
                    <label>Üst etiket (eyebrow)</label>
                    <input type="text" name="ece[story_eyebrow]" value="<?php echo esc_attr(ece_about_get('story_eyebrow', 'Hikayemiz')); ?>">
                </div>
                <div class="ece-field">
                    <label>Başlık (HTML: &lt;em&gt; kullanılabilir)</label>
                    <textarea name="ece[story_heading]" rows="2"><?php echo esc_textarea(ece_about_get('story_heading', "Bir Atölyeden<br><em>Global Markaya</em>")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Paragraf 1</label>
                    <textarea name="ece[story_p1]" rows="3"><?php echo esc_textarea(ece_about_get('story_p1', "1994 yılında İstanbul'da küçük bir atölyede başlayan yolculuğumuz, bugün 40'tan fazla ülkeye ihracat yapan, sektörün lider markalarından biri haline gelmiştir.")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Paragraf 2</label>
                    <textarea name="ece[story_p2]" rows="3"><?php echo esc_textarea(ece_about_get('story_p2', "Her bir ürünümüz, ECONYL® sertifikalı %100 poliamid elyaf ve son teknoloji CNC lazer kesim ile üretilmektedir. Kalite kontrol süreçlerimiz EN ISO 10874 ve EN 13501-1 standartlarına tam uygunluk sağlar.")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Paragraf 3</label>
                    <textarea name="ece[story_p3]" rows="3"><?php echo esc_textarea(ece_about_get('story_p3', "Otellerden kurumsal ofislere, restoranlardan konutlara kadar her mekâna özel çözümler sunuyor, müşterilerimizin hayallerini gerçeğe dönüştürüyoruz.")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Sağ alt kutu — Sayı</label>
                    <input type="text" name="ece[story_float_num]" value="<?php echo esc_attr(ece_about_get('story_float_num', '30')); ?>">
                </div>
                <div class="ece-field">
                    <label>Sağ alt kutu — Metin</label>
                    <input type="text" name="ece[story_float_text]" value="<?php echo esc_attr(ece_about_get('story_float_text', 'Yıllık Tecrübe')); ?>">
                </div>
                <div class="ece-field">
                    <label>İmza — İsim / unvan</label>
                    <input type="text" name="ece[story_signature_name]" value="<?php echo esc_attr(ece_about_get('story_signature_name', 'Kurucu & CEO')); ?>">
                </div>
                <div class="ece-field">
                    <label>İmza — Alt satır</label>
                    <input type="text" name="ece[story_signature_role]" value="<?php echo esc_attr(ece_about_get('story_signature_role', 'Entry Mark Carpets')); ?>">
                </div>
            </div>
        </div>

        <!-- TAB: VALUES -->
        <div class="ece-tab-content <?php echo ($active_tab === 'values') ? 'ece-tab-content--active' : ''; ?>" id="tab-values">
            <div class="ece-card">
                <div class="ece-card-title">Değerler — Başlık alanı</div>
                <div class="ece-field">
                    <label>Üst etiket</label>
                    <input type="text" name="ece[values_eyebrow]" value="<?php echo esc_attr(ece_about_get('values_eyebrow', 'Değerlerimiz')); ?>">
                </div>
                <div class="ece-field">
                    <label>Başlık (HTML: &lt;em&gt; kullanılabilir)</label>
                    <input type="text" name="ece[values_title]" value="<?php echo esc_attr(ece_about_get('values_title', 'Bizi Biz Yapan <em>Değerler</em>')); ?>">
                </div>
                <div class="ece-field">
                    <label>Alt metin</label>
                    <input type="text" name="ece[values_subtitle]" value="<?php echo esc_attr(ece_about_get('values_subtitle', 'Her ürünümüzde bu değerleri yaşatıyor, müşterilerimize en iyisini sunuyoruz.')); ?>">
                </div>
                <div class="ece-card-title">4 değer kartı</div>
                <?php
                $value_defaults = [
                    ['title' => 'Kalite', 'desc' => 'ECONYL® sertifikalı malzemeler, CE belgesi ve uluslararası standartlara tam uygunluk ile üstün kalite garantisi.'],
                    ['title' => 'İnovasyon', 'desc' => 'CNC lazer teknolojisi, online tasarım stüdyosu ve sürekli AR-GE yatırımı ile sektörün öncüsü olmaya devam ediyoruz.'],
                    ['title' => 'Sürdürülebilirlik', 'desc' => 'Geri dönüştürülmüş ECONYL® elyaf kullanımı, çevre dostu üretim süreçleri ve karbon ayak izi azaltma hedeflerimiz.'],
                    ['title' => 'Müşteri Odaklılık', 'desc' => 'Ücretsiz tasarımcı desteği, 24 saat onay süreci ve kişiye özel çözümler ile tam memnuniyet odaklı hizmet.'],
                ];
                for ($i = 1; $i <= 4; $i++):
                    $vd = $value_defaults[$i - 1];
                ?>
                <div class="ece-field">
                    <label>Değer <?php echo $i; ?> — Başlık</label>
                    <input type="text" name="ece[value<?php echo $i; ?>_title]" value="<?php echo esc_attr(ece_about_get('value' . $i . '_title', $vd['title'])); ?>">
                </div>
                <div class="ece-field">
                    <label>Değer <?php echo $i; ?> — Açıklama</label>
                    <textarea name="ece[value<?php echo $i; ?>_desc]" rows="2"><?php echo esc_textarea(ece_about_get('value' . $i . '_desc', $vd['desc'])); ?></textarea>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- TAB: TIMELINE -->
        <div class="ece-tab-content <?php echo ($active_tab === 'timeline') ? 'ece-tab-content--active' : ''; ?>" id="tab-timeline">
            <div class="ece-card">
                <div class="ece-card-title">Zaman çizelgesi — Başlık</div>
                <div class="ece-field">
                    <label>Üst etiket</label>
                    <input type="text" name="ece[timeline_eyebrow]" value="<?php echo esc_attr(ece_about_get('timeline_eyebrow', 'Kilometre Taşlarımız')); ?>">
                </div>
                <div class="ece-field">
                    <label>Başlık (HTML: &lt;em&gt; kullanılabilir)</label>
                    <input type="text" name="ece[timeline_title]" value="<?php echo esc_attr(ece_about_get('timeline_title', 'Yolculuğumuzun <em>Hikayesi</em>')); ?>">
                </div>
                <div class="ece-card-title">6 zaman dilimi</div>
                <?php
                $timeline_defaults = [
                    ['year' => '1994', 'title' => 'Kuruluş', 'desc' => "İstanbul'da küçük bir atölyede halı üretimine başlandı. İlk yılda 500 adet paspas üretildi."],
                    ['year' => '2003', 'title' => 'İlk İhracat', 'desc' => "Avrupa pazarına ilk ihracat gerçekleştirildi. Almanya ve İngiltere'ye düzenli sevkiyat başladı."],
                    ['year' => '2010', 'title' => 'CE Sertifikası', 'desc' => 'Avrupa Birliği CE sertifikası alınarak uluslararası kalite standartlarına tam uygunluk sağlandı.'],
                    ['year' => '2016', 'title' => 'ECONYL® Partnerliği', 'desc' => 'ECONYL® markası ile partnerlik anlaşması imzalandı. %100 geri dönüştürülmüş elyaf kullanımına geçildi.'],
                    ['year' => '2020', 'title' => 'CNC Lazer Teknolojisi', 'desc' => 'Son teknoloji CNC lazer kesim sistemleri devreye alınarak üretim kalitesi ve hızı artırıldı.'],
                    ['year' => '2024', 'title' => 'Online Tasarım Stüdyosu', 'desc' => 'Müşterilerin kendi tasarımlarını oluşturabildiği online interaktif tasarım platformu hizmete açıldı.'],
                ];
                for ($i = 1; $i <= 6; $i++):
                    $td = $timeline_defaults[$i - 1];
                ?>
                <div class="ece-field">
                    <label>Öğe <?php echo $i; ?> — Yıl</label>
                    <input type="text" name="ece[timeline_<?php echo $i; ?>_year]" value="<?php echo esc_attr(ece_about_get('timeline_' . $i . '_year', $td['year'])); ?>">
                </div>
                <div class="ece-field">
                    <label>Öğe <?php echo $i; ?> — Başlık</label>
                    <input type="text" name="ece[timeline_<?php echo $i; ?>_title]" value="<?php echo esc_attr(ece_about_get('timeline_' . $i . '_title', $td['title'])); ?>">
                </div>
                <div class="ece-field">
                    <label>Öğe <?php echo $i; ?> — Açıklama</label>
                    <textarea name="ece[timeline_<?php echo $i; ?>_desc]" rows="2"><?php echo esc_textarea(ece_about_get('timeline_' . $i . '_desc', $td['desc'])); ?></textarea>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- TAB: TEAM -->
        <div class="ece-tab-content <?php echo ($active_tab === 'team') ? 'ece-tab-content--active' : ''; ?>" id="tab-team">
            <div class="ece-card">
                <div class="ece-card-title">Ekip — Başlık alanı</div>
                <div class="ece-field">
                    <label>Üst etiket</label>
                    <input type="text" name="ece[team_eyebrow]" value="<?php echo esc_attr(ece_about_get('team_eyebrow', 'Ekibimiz')); ?>">
                </div>
                <div class="ece-field">
                    <label>Başlık (HTML: &lt;em&gt; kullanılabilir)</label>
                    <input type="text" name="ece[team_title]" value="<?php echo esc_attr(ece_about_get('team_title', 'Uzman <em>Kadromuz</em>')); ?>">
                </div>
                <div class="ece-field">
                    <label>Alt metin</label>
                    <input type="text" name="ece[team_subtitle]" value="<?php echo esc_attr(ece_about_get('team_subtitle', 'Alanında deneyimli profesyonellerden oluşan ekibimizle hizmetinizdeyiz.')); ?>">
                </div>
                <div class="ece-card-title">4 ekip üyesi</div>
                <?php
                $team_defaults = [
                    ['name' => 'Ahmet Yılmaz', 'role' => 'Kurucu & CEO', 'bio' => '30 yıllık sektör deneyimi ile şirketi global bir markaya dönüştürdü.', 'initials' => 'AY'],
                    ['name' => 'Elif Kaya', 'role' => 'Tasarım Direktörü', 'bio' => 'Modern ve geleneksel motifleri birleştiren koleksiyonların yaratıcısı.', 'initials' => 'EK'],
                    ['name' => 'Mehmet Demir', 'role' => 'Üretim Müdürü', 'bio' => 'CNC lazer sistemleri ve kalite kontrol süreçlerinin yöneticisi.', 'initials' => 'MD'],
                    ['name' => 'Zeynep Öztürk', 'role' => 'İhracat Direktörü', 'bio' => '40+ ülkeye ihracat operasyonları ve uluslararası müşteri ilişkileri.', 'initials' => 'ZÖ'],
                ];
                for ($i = 1; $i <= 4; $i++):
                    $tm = $team_defaults[$i - 1];
                ?>
                <div class="ece-field">
                    <label>Üye <?php echo $i; ?> — Ad Soyad</label>
                    <input type="text" name="ece[team_<?php echo $i; ?>_name]" value="<?php echo esc_attr(ece_about_get('team_' . $i . '_name', $tm['name'])); ?>">
                </div>
                <div class="ece-field">
                    <label>Üye <?php echo $i; ?> — Unvan</label>
                    <input type="text" name="ece[team_<?php echo $i; ?>_role]" value="<?php echo esc_attr(ece_about_get('team_' . $i . '_role', $tm['role'])); ?>">
                </div>
                <div class="ece-field">
                    <label>Üye <?php echo $i; ?> — Kısa biyografi</label>
                    <textarea name="ece[team_<?php echo $i; ?>_bio]" rows="2"><?php echo esc_textarea(ece_about_get('team_' . $i . '_bio', $tm['bio'])); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Üye <?php echo $i; ?> — Avatar baş harfleri (örn. AY)</label>
                    <input type="text" name="ece[team_<?php echo $i; ?>_initials]" value="<?php echo esc_attr(ece_about_get('team_' . $i . '_initials', $tm['initials'])); ?>" maxlength="4">
                </div>
                <div class="ece-field">
                    <label>Üye <?php echo $i; ?> — LinkedIn URL (boş bırakılabilir)</label>
                    <input type="text" name="ece[team_<?php echo $i; ?>_linkedin]" value="<?php echo esc_attr(ece_about_get('team_' . $i . '_linkedin', '#')); ?>">
                </div>
                <div class="ece-field">
                    <label>Üye <?php echo $i; ?> — Twitter/X URL (boş bırakılabilir)</label>
                    <input type="text" name="ece[team_<?php echo $i; ?>_twitter]" value="<?php echo esc_attr(ece_about_get('team_' . $i . '_twitter', '')); ?>">
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- TAB: CTA -->
        <div class="ece-tab-content <?php echo ($active_tab === 'cta') ? 'ece-tab-content--active' : ''; ?>" id="tab-cta">
            <div class="ece-card">
                <div class="ece-card-title">CTA — Alt çağrı alanı</div>
                <div class="ece-field">
                    <label>Başlık (HTML: &lt;em&gt; kullanılabilir)</label>
                    <input type="text" name="ece[cta_title]" value="<?php echo esc_attr(ece_about_get('cta_title', 'Hayalinizdeki Paspası <em>Birlikte</em> Tasarlayalım')); ?>">
                </div>
                <div class="ece-field">
                    <label>Açıklama</label>
                    <textarea name="ece[cta_desc]" rows="2"><?php echo esc_textarea(ece_about_get('cta_desc', 'Online tasarım stüdyomuz ile logonuzu, renginizi ve ölçünüzü seçin. Uzman ekibimiz size destek olsun.')); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Birincil buton metni</label>
                    <input type="text" name="ece[cta_btn_primary_text]" value="<?php echo esc_attr(ece_about_get('cta_btn_primary_text', 'Tasarlamaya Başla')); ?>">
                </div>
                <div class="ece-field">
                    <label>Birincil buton linki (URL)</label>
                    <input type="text" name="ece[cta_btn_primary_url]" value="<?php echo esc_attr(ece_about_get('cta_btn_primary_url', '')); ?>" placeholder="<?php echo esc_attr(home_url('/')); ?>">
                </div>
                <div class="ece-field">
                    <label>İkincil buton metni</label>
                    <input type="text" name="ece[cta_btn_secondary_text]" value="<?php echo esc_attr(ece_about_get('cta_btn_secondary_text', 'Bize Ulaşın')); ?>">
                </div>
                <div class="ece-field">
                    <label>İkincil buton linki (URL)</label>
                    <input type="text" name="ece[cta_btn_secondary_url]" value="<?php echo esc_attr(ece_about_get('cta_btn_secondary_url', '')); ?>" placeholder="<?php echo esc_attr(home_url('/iletisim')); ?>">
                </div>
            </div>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary">Kaydet</button>
        </p>
    </form>
    <script>
    (function(){
        var form = document.getElementById('eceAboutForm');
        if (!form) return;
        var tabs = form.querySelectorAll('.ece-tab-content[id^="tab-"]');
        form.querySelectorAll('.ece-tabs .ece-tab').forEach(function(btn){
            btn.addEventListener('click', function(){
                var t = this.getAttribute('data-tab');
                var input = document.getElementById('eceAboutActiveTab');
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
