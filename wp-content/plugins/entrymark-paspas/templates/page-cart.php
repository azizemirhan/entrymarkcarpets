<?php
/**
 * Template Name: Paspas Sepet
 * Entry Mark Paspas - Cart Page
 */

if (!defined('ABSPATH')) exit;

get_header();

// Sepet verilerini al
$cart = EMC_Cart::get_cart();
$cart_items = $cart['items'] ?? [];
$cart_count = count($cart_items);

// Debug: sepet verilerini logla
if (!empty($cart_items)) {
    error_log('Cart Items: ' . print_r($cart_items, true));
}

// Fiyatlandırma ayarları
$price_per_m2 = function_exists('emc_get_float_option') ? emc_get_float_option('emc_price_per_m2', 20.45) : (float) get_option('emc_price_per_m2', 20.45);
$tax_rate = function_exists('emc_get_float_option') ? emc_get_float_option('emc_tax_rate', 10) : (float) get_option('emc_tax_rate', 10);
$checkout_page_id = get_option('emc_checkout_page_id', 0);
if ($checkout_page_id) {
	$checkout_url = get_permalink((int) $checkout_page_id);
} else {
	// Ödeme sayfası atanmamışsa slug ile dene (odeme veya checkout)
	$checkout_page = get_page_by_path('odeme') ?: get_page_by_path('checkout');
	$checkout_url = $checkout_page ? get_permalink($checkout_page) : home_url('/odeme');
}

// Sepet sayfası ayarları
$cart_title = get_option('emc_cart_title', 'Sepetim');
$cart_subtitle = get_option('emc_cart_subtitle', 'ürün');
$payment_logos = get_option('emc_payment_logos', '');
$trust_items = get_option('emc_trust_items', array(
    array('icon' => 'shield', 'title' => 'Güvenli Ödeme', 'desc' => '256-bit SSL şifreleme'),
    array('icon' => 'truck', 'title' => 'Ücretsiz Kargo', 'desc' => '500 TL üzeri siparişlerde'),
    array('icon' => 'refresh', 'title' => 'Kolay İade', 'desc' => '14 gün içinde ücretsiz'),
));
?>

