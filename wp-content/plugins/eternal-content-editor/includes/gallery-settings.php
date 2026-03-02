<?php
/**
 * Galeri sayfası — Next Content ile yönetim (tüm bölümler)
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('ece_gallery_get')) {
    /**
     * Galeri ayarını getirir.
     *
     * @param string $key Option key (eternal_gallery_ öneki otomatik eklenir)
     * @param string $default Varsayılan değer
     * @return string|array
     */
    function ece_gallery_get($key, $default = '')
    {
        return get_option('eternal_gallery_' . $key, $default);
    }
}

/**
 * Kayıtlı galeri kategorilerini (filtreler) dizi olarak döndürür.
 * İlk öğe her zaman "Tümü"|all, sonrakiler Etiket|slug.
 *
 * @return array [ ['label' => '...', 'slug' => '...'], ... ]
 */
function ece_gallery_get_categories()
{
    $raw = ece_gallery_get('filters_items', "Tümü|all\nOtel|hotel\nOfis|office\nÖzel Tasarım|custom\nKonut|residential");
    $rows = array_filter(array_map('trim', explode("\n", $raw)));
    $out = [];
    foreach ($rows as $row) {
        $parts = array_map('trim', explode('|', $row, 2));
        if (count($parts) >= 2) {
            $out[] = ['label' => $parts[0], 'slug' => $parts[1]];
        }
    }
    if (empty($out)) {
        return [
            ['label' => 'Tümü', 'slug' => 'all'],
            ['label' => 'Otel', 'slug' => 'hotel'],
            ['label' => 'Ofis', 'slug' => 'office'],
            ['label' => 'Özel Tasarım', 'slug' => 'custom'],
            ['label' => 'Konut', 'slug' => 'residential'],
        ];
    }
    return $out;
}

/**
 * Kayıtlı galeri ürünlerini dizi olarak döndürür.
 *
 * @return array
 */
function ece_gallery_get_products()
{
    $json = ece_gallery_get('products_json', '');
    if (empty($json)) {
        return [];
    }
    $decoded = json_decode($json, true);
    return is_array($decoded) ? $decoded : [];
}

/**
 * Tek ürün için form alanları (row HTML).
 *
 * @param array $p     Ürün verisi (boş ise varsayılanlar)
 * @param int   $index Sıra no
 */
