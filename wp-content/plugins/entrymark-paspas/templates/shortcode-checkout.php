<?php
/**
 * Entry Mark Paspas - Modern Checkout Interface
 * Clean, modern checkout with order summary and PayTR integration
 */
if (!defined('ABSPATH')) exit;
?>
<div class="emc-checkout-wrapper">
  <div class="emc-checkout-container">
    
    <!-- Header -->
    <div class="checkout-header">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
        Alışverişe Dön
      </a>
      <h1 class="checkout-title">Siparişi Tamamla</h1>
      <div class="checkout-steps">
        <div class="step completed">
          <span class="step-icon">✓</span>
          <span class="step-label">Tasarım</span>
        </div>
        <div class="step-line"></div>
        <div class="step completed">
          <span class="step-icon">✓</span>
          <span class="step-label">Sepet</span>
        </div>
        <div class="step-line"></div>
        <div class="step active">
          <span class="step-icon">3</span>
          <span class="step-label">Ödeme</span>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    <div id="emcCheckoutSuccess" class="success-message" style="display:none;">
      <div class="success-icon">🎉</div>
      <h2>Ödemeniz Başarıyla Alındı!</h2>
      <p>Sipariş numaranız: <strong id="emcSuccessOrderId"></strong></p>
      <p class="success-note">Sipariş detayları e-posta adresinize gönderildi.</p>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary">Ana Sayfaya Dön</a>
    </div>

    <!-- Fail Message -->
    <div id="emcCheckoutFail" class="fail-message" style="display:none;">
      <div class="fail-icon">⚠️</div>
      <h2>Ödeme Başarısız</h2>
      <p>Ödeme işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin veya farklı bir ödeme yöntemi kullanın.</p>
      <button type="button" class="btn-primary" onclick="location.reload()">Tekrar Dene</button>
    </div>

    <!-- Empty Cart -->
    <div id="emcCheckoutEmpty" class="empty-cart" style="display:none;">
      <div class="empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="9" cy="21" r="1"/>
          <circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
      </div>
      <h2>Sepetiniz Boş</h2>
      <p>Henüz sepetinize ürün eklemediniz.</p>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary">Tasarıma Başla</a>
    </div>

    <!-- Checkout Form -->
    <div id="emcCheckoutFormWrap" class="checkout-content">
      <div class="checkout-grid">
        
        <!-- Left: Order Summary -->
        <div class="order-summary-section">
          <div class="summary-card">
            <h2>Sipariş Özeti</h2>
            <div id="emcOrderItems" class="order-items"></div>
            
            <div class="summary-totals">
              <div class="total-row">
                <span>Ara Toplam</span>
                <span id="emcSubtotal">0,00 TL</span>
              </div>
              <div class="total-row">
                <span>Kargo</span>
                <span id="emcShipping">0,00 TL</span>
              </div>
              <div class="total-row">
                <span>KDV (%10)</span>
                <span id="emcTax">0,00 TL</span>
              </div>
              <div class="total-grand">
                <span>Toplam</span>
                <span id="emcTotal">0,00 TL</span>
              </div>
            </div>
          </div>

          <div class="trust-badges">
            <div class="badge">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              <span>Güvenli Ödeme</span>
            </div>
            <div class="badge">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <path d="m9 12 2 2 4-4"/>
              </svg>
              <span>SSL Sertifikalı</span>
            </div>
            <div class="badge">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
              <span>7/24 Destek</span>
            </div>
          </div>
        </div>

        <!-- Right: Customer Form -->
        <div class="customer-form-section">
          <div class="form-card">
            <h2>İletişim Bilgileri</h2>
            
            <form id="emcCheckoutForm">
              <div class="form-row two-col">
                <div class="form-group">
                  <label for="emcCustomerAd">
                    Ad <span class="required">*</span>
                  </label>
                  <input type="text" id="emcCustomerAd" required placeholder="Adınız">
                </div>
                <div class="form-group">
                  <label for="emcCustomerSoyad">
                    Soyad <span class="required">*</span>
                  </label>
                  <input type="text" id="emcCustomerSoyad" required placeholder="Soyadınız">
                </div>
              </div>

              <div class="form-group">
                <label for="emcCustomerEmail">
                  E-posta <span class="required">*</span>
                </label>
                <input type="email" id="emcCustomerEmail" required placeholder="ornek@email.com">
              </div>

              <div class="form-group">
                <label for="emcCustomerTelefon">
                  Telefon <span class="required">*</span>
                </label>
                <input type="tel" id="emcCustomerTelefon" required placeholder="05XX XXX XX XX">
              </div>

              <div class="form-group">
                <label for="emcCustomerAdres">
                  Teslimat Adresi <span class="required">*</span>
                </label>
                <textarea id="emcCustomerAdres" rows="3" required placeholder="Sokak, Bina No, Daire, İlçe/İl"></textarea>
              </div>

              <div class="form-group payment-method">
                <label>Ödeme Yöntemi</label>
                <div class="payment-option selected">
                  <div class="payment-radio"></div>
                  <div class="payment-info">
                    <strong>Kredi/Banka Kartı</strong>
                    <span>PayTR güvencesi ile güvenli ödeme</span>
                  </div>
                  <div class="payment-icons">
                    <span class="card-icon">💳</span>
                  </div>
                </div>
              </div>

              <button type="submit" id="emcPaytrBtn" class="btn-pay">
                <span class="btn-text">Ödemeyi Tamamla</span>
                <span class="btn-loader" style="display:none;">
                  <span class="spinner"></span>
                  İşleniyor...
                </span>
              </button>

              <p class="form-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                  <path d="m9 12 2 2 4-4"/>
                </svg>
                Bilgileriniz 256-bit SSL ile şifrelenmektedir.
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- PayTR iFrame -->
    <div id="emcPaytrIframeWrap" class="paytr-section" style="display:none;">
      <div class="iframe-container">
        <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
        <iframe id="emcPaytrIframe" title="Ödeme"></iframe>
      </div>
    </div>

  </div>
</div>