<div class="emc-cart-page">

  <!-- Breadcrumb -->
  <div class="emc-breadcrumb">
    <a href="<?php echo esc_url(home_url('/')); ?>">Anasayfa</a>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    <span><?php echo esc_html($cart_title); ?></span>
  </div>

  <!-- Page Header -->
  <div class="emc-page-header">
    <div class="emc-page-header-right">
      <a href="<?php echo esc_url(home_url('/paspas-tasarim/')); ?>" class="emc-continue-shopping">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
        Alışverişe Devam Et
      </a>
      <button class="emc-clear-cart-btn" id="clearCartBtn" <?php echo $cart_count === 0 ? 'style="display:none;"' : ''; ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        Sepeti Temizle
      </button>
    </div>
  </div>

  <!-- Layout -->
  <div class="emc-cart-layout" id="cartLayout" <?php echo $cart_count === 0 ? 'style="display:none;"' : ''; ?>>

    <!-- LEFT: Cart Items -->
    <div>
      <!-- Column header -->
      <div class="emc-cart-col-header">
        <span>Ürün</span>
        <span style="text-align:center">Birim Fiyat</span>
        <span style="text-align:center">Adet</span>
        <span class="col-right">Ara Toplam</span>
        <span></span>
      </div>

      <div class="emc-cart-items" id="cartItems">
        <?php if ($cart_count > 0): ?>
          <?php 
          $customizer_url = home_url('/paspas-ozellestir/');
          foreach ($cart_items as $index => $item): 
            $design = $item['design'] ?? [];
            $pricing = $item['pricing'] ?? [];
            $quantity = $item['quantity'] ?? 1;
            $product_price = $pricing['base'] ?? 0;
            $subtotal = $product_price * $quantity;
            $shape_names = ['horizontal' => 'Yatay', 'vertical' => 'Dikey', 'round' => 'Yuvarlak'];
            $shape_name = $shape_names[$design['orient'] ?? 'horizontal'] ?? 'Yatay';
            $size_w = $design['size']['w'] ?? 40;
            $size_h = $design['size']['h'] ?? 70;
            $size_text = $size_w . '×' . $size_h . ' cm';
            $texture_name = $design['texture_name'] ?? 'Standart';
            $texture_id = $design['texture_id'] ?? '';
            $text = $design['text'] ?? '';
            $textColor = $design['textColor'] ?? '#FFFFFF';
            
            // Logo bilgilerini URL'ye ekle
            $images_param = '';
            if (!empty($design['images']) && is_array($design['images'])) {
                $img_urls = array_map(function($img) {
                    return $img['url'] ?? '';
                }, $design['images']);
                $images_param = implode(',', array_filter($img_urls));
            }
            
            // Düzenleme linki oluştur - MANUEL URL oluştur
            $edit_url = $customizer_url . '?';
            $edit_url .= 'shape=' . urlencode($design['orient'] ?? 'horizontal');
            $edit_url .= '&texture=' . urlencode($texture_id);
            $edit_url .= '&width=' . urlencode($size_w);
            $edit_url .= '&height=' . urlencode($size_h);
            $edit_url .= '&text=' . urlencode($text);
            $edit_url .= '&textColor=' . urlencode($textColor);
            $edit_url .= '&images=' . urlencode($images_param);  // ÖNEMLİ: images parametresi
            $edit_url .= '&edit_item=' . urlencode($index);
            
            // Debug log
            error_log('EMC CART EDIT URL: ' . $edit_url);
            
            // Görsel URL
            $preview_url = $item['preview_data_url'] ?? '';
          ?>
          <div class="emc-cart-item" data-id="<?php echo esc_attr($item['cart_id'] ?? ''); ?>">
            <div class="emc-cart-product">
              <a href="<?php echo htmlspecialchars($edit_url, ENT_QUOTES, 'UTF-8'); ?>" class="emc-cart-product-img-link">
                <div class="emc-cart-product-img">
                  <?php if (!empty($preview_url) && strpos($preview_url, 'data:image') === 0): ?>
                    <img src="<?php echo esc_attr($preview_url); ?>" alt="Tasarım" loading="lazy" onerror="this.parentElement.innerHTML='<div class=\'emc-cart-img-error\'>Görsel yüklenemedi</div>'">
                  <?php elseif (!empty($preview_url)): ?>
                    <img src="<?php echo esc_url($preview_url); ?>" alt="Tasarım" loading="lazy">
                  <?php else: ?>
                    <div class="emc-cart-product-img-placeholder" style="background: linear-gradient(135deg, #2D3748, #4A5568);">
                      <svg viewBox="0 0 24 24" width="40" height="40"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/></svg>
                    </div>
                  <?php endif; ?>
                  <div class="emc-cart-edit-overlay">
                    <span>Düzenle</span>
                  </div>
                </div>
              </a>
              <div class="emc-cart-product-info">
                <a href="<?php echo htmlspecialchars($edit_url, ENT_QUOTES, 'UTF-8'); ?>" class="emc-cart-product-name-link">
                  <div class="emc-cart-product-name">Özel Paspas Tasarımı</div>
                </a>
                <div class="emc-cart-product-variant"><?php echo esc_html($size_text); ?> · <?php echo esc_html($texture_name); ?></div>
                <div class="emc-cart-product-tags">
                  <span class="emc-cart-product-tag"><?php echo esc_html($shape_name); ?></span>
                  <?php if (!empty($text)): ?>
                    <span class="emc-cart-product-tag custom">Yazı: <?php echo esc_html(mb_substr($text, 0, 15)); ?><?php echo mb_strlen($text) > 15 ? '...' : ''; ?></span>
                  <?php endif; ?>
                </div>
                <a href="<?php echo htmlspecialchars($edit_url, ENT_QUOTES, 'UTF-8'); ?>" class="emc-cart-edit-link">
                  <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  Tasarımı Düzenle
                </a>
              </div>
            </div>
            <!-- Mobile Title -->
            <div class="emc-cart-mobile-title">
              <a href="<?php echo htmlspecialchars($edit_url, ENT_QUOTES, 'UTF-8'); ?>" class="emc-cart-product-name-link">
                <div class="emc-cart-product-name">Özel Paspas Tasarımı</div>
              </a>
              <div class="emc-cart-product-variant"><?php echo esc_html($size_text); ?> · <?php echo esc_html($texture_name); ?></div>
            </div>
            
            <div class="emc-cart-unit-price"><?php echo emc_format_price($product_price); ?></div>
            <div class="emc-cart-qty">
              <div class="emc-qty-control">
                <button class="emc-qty-btn <?php echo $quantity <= 1 ? 'disabled' : ''; ?>" data-action="dec" data-id="<?php echo esc_attr($item['cart_id'] ?? ''); ?>">−</button>
                <span class="emc-qty-value"><?php echo intval($quantity); ?></span>
                <button class="emc-qty-btn" data-action="inc" data-id="<?php echo esc_attr($item['cart_id'] ?? ''); ?>">+</button>
              </div>
            </div>
            <div class="emc-cart-subtotal"><?php echo emc_format_price($subtotal); ?></div>
            <button class="emc-cart-remove" data-id="<?php echo esc_attr($item['cart_id'] ?? ''); ?>" title="Kaldır">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Coupon -->
      <div class="emc-coupon-section" id="couponSection">
        <div class="emc-coupon-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
          İndirim Kuponu
        </div>
        <div class="emc-coupon-row">
          <input class="emc-coupon-input" id="couponInput" type="text" placeholder="Kupon kodunuzu girin...">
          <button class="emc-coupon-apply" id="couponApply">Uygula</button>
        </div>
        <div class="emc-coupon-msg" id="couponMsg">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <span id="couponMsgText"></span>
        </div>
      </div>

      <!-- Trust -->
      <?php
      $svg_icons = array(
          'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
          'truck' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
          'refresh' => '<polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>',
          'credit-card' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
          'lock' => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
          'check' => '<polyline points="20 6 9 17 4 12"/>',
      );
      ?>
      <div class="emc-trust-strip">
        <?php foreach ($trust_items as $item) : ?>
        <div class="emc-trust-item">
          <div class="emc-trust-item-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <?php echo $svg_icons[$item['icon']] ?? $svg_icons['shield']; ?>
            </svg>
          </div>
          <div class="emc-trust-item-text"><strong><?php echo esc_html($item['title']); ?></strong><?php echo esc_html($item['desc']); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RIGHT: Order Summary -->
    <div class="emc-order-summary" id="orderSummary">
      <div class="emc-summary-header">
        <div class="emc-summary-title">Sipariş Özeti</div>
        <div class="emc-summary-item-count" id="summaryCount"><?php echo $cart_count; ?> ürün</div>
      </div>
      <div class="emc-summary-items" id="summaryItems">
        <?php if ($cart_count > 0): ?>
          <?php foreach ($cart_items as $item): 
            $design = $item['design'] ?? [];
            $pricing = $item['pricing'] ?? [];
            $quantity = $item['quantity'] ?? 1;
            $product_price = $pricing['base'] ?? 0;
            $subtotal = $product_price * $quantity;
            $preview_url = $item['preview_data_url'] ?? '';
          ?>
          <div class="emc-summary-mini-item" data-id="<?php echo esc_attr($item['cart_id'] ?? ''); ?>">
            <div class="emc-summary-mini-img">
              <?php if (!empty($preview_url) && strpos($preview_url, 'data:image') === 0): ?>
                <img src="<?php echo esc_attr($preview_url); ?>" alt="" loading="lazy">
              <?php elseif (!empty($preview_url)): ?>
                <img src="<?php echo esc_url($preview_url); ?>" alt="" loading="lazy">
              <?php endif; ?>
            </div>
            <div class="emc-summary-mini-info">
              <div class="emc-summary-mini-name">Özel Paspas</div>
              <div class="emc-summary-mini-meta"><?php echo intval($quantity); ?> adet</div>
            </div>
            <div class="emc-summary-mini-price"><?php echo emc_format_price($subtotal); ?></div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="emc-summary-pricing">
        <div class="emc-summary-row">
          <span class="emc-summary-row-label">Ara Toplam</span>
          <span class="emc-summary-row-value" id="sumSubtotal"><?php echo emc_format_price($cart['subtotal'] ?? 0); ?></span>
        </div>
        <div class="emc-summary-row shipping">
          <span class="emc-summary-row-label">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Kargo
          </span>
          <span class="emc-summary-row-value <?php echo ($cart['shipping'] ?? 0) == 0 ? 'free' : ''; ?>" id="sumShipping">
            <?php echo ($cart['shipping'] ?? 0) == 0 ? 'Ücretsiz' : emc_format_price($cart['shipping']); ?>
          </span>
        </div>
        <div class="emc-summary-row" id="discountRow" style="display:none;">
          <span class="emc-summary-row-label">İndirim</span>
          <span class="emc-summary-row-value discount" id="sumDiscount">-0 TL</span>
        </div>
        <div class="emc-summary-row">
          <span class="emc-summary-row-label">KDV (%<?php echo intval($tax_rate); ?>)</span>
          <span class="emc-summary-row-value" id="sumTax"><?php echo emc_format_price($cart['tax'] ?? 0); ?></span>
        </div>
        <div class="emc-summary-divider"></div>
        <div class="emc-summary-total-row">
          <span class="emc-summary-total-label">Toplam</span>
          <span class="emc-summary-total-value" id="sumTotal"><?php echo emc_format_price($cart['total'] ?? 0); ?></span>
        </div>
      </div>
      <div class="emc-summary-cta">
        <a href="<?php echo esc_url($checkout_url); ?>" class="emc-checkout-btn" id="checkoutBtn" title="Ödeme sayfasına git">
          Ödemeye Geç
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
        </a>
        <div class="emc-summary-secure">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          Güvenli ödeme ile korunuyorsunuz
        </div>
        <div class="emc-summary-payments">
          <?php if ($payment_logos) : ?>
            <img src="<?php echo esc_url($payment_logos); ?>" alt="Ödeme Yöntemleri" style="max-width:100%; height:auto;">
          <?php else : ?>
            <div class="emc-pay-icon">VISA</div>
            <div class="emc-pay-icon">MC</div>
            <div class="emc-pay-icon">AMEX</div>
            <div class="emc-pay-icon">PP</div>
            <div class="emc-pay-icon">IBAN</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Empty State -->
  <div class="emc-empty-cart <?php echo $cart_count === 0 ? 'visible' : ''; ?>" id="emptyCart">
    <div class="emc-empty-cart-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
    </div>
    <h3>Sepetiniz Boş</h3>
    <p>Henüz sepetinize ürün eklemediniz. Tasarımlarınızı oluşturmaya başlayın!</p>
    <a href="<?php echo esc_url(home_url('/paspas-tasarim/')); ?>" class="emc-empty-cart-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
      Tasarıma Başla
    </a>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="emc-modal-overlay" id="emcDeleteModal">
  <div class="emc-modal">
    <div class="emc-modal-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M3 6h18"/>
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
        <line x1="10" y1="11" x2="10" y2="17"/>
        <line x1="14" y1="11" x2="14" y2="17"/>
      </svg>
    </div>
    <h3 class="emc-modal-title">Ürünü Kaldır</h3>
    <p class="emc-modal-text">Bu ürünü sepetten kaldırmak istediğinize emin misiniz?</p>
    <div class="emc-modal-actions">
      <button class="emc-modal-btn emc-modal-btn-cancel" id="emcModalCancel">İptal</button>
      <button class="emc-modal-btn emc-modal-btn-confirm" id="emcModalConfirm">Kaldır</button>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="emc-toast" id="emcToast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
  <span id="emcToastMsg"></span>
</div>

<?php get_footer(); ?>