function ece_gallery_render_product_row($p, $index)
{
    $p = wp_parse_args($p, [
        'title' => '', 'collection' => '', 'category' => 'custom', 'color' => '#2D3748',
        'colors' => '', 'material' => '', 'size' => '', 'thickness' => '', 'price' => '',
        'desc' => '', 'badge' => '', 'layout' => 'normal',
    ]);
    if (is_array($p['colors'])) {
        $p['colors'] = implode(', ', $p['colors']);
    }
    $name = 'ece[products][' . $index . ']';
    ?>
    <div class="ece-product-row" data-index="<?php echo (int) $index; ?>">
        <div class="ece-product-row-header">
            <strong>Ürün #<span class="ece-product-num"><?php echo (int) $index + 1; ?></span></strong>
            <button type="button" class="button button-small ece-product-remove" aria-label="Ürünü kaldır">Kaldır</button>
        </div>
        <div class="ece-product-fields">
            <div class="ece-field">
                <label>Ürün adı</label>
                <input type="text" name="<?php echo esc_attr($name); ?>[title]" value="<?php echo esc_attr($p['title']); ?>" placeholder="Örn: Aegean Luxe Ocean">
            </div>
            <div class="ece-field">
                <label>Koleksiyon adı</label>
                <input type="text" name="<?php echo esc_attr($name); ?>[collection]" value="<?php echo esc_attr($p['collection']); ?>" placeholder="Örn: Aegean Luxe Series">
            </div>
            <div class="ece-field">
                <label>Kategori</label>
                <select name="<?php echo esc_attr($name); ?>[category]">
                    <option value="hotel" <?php selected($p['category'], 'hotel'); ?>>Otel</option>
                    <option value="office" <?php selected($p['category'], 'office'); ?>>Ofis</option>
                    <option value="custom" <?php selected($p['category'], 'custom'); ?>>Özel Tasarım</option>
                    <option value="residential" <?php selected($p['category'], 'residential'); ?>>Konut</option>
                </select>
            </div>
            <div class="ece-field">
                <label>Ana renk (hex)</label>
                <input type="text" name="<?php echo esc_attr($name); ?>[color]" value="<?php echo esc_attr($p['color']); ?>" placeholder="#1B4F8A">
            </div>
            <div class="ece-field">
                <label>Renk seçenekleri (virgülle ayırın, hex)</label>
                <input type="text" name="<?php echo esc_attr($name); ?>[colors]" value="<?php echo esc_attr($p['colors']); ?>" placeholder="#1B4F8A, #0D47A1, #2D3748">
            </div>
            <div class="ece-field">
                <label>Malzeme</label>
                <input type="text" name="<?php echo esc_attr($name); ?>[material]" value="<?php echo esc_attr($p['material']); ?>" placeholder="Yün Karışım">
            </div>
            <div class="ece-field">
                <label>Boyut</label>
                <input type="text" name="<?php echo esc_attr($name); ?>[size]" value="<?php echo esc_attr($p['size']); ?>" placeholder="200 × 300 cm">
            </div>
            <div class="ece-field">
                <label>Kalınlık</label>
                <input type="text" name="<?php echo esc_attr($name); ?>[thickness]" value="<?php echo esc_attr($p['thickness']); ?>" placeholder="14mm">
            </div>
            <div class="ece-field">
                <label>Fiyat</label>
                <input type="text" name="<?php echo esc_attr($name); ?>[price]" value="<?php echo esc_attr($p['price']); ?>" placeholder="₺4.250">
            </div>
            <div class="ece-field ece-field-full">
                <label>Açıklama</label>
                <textarea name="<?php echo esc_attr($name); ?>[desc]" rows="3" placeholder="Kısa ürün açıklaması"><?php echo esc_textarea($p['desc']); ?></textarea>
            </div>
            <div class="ece-field">
                <label>Rozet</label>
                <select name="<?php echo esc_attr($name); ?>[badge]">
                    <option value="" <?php selected($p['badge'], ''); ?>>Yok</option>
                    <option value="new" <?php selected($p['badge'], 'new'); ?>>Yeni</option>
                    <option value="popular" <?php selected($p['badge'], 'popular'); ?>>Popüler</option>
                    <option value="sale" <?php selected($p['badge'], 'sale'); ?>>İndirim</option>
                </select>
            </div>
            <div class="ece-field">
                <label>Kart düzeni</label>
                <select name="<?php echo esc_attr($name); ?>[layout]">
                    <option value="normal" <?php selected($p['layout'], 'normal'); ?>>Normal</option>
                    <option value="tall" <?php selected($p['layout'], 'tall'); ?>>Dikey (tall)</option>
                    <option value="wide" <?php selected($p['layout'], 'wide'); ?>>Yatay (wide)</option>
                    <option value="featured" <?php selected($p['layout'], 'featured'); ?>>Öne çıkan (featured)</option>
                </select>
            </div>
        </div>
    </div>
    <?php
}

