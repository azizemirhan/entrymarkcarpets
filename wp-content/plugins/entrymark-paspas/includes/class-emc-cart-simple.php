<?php
/**
 * Basit Sepet İşlemleri - REST API yerine form submit ile
 */

if (!defined('ABSPATH')) exit;

class EMC_Cart_Simple {

    public static function init() {
        add_action('init', array(__CLASS__, 'handle_cart_actions'), 5);
        add_action('template_redirect', array(__CLASS__, 'handle_cart_actions'));
    }

    public static function handle_cart_actions() {
        // Sadece POST isteklerinde çalış
        if (empty($_POST)) {
            return;
        }
        error_log('EMC: handle_cart_actions POST=' . print_r($_POST, true));
        // Sepete ekleme
        if (isset($_POST['emc_add_to_cart'])) {
            // Nonce doğrulaması - şimdilik devre dışı
            // if (!wp_verify_nonce($_POST['emc_cart_nonce'] ?? '', 'emc_cart_action')) {
            //     wp_die('Güvenlik hatası');
            // }

            // Logo'ları işle ve sunucuya kaydet
            $design_images = array();
            $images_count = intval($_POST['images_count'] ?? 0);
            error_log('EMC: Adding to cart, images_count=' . $images_count);
            
            if ($images_count > 0 && !empty($_POST['design_images'])) {
                $images_data = json_decode(stripslashes($_POST['design_images']), true);
                error_log('EMC: design_images decoded: ' . print_r($images_data, true));
                if (is_array($images_data)) {
                    foreach ($images_data as $img) {
                        if (!empty($img['data_url'])) {
                            error_log('EMC: Uploading image: ' . ($img['name'] ?? 'logo'));
                            $uploaded_url = self::upload_base64_image($img['data_url'], $img['name'] ?? 'logo');
                            error_log('EMC: Upload result: ' . ($uploaded_url ?: 'FAILED'));
                            if ($uploaded_url) {
                                $design_images[] = array(
                                    'url' => $uploaded_url,
                                    'name' => $img['name'] ?? 'logo',
                                    'x' => $img['x'] ?? 50,
                                    'y' => $img['y'] ?? 50,
                                    'width' => $img['width'] ?? 100,
                                    'height' => $img['height'] ?? 100
                                );
                            }
                        }
                    }
                }
            }
            error_log('EMC: Total images saved: ' . count($design_images));
            
            $design = array(
                'orient' => sanitize_text_field($_POST['shape'] ?? 'horizontal'),
                'recess' => 'no',
                'texture_id' => sanitize_text_field($_POST['texture_id'] ?? ''),
                'texture_name' => sanitize_text_field($_POST['texture_name'] ?? ''),
                'size' => array(
                    'w' => intval($_POST['width'] ?? 40),
                    'h' => intval($_POST['height'] ?? 70)
                ),
                'text' => sanitize_text_field($_POST['text'] ?? ''),
                'textColor' => sanitize_text_field($_POST['textColor'] ?? '#FFFFFF'),
                'textFont' => 'Arial',
                'textBold' => false,
                'textItalic' => false,
                'shipping' => sanitize_text_field($_POST['shipping'] ?? ''),
                'images_count' => count($design_images),
                'images' => $design_images
            );

            $pricing = array(
                'base' => floatval($_POST['base_price'] ?? 0),
                'shipCost' => floatval($_POST['shipping_price'] ?? 0),
                'tax' => floatval($_POST['tax'] ?? 0),
                'total' => floatval($_POST['total'] ?? 0)
            );

            $summary = array(
                'texture_name' => $design['texture_name'],
                'size_label' => $design['size']['w'] . '×' . $design['size']['h'] . ' cm',
                'shipping_label' => sanitize_text_field($_POST['shipping_name'] ?? '')
            );

            // Sepete ekle
            $cart_id = EMC_Cart::get_cart_id();
            if (!$cart_id) {
                $cart_id = EMC_Cart::create_cart_id();
                // Cookie ayarla
                $cookie_path = defined('COOKIEPATH') ? COOKIEPATH : '/';
                $cookie_domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
                setcookie(EMC_Cart::COOKIE_NAME, $cart_id, time() + DAY_IN_SECONDS * 2, $cookie_path, $cookie_domain);
                $_COOKIE[EMC_Cart::COOKIE_NAME] = $cart_id;
            }

            $result = EMC_Cart::add_item($design, $summary, $pricing, $_POST['preview_data_url'] ?? '', $cart_id);

            // Sepet sayfasına yönlendir
            $cart_page_id = get_option('emc_cart_page_id', 0);
            $cart_url = $cart_page_id ? get_permalink($cart_page_id) : home_url('/sepet/');
            
            wp_redirect($cart_url);
            exit;
        }
        
        // Sepet işlemleri (update qty, remove, clear)
        if (isset($_POST['emc_cart_action'])) {
            error_log('EMC CART ACTION: ' . print_r($_POST, true));
            $action = sanitize_text_field($_POST['emc_cart_action']);
            $cart_id = sanitize_text_field($_POST['cart_id'] ?? '');
            error_log('EMC ACTION=' . $action . ' CART_ID=' . $cart_id);
            
            // Şu anki URL'yi al (cart page)
            $cart_page_id = get_option('emc_cart_page_id', 0);
            $cart_url = $cart_page_id ? get_permalink($cart_page_id) : home_url('/sepet/');
            
            // Kullanıcının sepet ID'sini al (POST'taki cart_id ITEM'ın id'si, user cart_id değil)
            $user_cart_id = EMC_Cart::get_cart_id();
            error_log('EMC USER_CART_ID=[' . $user_cart_id . '] ITEM_CART_ID=[' . $cart_id . ']');
            error_log('EMC COOKIE: ' . print_r($_COOKIE, true));
            
            switch ($action) {
                case 'update_qty':
                    $qty_action = sanitize_text_field($_POST['action'] ?? '');
                    if ($cart_id && $user_cart_id && in_array($qty_action, ['inc', 'dec'])) {
                        $items = EMC_Cart::get_items($user_cart_id);
                        error_log('EMC UPDATE: Found ' . count($items) . ' items in user cart [' . $user_cart_id . ']');
                        $found = false;
                        for ($i = 0; $i < count($items); $i++) {
                            if ($items[$i]['cart_id'] === $cart_id) {
                                error_log('EMC UPDATE: Found matching item at index ' . $i . ', current qty=' . ($items[$i]['quantity'] ?? 1));
                                if ($qty_action === 'inc') {
                                    $items[$i]['quantity'] = ($items[$i]['quantity'] ?? 1) + 1;
                                } else {
                                    $items[$i]['quantity'] = max(1, ($items[$i]['quantity'] ?? 1) - 1);
                                }
                                error_log('EMC UPDATE: New qty=' . $items[$i]['quantity']);
                                $found = true;
                                break;
                            }
                        }
                        if ($found) {
                            EMC_Cart::save_items($user_cart_id, $items);
                            error_log('EMC UPDATE: Saved items successfully');
                        } else {
                            error_log('EMC UPDATE: Item not found in cart!');
                        }
                    } else {
                        error_log('EMC UPDATE: SKIPPED - cart_id=[' . $cart_id . '] user_cart_id=[' . $user_cart_id . '] qty_action=[' . $qty_action . ']');
                    }
                    break;
                    
                case 'remove':
                    if ($cart_id && $user_cart_id) {
                        $items = EMC_Cart::get_items($user_cart_id);
                        error_log('EMC REMOVE: Found ' . count($items) . ' items in user cart');
                        $items = array_values(array_filter($items, function($item) use ($cart_id) {
                            return $item['cart_id'] !== $cart_id;
                        }));
                        error_log('EMC REMOVE: After filter ' . count($items) . ' items remain');
                        EMC_Cart::save_items($user_cart_id, $items);
                        error_log('EMC REMOVE: Saved items');
                    }
                    break;
                    
                case 'clear':
                    // Mevcut cart_id'yi al
                    $current_cart_id = EMC_Cart::get_cart_id();
                    if ($current_cart_id) {
                        EMC_Cart::clear($current_cart_id);
                    }
                    break;
            }
            
            wp_redirect($cart_url);
            exit;
        }
    }

    /**
     * Base64 görseli WordPress medya kütüphanesine yükle
     */
    private static function upload_base64_image($base64_data, $filename = 'logo') {
        // Base64 veriyi ayıkla
        if (strpos($base64_data, 'data:image') === 0) {
            $parts = explode(',', $base64_data);
            $base64_data = $parts[1] ?? '';
        }
        
        if (empty($base64_data)) {
            return false;
        }
        
        // Decode
        $image_data = base64_decode($base64_data);
        if (!$image_data) {
            return false;
        }
        
        // Dosya adı oluştur
        $filename = sanitize_file_name($filename . '_' . uniqid() . '.png');
        
        // Upload dizini
        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'] . '/' . $filename;
        $upload_url = $upload_dir['url'] . '/' . $filename;
        
        // Dosyayı kaydet
        if (!file_put_contents($upload_path, $image_data)) {
            return false;
        }
        
        // WordPress medya kütüphanesine ekle
        $filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $filetype['type'] ?: 'image/png',
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        
        $attach_id = wp_insert_attachment($attachment, $upload_path);
        if (!is_wp_error($attach_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload_path);
            wp_update_attachment_metadata($attach_id, $attach_data);
        }
        
        return $upload_url;
    }
}

EMC_Cart_Simple::init();
