<?php
/**
 * Plugin Name: Next Content
 * Plugin URI:  https://eternalyangin.com
 * Description: Next Content editör ile site içeriklerini kolayca yönetin.
 * Version:     1.1.0
 * Author:      Eternal Yangın
 * Text Domain: eternal-content-editor
 */

if (!defined('ABSPATH')) {
    exit;
}

define('ECE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ECE_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Section status check - Bölümün frontend'de gösterilip gösterilmeyeceği
 *
 * @param string $prefix Option prefix (home, about, services, contact, vb.)
 * @param string $section_key Section/tab key (hero, services, faq, vb.)
 * @return bool true = göster, false = gizle
 */
function ece_section_active($prefix, $section_key)
{
    $value = get_option('eternal_' . $prefix . '_' . $section_key . '_status', '1');
    return ($value === '1' || $value === true);
}

/**
 * Admin: Section status checkbox (her tab'ın ilk kartına eklenecek)
 */
function ece_render_section_status($prefix, $section_key)
{
    $value = get_option('eternal_' . $prefix . '_' . $section_key . '_status', '1');
    $name = 'ece[' . $section_key . '_status]';
    ?>
    <div class="ece-field ece-field-status" style="margin-bottom:1em;padding:10px;background:#f8f9fa;border-radius:4px;border-left:4px solid #2271b1;">
        <label class="ece-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="hidden" name="<?php echo esc_attr($name); ?>" value="0">
            <input type="checkbox" name="<?php echo esc_attr($name); ?>" value="1" <?php checked($value, '1'); ?>>
            <strong>Bölümü Göster</strong>
        </label>
        <p class="description" style="margin:4px 0 0 28px;">Kapalıysa bu bölüm sayfada görünmez.</p>
    </div>
    <?php
}

// Genel Ayarlar (Header/Footer) + Anasayfa + Hakkımızda + İletişim
require_once ECE_PLUGIN_DIR . 'includes/general-settings.php';
require_once ECE_PLUGIN_DIR . 'includes/home-settings.php';
require_once ECE_PLUGIN_DIR . 'includes/about-settings.php';
require_once ECE_PLUGIN_DIR . 'includes/contact-settings.php';
require_once ECE_PLUGIN_DIR . 'includes/gallery-settings.php';

/**
 * Mevcut sayfalar — Genel Ayarlar, Anasayfa, Hakkımızda, İletişim, Galeri
 */
function ece_get_pages()
{
    return [
        'general' => ['label' => 'Genel Ayarlar', 'icon' => 'fas fa-cogs', 'callback' => 'ece_general_settings_page'],
        'home'    => ['label' => 'Anasayfa', 'icon' => 'fas fa-home', 'callback' => 'ece_home_settings_page'],
        'about'   => ['label' => 'Hakkımızda', 'icon' => 'fas fa-info-circle', 'callback' => 'ece_about_settings_page'],
        'contact' => ['label' => 'İletişim', 'icon' => 'fas fa-envelope', 'callback' => 'ece_contact_settings_page'],
        'gallery' => ['label' => 'Galeri', 'icon' => 'fas fa-images', 'callback' => 'ece_gallery_settings_page'],
    ];
}

/**
 * Ana editör sayfası — doğrudan Genel Ayarlar (Header/Footer)
 */
function ece_main_editor_page()
{
    $pages = ece_get_pages();
    $active_page = isset($_GET['ece_page']) ? sanitize_key($_GET['ece_page']) : 'general';
    if (!isset($pages[$active_page])) {
        $active_page = 'general';
    }
    ?>
    <div class="ece-wrap">
        <div class="ece-top-bar">
            <div class="ece-top-bar-left">
                <img src="<?php echo esc_url(ECE_PLUGIN_URL . 'assets/images/next-logo.png'); ?>" alt="Next Content">
                <span class="ece-version">v1.1</span>
            </div>
            <div class="ece-top-bar-right">
                <span class="ece-page-label"><i class="fas fa-cog"></i> Sayfa:</span>
                <select class="ece-page-select" id="ecePageSelect" aria-label="<?php esc_attr_e( 'Düzenlenecek sayfayı seçin', 'eternal-content-editor' ); ?>">
                    <?php foreach ($pages as $key => $page): ?>
                        <option value="<?php echo esc_url(admin_url('admin.php?page=eternal-content&ece_page=' . $key)); ?>" <?php selected($active_page, $key); ?>>
                            <?php echo esc_html($page['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="ece-content">
            <?php
            if (isset($_GET['updated']) && $_GET['updated'] === 'true') {
                echo '<div class="ece-success"><i class="fas fa-check-circle"></i> Değişiklikler kaydedildi.</div>';
            }
            if (isset($pages[$active_page]) && is_callable($pages[$active_page]['callback'])) {
                call_user_func($pages[$active_page]['callback']);
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Register admin menu — single page only
 */
add_action('admin_menu', function () {
    add_menu_page(
        'Next Content',
        'Next Content',
        'manage_options',
        'eternal-content',
        'ece_main_editor_page',
        ECE_PLUGIN_URL . 'assets/images/icon-next-content.png',
        30
    );
});

/**
 * Recursive sanitize for nested arrays
 */
function ece_sanitize_nested($data) {
    if (is_array($data)) {
        return array_map('ece_sanitize_nested', $data);
    }
    return sanitize_textarea_field($data);
}

/**
 * General Settings (Header/Footer) - Save handler
 */
add_action('admin_init', function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !current_user_can('manage_options')) {
        return;
    }
    if (empty($_POST['ece_save_general'])) {
        return;
    }
    $nonce = isset($_POST['ece_general_nonce']) ? sanitize_text_field(wp_unslash($_POST['ece_general_nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'ece_save_general_action')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.</p></div>';
        });
        return;
    }
    $fields = isset($_POST['ece']) && is_array($_POST['ece']) ? wp_unslash($_POST['ece']) : [];
    foreach ($fields as $key => $value) {
        $sanitized_key = sanitize_key($key);
        if (empty($sanitized_key)) continue;
        $sanitized_value = is_array($value) ? ece_sanitize_nested($value) : sanitize_textarea_field($value);
        update_option('eternal_general_' . $sanitized_key, $sanitized_value);
    }
    $active_tab = isset($_POST['ece_active_tab']) ? sanitize_key($_POST['ece_active_tab']) : 'header';
    wp_safe_redirect(add_query_arg([
        'page' => 'eternal-content',
        'ece_page' => 'general',
        'tab' => $active_tab,
        'updated' => 'true',
    ], admin_url('admin.php')));
    exit;
}, 5);

/**
 * Anasayfa (Home) ayarları — Kaydet (admin_post ile güvenilir işlem)
 */
add_action('admin_post_ece_save_home', function () {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Yetkiniz yok.', 'eternal-content-editor'));
    }
    $nonce = isset($_POST['ece_home_nonce']) ? sanitize_text_field(wp_unslash($_POST['ece_home_nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'ece_save_home_action')) {
        wp_die(esc_html__('Güvenlik doğrulaması başarısız. Sayfayı yenileyip tekrar deneyin.', 'eternal-content-editor'));
    }
    $fields = isset($_POST['ece']) && is_array($_POST['ece']) ? wp_unslash($_POST['ece']) : [];
    foreach ($fields as $key => $value) {
        $sanitized_key = sanitize_key(str_replace([' ', '-'], '_', $key));
        if (empty($sanitized_key)) continue;
        $sanitized_value = is_array($value) ? ece_sanitize_nested($value) : sanitize_textarea_field($value);
        update_option('eternal_home_' . $sanitized_key, $sanitized_value);
    }
    $active_tab = isset($_POST['ece_active_tab']) ? sanitize_key($_POST['ece_active_tab']) : 'hero';
    $redirect = isset($_POST['ece_redirect']) ? esc_url_raw(wp_unslash($_POST['ece_redirect'])) : '';
    if (empty($redirect)) {
        $redirect = admin_url('admin.php');
    }
    $redirect = add_query_arg([
        'page' => 'eternal-content',
        'ece_page' => 'home',
        'tab' => $active_tab,
        'updated' => 'true',
    ], admin_url('admin.php'));
    wp_safe_redirect($redirect);
    exit;
});

/**
 * Hakkımızda sayfası ayarları — Kaydet
 */
add_action('admin_post_ece_save_about', function () {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Yetkiniz yok.', 'eternal-content-editor'));
    }
    $nonce = isset($_POST['ece_about_nonce']) ? sanitize_text_field(wp_unslash($_POST['ece_about_nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'ece_save_about_action')) {
        wp_die(esc_html__('Güvenlik doğrulaması başarısız. Sayfayı yenileyip tekrar deneyin.', 'eternal-content-editor'));
    }
    $fields = isset($_POST['ece']) && is_array($_POST['ece']) ? wp_unslash($_POST['ece']) : [];
    foreach ($fields as $key => $value) {
        $sanitized_key = sanitize_key(str_replace([' ', '-'], '_', $key));
        if (empty($sanitized_key)) continue;
        $sanitized_value = is_array($value) ? ece_sanitize_nested($value) : sanitize_textarea_field($value);
        update_option('eternal_about_' . $sanitized_key, $sanitized_value);
    }
    $active_tab = isset($_POST['ece_active_tab']) ? sanitize_key($_POST['ece_active_tab']) : 'hero';
    $redirect = add_query_arg([
        'page' => 'eternal-content',
        'ece_page' => 'about',
        'tab' => $active_tab,
        'updated' => 'true',
    ], admin_url('admin.php'));
    wp_safe_redirect($redirect);
    exit;
});

/**
 * İletişim sayfası ayarları — Kaydet
 */
add_action('admin_post_ece_save_contact', function () {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Yetkiniz yok.', 'eternal-content-editor'));
    }
    $nonce = isset($_POST['ece_contact_nonce']) ? sanitize_text_field(wp_unslash($_POST['ece_contact_nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'ece_save_contact_action')) {
        wp_die(esc_html__('Güvenlik doğrulaması başarısız. Sayfayı yenileyip tekrar deneyin.', 'eternal-content-editor'));
    }
    $fields = isset($_POST['ece']) && is_array($_POST['ece']) ? wp_unslash($_POST['ece']) : [];
    foreach ($fields as $key => $value) {
        $sanitized_key = sanitize_key(str_replace([' ', '-'], '_', $key));
        if (empty($sanitized_key)) continue;
        $sanitized_value = is_array($value) ? ece_sanitize_nested($value) : sanitize_textarea_field($value);
        update_option('eternal_contact_' . $sanitized_key, $sanitized_value);
    }
    $active_tab = isset($_POST['ece_active_tab']) ? sanitize_key($_POST['ece_active_tab']) : 'hero';
    $redirect = add_query_arg([
        'page' => 'eternal-content',
        'ece_page' => 'contact',
        'tab' => $active_tab,
        'updated' => 'true',
    ], admin_url('admin.php'));
    wp_safe_redirect($redirect);
    exit;
});

/**
 * Galeri sayfası ayarları — Kaydet
 */
add_action('admin_post_ece_save_gallery', function () {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Yetkiniz yok.', 'eternal-content-editor'));
    }
    $nonce = isset($_POST['ece_gallery_nonce']) ? sanitize_text_field(wp_unslash($_POST['ece_gallery_nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'ece_save_gallery_action')) {
        wp_die(esc_html__('Güvenlik doğrulaması başarısız. Sayfayı yenileyip tekrar deneyin.', 'eternal-content-editor'));
    }
    $fields = isset($_POST['ece']) && is_array($_POST['ece']) ? wp_unslash($_POST['ece']) : [];
    if (isset($fields['products']) && is_array($fields['products'])) {
        $products = [];
        foreach (array_values($fields['products']) as $i => $row) {
            if (!is_array($row)) continue;
            $colors_str = isset($row['colors']) ? sanitize_text_field($row['colors']) : '';
            $colors_arr = array_filter(array_map('trim', explode(',', $colors_str)));
            if (empty($colors_arr) && !empty($row['color'])) {
                $colors_arr = [sanitize_text_field($row['color'])];
            }
            $products[] = [
                'id' => $i + 1,
                'title' => isset($row['title']) ? sanitize_text_field($row['title']) : '',
                'collection' => isset($row['collection']) ? sanitize_text_field($row['collection']) : '',
                'category' => in_array($row['category'] ?? '', ['hotel', 'office', 'custom', 'residential'], true) ? sanitize_key($row['category']) : 'custom',
                'color' => isset($row['color']) ? sanitize_hex_color($row['color']) ?: sanitize_text_field($row['color']) : '#2D3748',
                'colors' => $colors_arr,
                'material' => isset($row['material']) ? sanitize_text_field($row['material']) : '',
                'size' => isset($row['size']) ? sanitize_text_field($row['size']) : '',
                'thickness' => isset($row['thickness']) ? sanitize_text_field($row['thickness']) : '',
                'price' => isset($row['price']) ? sanitize_text_field($row['price']) : '',
                'desc' => isset($row['desc']) ? sanitize_textarea_field($row['desc']) : '',
                'badge' => in_array($row['badge'] ?? '', ['new', 'popular', 'sale'], true) ? sanitize_key($row['badge']) : null,
                'layout' => in_array($row['layout'] ?? '', ['normal', 'tall', 'wide', 'featured'], true) ? sanitize_key($row['layout']) : 'normal',
            ];
        }
        update_option('eternal_gallery_products_json', wp_json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        unset($fields['products']);
    }
    if (isset($fields['categories']) && is_array($fields['categories'])) {
        $lines = [];
        foreach (array_values($fields['categories']) as $row) {
            if (!is_array($row)) continue;
            $label = isset($row['label']) ? sanitize_text_field($row['label']) : '';
            $slug = isset($row['slug']) ? sanitize_key(str_replace(' ', '-', $row['slug'])) : '';
            if ($label !== '' || $slug !== '') {
                $lines[] = $label . '|' . ($slug !== '' ? $slug : 'cat-' . count($lines));
            }
        }
        if (!empty($lines)) {
            update_option('eternal_gallery_filters_items', implode("\n", $lines));
        }
        unset($fields['categories']);
    }
    foreach ($fields as $key => $value) {
        $sanitized_key = sanitize_key(str_replace([' ', '-'], '_', $key));
        if (empty($sanitized_key)) continue;
        if ($sanitized_key === 'products_json') {
            continue;
        }
        $sanitized_value = is_array($value) ? ece_sanitize_nested($value) : sanitize_textarea_field($value);
        update_option('eternal_gallery_' . $sanitized_key, $sanitized_value);
    }
    $active_tab = isset($_POST['ece_active_tab']) ? sanitize_key($_POST['ece_active_tab']) : 'hero';
    $redirect = add_query_arg([
        'page' => 'eternal-content',
        'ece_page' => 'gallery',
        'tab' => $active_tab,
        'updated' => 'true',
    ], admin_url('admin.php'));
    wp_safe_redirect($redirect);
    exit;
});

/**
 * Next Content menü ikonu - tüm admin sayfalarında
 */
add_action('admin_head', function () {
    echo '<style>
    #adminmenu .toplevel_page_eternal-content .wp-menu-image img {
        width: 20px; height: 20px; padding: 6px 0; opacity: 0.9; filter: none;
    }
    #adminmenu .toplevel_page_eternal-content:hover .wp-menu-image img,
    #adminmenu .toplevel_page_eternal-content.wp-has-current-submenu .wp-menu-image img {
        opacity: 1;
    }
    </style>';
});

/**
 * Register admin assets
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if (strpos($hook, 'eternal-content') === false) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style('ece-admin-style', ECE_PLUGIN_URL . 'assets/admin.css', [], '1.4.0');
    wp_enqueue_script('ece-admin-script', ECE_PLUGIN_URL . 'assets/admin.js', ['jquery'], '1.3.0', true);
});

// Eski sayfa kaydetme işleyicileri kaldırıldı — sadece Genel Ayarlar kaydı yukarıdaki admin_init ile yapılıyor.

// Test modülü kaldırıldı — sadece Genel Ayarlar kullanılıyor.