function ece_gallery_settings_page()
{
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'hero';
    $tabs = [
        'hero'     => ['label' => 'Hero (Üst Alan)', 'icon' => 'fas fa-image'],
        'products' => ['label' => 'Ürünler', 'icon' => 'fas fa-box-open'],
        'filters'  => ['label' => 'Filtreler', 'icon' => 'fas fa-filter'],
        'grid'     => ['label' => 'Galeri / Daha Fazla', 'icon' => 'fas fa-th'],
        'lightbox' => ['label' => 'Lightbox', 'icon' => 'fas fa-expand'],
    ];
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="eceGalleryForm" novalidate>
        <input type="hidden" name="action" value="ece_save_gallery">
        <?php wp_nonce_field('ece_save_gallery_action', 'ece_gallery_nonce'); ?>
        <input type="hidden" name="ece_save_gallery" value="1">
        <input type="hidden" name="ece_active_tab" value="<?php echo esc_attr($active_tab); ?>" id="eceGalleryActiveTab">

        <div class="ece-submit-bar ece-submit-bar--sticky">
            <button type="submit" class="button button-primary button-hero">
                <i class="fas fa-save"></i> Ayarları Kaydet
            </button>
        </div>

        <div class="ece-page-header">
            <i class="fas fa-images"></i>
            <span>Next Content — Galeri Sayfası</span>
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
            <?php ece_render_section_status('gallery', 'hero'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Hero — Üst başlık alanı</div>
                <div class="ece-field">
                    <label>Üst etiket (eyebrow)</label>
                    <input type="text" name="ece[hero_eyebrow]" value="<?php echo esc_attr(ece_gallery_get('hero_eyebrow', 'Koleksiyonlarımız')); ?>">
                </div>
                <div class="ece-field">
                    <label>Ana başlık (HTML: &lt;em&gt;, &lt;br&gt; kullanılabilir)</label>
                    <textarea name="ece[hero_heading]" rows="2"><?php echo esc_textarea(ece_gallery_get('hero_heading', "İlham Veren<br><em>Tasarımlar</em>")); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Açıklama (alt metin)</label>
                    <textarea name="ece[hero_subtitle]" rows="3"><?php echo esc_textarea(ece_gallery_get('hero_subtitle', 'Otellerden kurumsal ofislere, restoranlardan konutlara — her mekâna özel üretilen premium halı ve paspas koleksiyonlarımızı keşfedin.')); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Özelleştirici sayfası URL (boş bırakılırsa varsayılan /paspas-ozellestir/ kullanılır)</label>
                    <input type="url" name="ece[hero_customizer_url]" value="<?php echo esc_attr(ece_gallery_get('hero_customizer_url', '')); ?>" placeholder="<?php echo esc_attr(home_url('/paspas-ozellestir/')); ?>">
                </div>
            </div>
        </div>

        <!-- TAB: ÜRÜNLER -->
        <div class="ece-tab-content <?php echo ($active_tab === 'products') ? 'ece-tab-content--active' : ''; ?>" id="tab-products">
            <?php ece_render_section_status('gallery', 'products'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Galeri ürünleri</div>
                <p class="description" style="margin-bottom:1em;">Galeri sayfasında gösterilecek ürünleri aşağıya ekleyin. Her ürün için tüm alanları doldurun; sıralama yukarıdan aşağıya görünür.</p>
                <div id="ece-products-list">
                    <?php
                    $products = ece_gallery_get_products();
                    if (empty($products)) {
                        ece_gallery_render_product_row([], 0);
                    } else {
                        foreach ($products as $i => $p) {
                            ece_gallery_render_product_row($p, $i);
                        }
                    }
                    ?>
                </div>
                <div id="ece-products-pagination" class="ece-products-pagination" style="display:none;"></div>
                <p style="margin-top:16px;">
                    <button type="button" class="button button-primary" id="ece-add-product"><i class="fas fa-plus"></i> Ürün ekle</button>
                </p>
            </div>
            <script type="text/template" id="ece-product-row-tpl">
                <div class="ece-product-row" data-index="__INDEX__">
                    <div class="ece-product-row-header">
                        <strong>Ürün #<span class="ece-product-num">__NUM__</span></strong>
                        <button type="button" class="button button-small ece-product-remove" aria-label="Ürünü kaldır">Kaldır</button>
                    </div>
                    <div class="ece-product-fields">
                        <div class="ece-field">
                            <label>Ürün adı</label>
                            <input type="text" name="ece[products][__INDEX__][title]" value="" placeholder="Örn: Aegean Luxe Ocean">
                        </div>
                        <div class="ece-field">
                            <label>Koleksiyon adı</label>
                            <input type="text" name="ece[products][__INDEX__][collection]" value="" placeholder="Örn: Aegean Luxe Series">
                        </div>
                        <div class="ece-field">
                            <label>Kategori</label>
                            <select name="ece[products][__INDEX__][category]">
                                <option value="hotel">Otel</option>
                                <option value="office">Ofis</option>
                                <option value="custom" selected>Özel Tasarım</option>
                                <option value="residential">Konut</option>
                            </select>
                        </div>
                        <div class="ece-field">
                            <label>Ana renk (hex)</label>
                            <input type="text" name="ece[products][__INDEX__][color]" value="#2D3748" placeholder="#1B4F8A">
                        </div>
                        <div class="ece-field">
                            <label>Renk seçenekleri (virgülle ayırın, hex)</label>
                            <input type="text" name="ece[products][__INDEX__][colors]" value="" placeholder="#1B4F8A, #0D47A1, #2D3748">
                        </div>
                        <div class="ece-field">
                            <label>Malzeme</label>
                            <input type="text" name="ece[products][__INDEX__][material]" value="" placeholder="Yün Karışım">
                        </div>
                        <div class="ece-field">
                            <label>Boyut</label>
                            <input type="text" name="ece[products][__INDEX__][size]" value="" placeholder="200 × 300 cm">
                        </div>
                        <div class="ece-field">
                            <label>Kalınlık</label>
                            <input type="text" name="ece[products][__INDEX__][thickness]" value="" placeholder="14mm">
                        </div>
                        <div class="ece-field">
                            <label>Fiyat</label>
                            <input type="text" name="ece[products][__INDEX__][price]" value="" placeholder="₺4.250">
                        </div>
                        <div class="ece-field ece-field-full">
                            <label>Açıklama</label>
                            <textarea name="ece[products][__INDEX__][desc]" rows="3" placeholder="Kısa ürün açıklaması"></textarea>
                        </div>
                        <div class="ece-field">
                            <label>Rozet</label>
                            <select name="ece[products][__INDEX__][badge]">
                                <option value="" selected>Yok</option>
                                <option value="new">Yeni</option>
                                <option value="popular">Popüler</option>
                                <option value="sale">İndirim</option>
                            </select>
                        </div>
                        <div class="ece-field">
                            <label>Kart düzeni</label>
                            <select name="ece[products][__INDEX__][layout]">
                                <option value="normal" selected>Normal</option>
                                <option value="tall">Dikey (tall)</option>
                                <option value="wide">Yatay (wide)</option>
                                <option value="featured">Öne çıkan (featured)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </script>
        </div>

        <!-- TAB: FILTERS / KATEGORİLER -->
        <div class="ece-tab-content <?php echo ($active_tab === 'filters') ? 'ece-tab-content--active' : ''; ?>" id="tab-filters">
            <?php ece_render_section_status('gallery', 'filters'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Galeri kategorileri (filtre butonları)</div>
                <p class="description" style="margin-bottom:1em;">Galeri sayfasında filtre olarak görünecek kategoriler. İlk satır her zaman &quot;Tümü&quot; (tüm ürünleri gösterir). Ürün eklerken seçtiğiniz <strong>Kategori</strong> ile buradaki slug’lar eşleşmeli (örn. Otel → hotel, Ofis → office).</p>
                <div id="ece-categories-list">
                    <?php
                    $categories = ece_gallery_get_categories();
                    foreach ($categories as $i => $cat) {
                        $is_first = ($i === 0);
                        $name = 'ece[categories][' . $i . ']';
                    ?>
                    <div class="ece-category-row" data-index="<?php echo (int) $i; ?>">
                        <div class="ece-category-row-inner">
                            <div class="ece-field">
                                <label>Görünen ad</label>
                                <input type="text" name="<?php echo esc_attr($name); ?>[label]" value="<?php echo esc_attr($cat['label']); ?>" placeholder="<?php echo $is_first ? 'Tümü' : 'Örn: Otel'; ?>">
                            </div>
                            <div class="ece-field">
                                <label>Slug <span class="description">(İngilizce, boşluksuz; ürün kategorisi ile aynı olmalı)</span></label>
                                <input type="text" name="<?php echo esc_attr($name); ?>[slug]" value="<?php echo esc_attr($cat['slug']); ?>" placeholder="<?php echo $is_first ? 'all' : 'hotel'; ?>" <?php echo $is_first ? 'readonly' : ''; ?>>
                            </div>
                            <?php if (!$is_first): ?>
                            <div class="ece-field ece-field-actions">
                                <label>&nbsp;</label>
                                <button type="button" class="button ece-category-remove" aria-label="Kategoriyi kaldır">Kaldır</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <p style="margin-top:16px;">
                    <button type="button" class="button button-primary" id="ece-add-category"><i class="fas fa-plus"></i> Kategori ekle</button>
                </p>
            </div>
            <script type="text/template" id="ece-category-row-tpl">
                <div class="ece-category-row" data-index="__INDEX__">
                    <div class="ece-category-row-inner">
                        <div class="ece-field">
                            <label>Görünen ad</label>
                            <input type="text" name="ece[categories][__INDEX__][label]" value="" placeholder="Örn: Otel">
                        </div>
                        <div class="ece-field">
                            <label>Slug</label>
                            <input type="text" name="ece[categories][__INDEX__][slug]" value="" placeholder="hotel">
                        </div>
                        <div class="ece-field ece-field-actions">
                            <label>&nbsp;</label>
                            <button type="button" class="button ece-category-remove" aria-label="Kategoriyi kaldır">Kaldır</button>
                        </div>
                    </div>
                </div>
            </script>
        </div>

        <!-- TAB: GRID / LOAD MORE -->
        <div class="ece-tab-content <?php echo ($active_tab === 'grid') ? 'ece-tab-content--active' : ''; ?>" id="tab-grid">
            <?php ece_render_section_status('gallery', 'grid'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Galeri grid ve “Daha fazla” alanı</div>
                <div class="ece-field">
                    <label>İlk yüklemede gösterilecek ürün sayısı</label>
                    <input type="number" name="ece[grid_initial_count]" value="<?php echo esc_attr(ece_gallery_get('grid_initial_count', '8')); ?>" min="1" max="24">
                </div>
                <div class="ece-field">
                    <label>“Daha fazla” buton metni</label>
                    <input type="text" name="ece[grid_load_more_text]" value="<?php echo esc_attr(ece_gallery_get('grid_load_more_text', 'Daha Fazla Göster')); ?>">
                </div>
                <div class="ece-field">
                    <label>Sayaç metni (placeholder: <strong>%shown%</strong> = gösterilen, <strong>%total%</strong> = toplam)</label>
                    <input type="text" name="ece[grid_counter_text]" value="<?php echo esc_attr(ece_gallery_get('grid_counter_text', '%shown% / %total% ürün gösteriliyor')); ?>" placeholder="%shown% / %total% ürün gösteriliyor">
                </div>
            </div>
        </div>

        <!-- TAB: LIGHTBOX -->
        <div class="ece-tab-content <?php echo ($active_tab === 'lightbox') ? 'ece-tab-content--active' : ''; ?>" id="tab-lightbox">
            <?php ece_render_section_status('gallery', 'lightbox'); ?>
            <div class="ece-card">
                <div class="ece-card-title">Lightbox — Varsayılan etiket ve alan başlıkları</div>
                <div class="ece-field">
                    <label>Varsayılan koleksiyon etiketi (kategori eşleşmezse gösterilir)</label>
                    <input type="text" name="ece[lightbox_tag_default]" value="<?php echo esc_attr(ece_gallery_get('lightbox_tag_default', 'KOLEKSİYON')); ?>">
                </div>
                <div class="ece-field">
                    <label>Malzeme etiketi</label>
                    <input type="text" name="ece[lightbox_label_material]" value="<?php echo esc_attr(ece_gallery_get('lightbox_label_material', 'Malzeme')); ?>">
                </div>
                <div class="ece-field">
                    <label>Boyut etiketi</label>
                    <input type="text" name="ece[lightbox_label_size]" value="<?php echo esc_attr(ece_gallery_get('lightbox_label_size', 'Boyut')); ?>">
                </div>
                <div class="ece-field">
                    <label>Kalınlık etiketi</label>
                    <input type="text" name="ece[lightbox_label_thickness]" value="<?php echo esc_attr(ece_gallery_get('lightbox_label_thickness', 'Kalınlık')); ?>">
                </div>
                <div class="ece-field">
                    <label>Fiyat etiketi</label>
                    <input type="text" name="ece[lightbox_label_price]" value="<?php echo esc_attr(ece_gallery_get('lightbox_label_price', 'Fiyat')); ?>">
                </div>
                <div class="ece-field">
                    <label>“Özelleştir” buton metni</label>
                    <input type="text" name="ece[lightbox_btn_customize]" value="<?php echo esc_attr(ece_gallery_get('lightbox_btn_customize', 'Özelleştir')); ?>">
                </div>
            </div>
            <div class="ece-card">
                <div class="ece-card-title">Kategori → Lightbox üst etiket eşlemesi</div>
                <p class="description" style="margin-bottom:1em;">Her satır: <strong>slug|Etiket</strong> (örn. hotel|OTEL KOLEKSİYONU). Slug’lar filtre slug’ları ile aynı olmalı.</p>
                <div class="ece-field">
                    <label>Kategori etiketleri (her satır: slug|Lightbox’ta görünecek etiket)</label>
                    <textarea name="ece[lightbox_tag_by_category]" rows="6"><?php echo esc_textarea(ece_gallery_get('lightbox_tag_by_category', "hotel|OTEL KOLEKSİYONU\noffice|OFİS KOLEKSİYONU\ncustom|ÖZEL TASARIM\nresidential|KONUT KOLEKSİYONU")); ?></textarea>
                </div>
            </div>
            <div class="ece-card">
                <div class="ece-card-title">Rozet etiketleri (kart ve lightbox)</div>
                <p class="description" style="margin-bottom:1em;">Badge key’i → Görünen metin. Örn: new → Yeni</p>
                <div class="ece-field">
                    <label>new rozeti metni</label>
                    <input type="text" name="ece[badge_new]" value="<?php echo esc_attr(ece_gallery_get('badge_new', 'Yeni')); ?>">
                </div>
                <div class="ece-field">
                    <label>popular rozeti metni</label>
                    <input type="text" name="ece[badge_popular]" value="<?php echo esc_attr(ece_gallery_get('badge_popular', 'Popüler')); ?>">
                </div>
                <div class="ece-field">
                    <label>sale rozeti metni</label>
                    <input type="text" name="ece[badge_sale]" value="<?php echo esc_attr(ece_gallery_get('badge_sale', 'İndirim')); ?>">
                </div>
            </div>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary">Kaydet</button>
        </p>
    </form>
    <script>
    (function(){
        var form = document.getElementById('eceGalleryForm');
        if (!form) return;
        var tabs = form.querySelectorAll('.ece-tab-content[id^="tab-"]');
        form.querySelectorAll('.ece-tabs .ece-tab').forEach(function(btn){
            btn.addEventListener('click', function(){
                var t = this.getAttribute('data-tab');
                var input = document.getElementById('eceGalleryActiveTab');
                if (input) input.value = t;
                tabs.forEach(function(c){ c.classList.remove('ece-tab-content--active'); });
                var el = form.querySelector('#tab-' + t);
                if (el) el.classList.add('ece-tab-content--active');
                form.querySelectorAll('.ece-tabs .ece-tab').forEach(function(b){ b.classList.remove('ece-tab--active'); });
                this.classList.add('ece-tab--active');
            });
        });
        var productsList = document.getElementById('ece-products-list');
        var rowTpl = document.getElementById('ece-product-row-tpl');
        var addBtn = document.getElementById('ece-add-product');
        var paginationEl = document.getElementById('ece-products-pagination');
        var PER_PAGE = 10;
        var currentPage = 1;

        function getRows() { return productsList ? productsList.querySelectorAll('.ece-product-row') : []; }

        function showPage(page) {
            var rows = getRows();
            var total = rows.length;
            if (total <= PER_PAGE) {
                rows.forEach(function(r){ r.style.display = ''; });
                if (paginationEl) paginationEl.style.display = 'none';
                return;
            }
            currentPage = Math.max(1, Math.min(page, Math.ceil(total / PER_PAGE)));
            var start = (currentPage - 1) * PER_PAGE;
            var end = start + PER_PAGE;
            rows.forEach(function(r, i){
                r.style.display = (i >= start && i < end) ? '' : 'none';
            });
            renderPagination();
            if (paginationEl) paginationEl.style.display = 'flex';
        }

        function renderPagination() {
            if (!paginationEl) return;
            var rows = getRows();
            var total = rows.length;
            var totalPages = Math.ceil(total / PER_PAGE) || 1;
            var start = (currentPage - 1) * PER_PAGE + 1;
            var end = Math.min(currentPage * PER_PAGE, total);
            paginationEl.innerHTML =
                '<div class="ece-products-pagination-nav">' +
                '<button type="button" class="button" id="ece-pagination-prev" ' + (currentPage <= 1 ? 'disabled' : '') + '>Önceki</button>' +
                '<span style="padding:0 8px;">Sayfa ' + currentPage + ' / ' + totalPages + '</span>' +
                '<button type="button" class="button" id="ece-pagination-next" ' + (currentPage >= totalPages ? 'disabled' : '') + '>Sonraki</button>' +
                '</div>' +
                '<span class="ece-products-pagination-info">' + total + ' ürün</span>';
            var prevBtn = document.getElementById('ece-pagination-prev');
            var nextBtn = document.getElementById('ece-pagination-next');
            if (prevBtn) prevBtn.addEventListener('click', function(){ showPage(currentPage - 1); });
            if (nextBtn) nextBtn.addEventListener('click', function(){ showPage(currentPage + 1); });
        }

        if (productsList && rowTpl && addBtn) {
            addBtn.addEventListener('click', function(){
                var rows = getRows();
                var nextIdx = rows.length;
                var html = rowTpl.textContent.replace(/__INDEX__/g, nextIdx).replace(/__NUM__/g, nextIdx + 1);
                var wrap = document.createElement('div');
                wrap.innerHTML = html.trim();
                var newRow = wrap.firstChild;
                productsList.appendChild(newRow);
                bindRemove(newRow);
                reindexRows();
                var newTotal = getRows().length;
                if (newTotal > PER_PAGE) {
                    currentPage = Math.ceil(newTotal / PER_PAGE);
                    showPage(currentPage);
                } else {
                    showPage(1);
                }
            });
            function bindRemove(row) {
                var btn = row.querySelector('.ece-product-remove');
                if (btn) btn.addEventListener('click', function(){
                    row.remove();
                    reindexRows();
                    var total = getRows().length;
                    var maxPage = Math.ceil(total / PER_PAGE) || 1;
                    showPage(Math.min(currentPage, maxPage));
                });
            }
            function reindexRows() {
                var rows = getRows();
                var prefix = 'ece[products][';
                rows.forEach(function(r, i){
                    r.dataset.index = i;
                    var numEl = r.querySelector('.ece-product-num');
                    if (numEl) numEl.textContent = i + 1;
                    r.querySelectorAll('input, select, textarea').forEach(function(el){
                        var n = el.name;
                        if (n && n.indexOf(prefix) === 0) {
                            var rest = n.slice(prefix.length);
                            var bracket = rest.indexOf(']');
                            if (bracket !== -1) el.name = prefix + i + ']' + rest.slice(bracket);
                        }
                    });
                });
            }
            productsList.querySelectorAll('.ece-product-row').forEach(bindRemove);
            showPage(1);
        }

        var categoriesList = document.getElementById('ece-categories-list');
        var categoryRowTpl = document.getElementById('ece-category-row-tpl');
        var addCategoryBtn = document.getElementById('ece-add-category');
        if (categoriesList && categoryRowTpl && addCategoryBtn) {
            addCategoryBtn.addEventListener('click', function(){
                var rows = categoriesList.querySelectorAll('.ece-category-row');
                var nextIdx = rows.length;
                var html = categoryRowTpl.textContent.replace(/__INDEX__/g, nextIdx);
                var wrap = document.createElement('div');
                wrap.innerHTML = html.trim();
                categoriesList.appendChild(wrap.firstChild);
                bindCategoryRemove(categoriesList.querySelectorAll('.ece-category-row')[nextIdx]);
            });
            function bindCategoryRemove(row) {
                if (!row || row.dataset.index === '0') return;
                var btn = row.querySelector('.ece-category-remove');
                if (btn) btn.addEventListener('click', function(){
                    row.remove();
                    reindexCategories();
                });
            }
            function reindexCategories() {
                var rows = categoriesList.querySelectorAll('.ece-category-row');
                var prefix = 'ece[categories][';
                rows.forEach(function(r, i){
                    r.dataset.index = i;
                    r.querySelectorAll('input').forEach(function(el){
                        var n = el.name;
                        if (n && n.indexOf(prefix) === 0) {
                            var rest = n.slice(prefix.length);
                            var bracket = rest.indexOf(']');
                            if (bracket !== -1) el.name = prefix + i + ']' + rest.slice(bracket);
                        }
                    });
                });
            }
            categoriesList.querySelectorAll('.ece-category-row').forEach(function(r){
                if (r.dataset.index !== '0') bindCategoryRemove(r);
            });
        }
    })();
    </script>
    <?php
}
