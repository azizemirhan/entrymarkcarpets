<?php
/**
 * General settings (Header & Footer)
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('ece_general_get')) {
    function ece_general_get($key, $default = '')
    {
        return get_option('eternal_general_' . $key, $default);
    }
}

function ece_general_settings_page()
{
    // Save logic is handled in admin_init
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'header';

    $tabs = [
        'header' => ['label' => 'Header (Üst Kısım)', 'icon' => 'fas fa-heading'],
        'footer' => ['label' => 'Footer (Alt Kısım)', 'icon' => 'fas fa-shoe-prints'],
    ];
    ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=eternal-content&ece_page=general')); ?>">
        <?php wp_nonce_field('ece_save_general_action', 'ece_general_nonce'); ?>
        <input type="hidden" name="ece_save_general" value="1">
        <input type="hidden" name="ece_active_tab" value="<?php echo esc_attr($active_tab); ?>" id="eceActiveTab">

        <div class="ece-page-header">
            <i class="fas fa-cogs"></i>
            <span>Next Content - Genel Ayarlar</span>
        </div>

        <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
            <div class="ece-notice ece-notice-success">
                <p>Ayarlar başarıyla kaydedildi.</p>
            </div>
        <?php endif; ?>

        <!-- TABS -->
        <div class="ece-tabs">
            <?php foreach ($tabs as $key => $tab): ?>
                <button type="button" class="ece-tab <?php echo ($active_tab === $key) ? 'ece-tab--active' : ''; ?>"
                    data-tab="<?php echo esc_attr($key); ?>">
                    <i class="<?php echo esc_attr($tab['icon']); ?>"></i>
                    <?php echo esc_html($tab['label']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- ===== TAB: HEADER ===== -->
        <div class="ece-tab-content <?php echo ($active_tab === 'header') ? 'ece-tab-content--active' : ''; ?>"
            id="tab-header">

            <p class="ece-help" style="margin-bottom:1.25rem;">Header'daki tüm içerik (logo, ticker, üst bar, sosyal medya) bu sekmeden yönetilir. Menüler <strong>Görünüm → Menüler</strong> sayfasından düzenlenir.</p>

            <!-- Logo -->
            <div class="ece-card">
                <div class="ece-card-title">Logo Ayarları</div>
                <div class="ece-field">
                    <label>Site Logosu</label>
                    <div class="ece-field-group">
                        <input type="text" name="ece[header_logo]"
                            value="<?php echo esc_attr(ece_general_get('header_logo')); ?>">
                        <button type="button" class="ece-upload-btn"><i class="fas fa-upload"></i> Yükle</button>
                    </div>
                    <div class="ece-image-preview">
                        <?php if (ece_general_get('header_logo')): ?>
                            <img src="<?php echo esc_url(ece_general_get('header_logo')); ?>" alt="Logo Preview">
                        <?php endif; ?>
                    </div>
                    <p class="ece-help">Önerilen boyut: 200x60px (PNG/SVG)</p>
                </div>
            </div>

            <!-- Ticker (kaydırma bandı) -->
            <div class="ece-card">
                <div class="ece-card-title">Ticker / Duyuru Bandı</div>
                <p class="ece-help">Header üstündeki kayan metinler. Her satır bir madde.</p>
                <?php
                $ticker_items = get_option('eternal_general_ticker_items', []);
                if (empty($ticker_items)) {
                    $ticker_items = [
                        ['text' => 'Free Shipping on Orders Over $500'],
                        ['text' => 'New Collection — Aegean Luxe Series Now Available'],
                        ['text' => 'Custom Design Lab — Create Your Own Carpet'],
                        ['text' => 'Trusted by 500+ Hotels Worldwide'],
                        ['text' => 'Premium Quality Since 1992'],
                        ['text' => 'Request a Free Sample Today'],
                    ];
                }
                $ticker_items = is_array($ticker_items) ? $ticker_items : [];
                ?>
                <div class="ece-repeater-container ece-ticker-items">
                    <?php foreach ($ticker_items as $tIdx => $ti): ?>
                    <div class="ece-repeater-item">
                        <span class="ece-repeater-num"><?php echo $tIdx + 1; ?></span>
                        <button type="button" class="ece-remove-repeater" title="Sil"><i class="fas fa-trash"></i> Sil</button>
                        <div class="ece-field">
                            <input type="text" name="ece[ticker_items][<?php echo $tIdx; ?>][text]"
                                value="<?php echo esc_attr($ti['text'] ?? ''); ?>"
                                placeholder="Örn: Free Shipping on Orders Over $500" style="width:100%;">
                        </div>
                        <p class="ece-help" style="margin-left:28px;">Vurgulamak istediğiniz kelimeleri <code>&lt;span class="ticker-gold"&gt;metin&lt;/span&gt;</code> ile sarabilirsiniz.</p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="ece-add-repeater"><i class="fas fa-plus"></i> Ticker metni ekle</button>
            </div>

            <!-- Üst Bar (sadece header'da görünen: e-posta + telefon) -->
            <div class="ece-card">
                <div class="ece-card-title">Üst Bar İletişim</div>
                <p class="ece-help">Header üst çizgide solda görünür.</p>
                <div class="ece-row">
                    <div class="ece-field">
                        <label>E-Posta</label>
                        <input type="email" name="ece[topbar_email]"
                            value="<?php echo esc_attr(ece_general_get('topbar_email', 'info@entrymarkcarpets.com')); ?>"
                            placeholder="info@site.com">
                    </div>
                    <div class="ece-field">
                        <label>Telefon</label>
                        <input type="text" name="ece[topbar_phone]"
                            value="<?php echo esc_attr(ece_general_get('topbar_phone', '+90 123 456 78 90')); ?>"
                            placeholder="+90 123 456 78 90">
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="ece-card">
                <div class="ece-card-title">Sosyal Medya</div>
                <p class="ece-help">Header üst bar sağda ve footer'da kullanılır.</p>
                <div class="ece-row">
                    <div class="ece-field">
                        <label><i class="fab fa-facebook"></i> Facebook</label>
                        <input type="text" name="ece[social_facebook]" placeholder="https://facebook.com/..."
                            value="<?php echo esc_attr(ece_general_get('social_facebook', '#')); ?>">
                    </div>
                    <div class="ece-field">
                        <label><i class="fab fa-instagram"></i> Instagram</label>
                        <input type="text" name="ece[social_instagram]" placeholder="https://instagram.com/..."
                            value="<?php echo esc_attr(ece_general_get('social_instagram', '#')); ?>">
                    </div>
                </div>
                <div class="ece-row">
                    <div class="ece-field">
                        <label><i class="fab fa-linkedin"></i> LinkedIn</label>
                        <input type="text" name="ece[social_linkedin]" placeholder="https://linkedin.com/..."
                            value="<?php echo esc_attr(ece_general_get('social_linkedin', '#')); ?>">
                    </div>
                    <div class="ece-field">
                        <label><i class="fab fa-pinterest"></i> Pinterest</label>
                        <input type="text" name="ece[social_pinterest]" placeholder="https://pinterest.com/..."
                            value="<?php echo esc_attr(ece_general_get('social_pinterest', '#')); ?>">
                    </div>
                </div>
            </div>

            <!-- Menüler (WordPress Menü sayfası) -->
            <div class="ece-card" style="border: 2px solid #2271b1; background: #f0f6fc;">
                <div class="ece-card-title" style="font-size: 1.1rem;"><i class="fas fa-bars"></i> Header ve Mega Menüler</div>
                <p class="ece-help">Tüm menüler (header, mega menü, footer) <strong>WordPress Menü sayfası</strong>ndan yönetilir. <strong>Primary Menu</strong> konumuna atadığınız menü header'da görünür; üst öğenin altına eklediğiniz öğeler mega menü sütunları olur. Sütun başlığı için menü öğesine CSS sınıfı olarak <code>mega-col-title</code> ekleyin.</p>
                <p><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="button button-primary" target="_blank"><i class="fas fa-external-link-alt"></i> Menüleri düzenle</a></p>
            </div>
        </div>

        <!-- ===== TAB: FOOTER ===== -->
        <div class="ece-tab-content <?php echo ($active_tab === 'footer') ? 'ece-tab-content--active' : ''; ?>"
            id="tab-footer">

            <p class="ece-help" style="margin-bottom:1.25rem;">Footer'daki tüm içerik bu sekmeden yönetilir. Sosyal medya linkleri <strong>Header</strong> sekmesinde; sütun menüleri <strong>Görünüm → Menüler</strong> (Footer Menu) ile düzenlenir.</p>

            <!-- Newsletter -->
            <div class="ece-card">
                <div class="ece-card-title">Newsletter Bölümü</div>
                <div class="ece-field">
                    <label>Başlık</label>
                    <input type="text" name="ece[newsletter_title]"
                        value="<?php echo esc_attr(ece_general_get('newsletter_title', 'Stay Inspired with')); ?>"
                        placeholder="Stay Inspired with">
                </div>
                <div class="ece-field">
                    <label>Vurgulu Kelime (başlıkta italik)</label>
                    <input type="text" name="ece[newsletter_title_em]"
                        value="<?php echo esc_attr(ece_general_get('newsletter_title_em', 'Entry Mark')); ?>"
                        placeholder="Entry Mark">
                </div>
                <div class="ece-field">
                    <label>Açıklama Metni</label>
                    <textarea name="ece[newsletter_desc]" rows="3" placeholder="Subscribe for exclusive access..."><?php echo esc_textarea(ece_general_get('newsletter_desc', 'Subscribe for exclusive access to new collections, designer insights, trade-only offers, and the latest in luxury flooring trends.')); ?></textarea>
                </div>
                <div class="ece-field">
                    <label>Buton Metni</label>
                    <input type="text" name="ece[newsletter_btn]"
                        value="<?php echo esc_attr(ece_general_get('newsletter_btn', 'Subscribe')); ?>"
                        placeholder="Subscribe">
                </div>
            </div>

            <!-- Footer Logo & About -->
            <div class="ece-card">
                <div class="ece-card-title">Footer Logo ve Açıklama</div>
                <div class="ece-field">
                    <label>Footer Logosu</label>
                    <div class="ece-field-group">
                        <input type="text" name="ece[footer_logo]"
                            value="<?php echo esc_attr(ece_general_get('footer_logo')); ?>"
                            placeholder="URL veya boş bırak (header logosu kullanılır)">
                        <button type="button" class="ece-upload-btn"><i class="fas fa-upload"></i> Yükle</button>
                    </div>
                    <div class="ece-image-preview">
                        <?php if (ece_general_get('footer_logo')): ?>
                            <img src="<?php echo esc_url(ece_general_get('footer_logo')); ?>" alt="Logo" style="background:#333; padding:10px; max-height:80px;">
                        <?php endif; ?>
                    </div>
                    <p class="ece-help">Boş bırakırsanız header logosu veya site logosu kullanılır.</p>
                </div>
                <div class="ece-field">
                    <label>Marka Açıklaması (logo altı)</label>
                    <textarea name="ece[footer_about_text]" rows="4" placeholder="Premium carpet manufacturer..."><?php echo esc_textarea(ece_general_get('footer_about_text', 'Premium carpet manufacturer delivering timeless design and infinite elegance since 1992. Trusted by leading hotels, offices, and residences across 40+ countries.')); ?></textarea>
                </div>
            </div>

            <!-- Footer Menü -->
            <div class="ece-card" style="border: 2px solid #2271b1; background: #f0f6fc;">
                <div class="ece-card-title"><i class="fas fa-bars"></i> Footer Menü Sütunları</div>
                <p class="ece-help">Footer'daki link sütunları (Collections, Solutions, Company, Support) <strong>Görünüm → Menüler</strong> sayfasında <strong>Footer Menu</strong> konumuna atadığınız menüden gelir.</p>
                <p><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="button button-primary" target="_blank"><i class="fas fa-external-link-alt"></i> Menüleri düzenle</a></p>
            </div>

            <!-- Ödeme yöntemleri (alt çizgide resimler) -->
            <div class="ece-card">
                <div class="ece-card-title">Ödeme Yöntemleri (alt çizgide sağda)</div>
                <p class="ece-help">Visa, Mastercard vb. ödeme yöntemi logolarını ekleyin. Her biri için bir görsel yükleyin (önerilen: şeffaf arka planlı PNG, yaklaşık 40x26 px).</p>
                <?php
                $payment_icons = get_option('eternal_general_payment_icons', []);
                if (empty($payment_icons) || !is_array($payment_icons)) {
                    $payment_icons = [['url' => '', 'label' => '']];
                }
                ?>
                <div class="ece-repeater-container">
                    <?php foreach ($payment_icons as $pIdx => $pay): ?>
                        <div class="ece-repeater-item">
                            <span class="ece-repeater-num"><?php echo $pIdx + 1; ?></span>
                            <button type="button" class="ece-remove-repeater" title="Sil"><i class="fas fa-trash"></i> Sil</button>
                            <div class="ece-field">
                                <label>Ödeme logosu (URL)</label>
                                <div class="ece-field-group">
                                    <input type="text" name="ece[payment_icons][<?php echo $pIdx; ?>][url]"
                                        value="<?php echo esc_attr($pay['url'] ?? ''); ?>"
                                        placeholder="https://... veya Yükle ile seçin">
                                    <button type="button" class="ece-upload-btn"><i class="fas fa-upload"></i> Yükle</button>
                                </div>
                                <div class="ece-image-preview">
                                    <?php if (!empty($pay['url'])): ?>
                                        <img src="<?php echo esc_url($pay['url']); ?>" alt="" style="max-height:28px; width:auto;">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="ece-field">
                                <label>Etiket (isteğe bağlı, erişilebilirlik için)</label>
                                <input type="text" name="ece[payment_icons][<?php echo $pIdx; ?>][label]"
                                    value="<?php echo esc_attr($pay['label'] ?? ''); ?>"
                                    placeholder="Örn: Visa">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="ece-add-repeater"><i class="fas fa-plus"></i> Ödeme logosu ekle</button>
            </div>

            <!-- Alt Kısım: Copyright ve Yasal Linkler -->
            <div class="ece-card">
                <div class="ece-card-title">Alt Çizgi (Copyright ve Yasal)</div>
                <div class="ece-field">
                    <label>Sol alan: Copyright yerine resim</label>
                    <div class="ece-field-group">
                        <input type="text" name="ece[copyright_image]"
                            value="<?php echo esc_attr(ece_general_get('copyright_image', '')); ?>"
                            placeholder="Resim URL — doluysa solda metin yerine bu resim gösterilir">
                        <button type="button" class="ece-upload-btn"><i class="fas fa-upload"></i> Yükle</button>
                    </div>
                    <div class="ece-image-preview">
                        <?php if (ece_general_get('copyright_image')): ?>
                            <img src="<?php echo esc_url(ece_general_get('copyright_image')); ?>" alt="" style="max-height:50px; width:auto; margin-top:8px;">
                        <?php endif; ?>
                    </div>
                    <p class="ece-help">Doluysa alt çizgide solda copyright metni yerine bu resim kullanılır (örn. logo veya sertifika). Boş bırakırsanız aşağıdaki metin gösterilir.</p>
                </div>
                <div class="ece-field">
                    <label>Copyright Metni (resim boşsa kullanılır)</label>
                    <input type="text" name="ece[copyright_text]"
                        value="<?php echo esc_attr(ece_general_get('copyright_text', '')); ?>"
                        placeholder="Boş bırakırsanız: © [Yıl] [Site Adı]. All rights reserved.">
                    <p class="ece-help">Sol resim eklemediyseniz bu metin solda görünür.</p>
                </div>
                <div class="ece-field">
                    <label>Yasal Linkler (Privacy Policy, Terms, Cookie vb.)</label>
                    <?php
                    $legal_links = get_option('eternal_general_legal_links', []);
                    if (empty($legal_links)) {
                        $legal_links = [
                            ['label' => 'Privacy Policy', 'url' => '#'],
                            ['label' => 'Terms of Service', 'url' => '#'],
                            ['label' => 'Cookie Settings', 'url' => '#'],
                        ];
                    }
                    $legal_links = is_array($legal_links) ? $legal_links : [];
                    ?>
                    <div class="ece-repeater-container">
                        <?php foreach ($legal_links as $index => $link): ?>
                            <div class="ece-repeater-item">
                                <span class="ece-repeater-num"><?php echo $index + 1; ?></span>
                                <button type="button" class="ece-remove-repeater" title="Sil"><i class="fas fa-trash"></i> Sil</button>
                                <div class="ece-row">
                                    <div class="ece-field">
                                        <input type="text" name="ece[legal_links][<?php echo $index; ?>][label]"
                                            value="<?php echo esc_attr($link['label'] ?? ''); ?>" placeholder="Link adı">
                                    </div>
                                    <div class="ece-field">
                                        <input type="text" name="ece[legal_links][<?php echo $index; ?>][url]"
                                            value="<?php echo esc_attr($link['url'] ?? ''); ?>" placeholder="URL">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="ece-add-repeater"><i class="fas fa-plus"></i> Yeni link ekle</button>
                </div>
            </div>
        </div>

        <!-- Save Bar -->
        <div class="ece-save-bar">
            <button type="submit" class="ece-save-btn">
                <i class="fas fa-save"></i> Ayarları Kaydet
            </button>
        </div>
    </form>
    <?php
}
