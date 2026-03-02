<?php
/**
 * Entry Mark Paspas - Ödeme Sayfası (checkout.html tasarımı entegre)
 */
if (!defined('ABSPATH')) exit;

$cart_url = '';
$cart_page_id = get_option('emc_cart_page_id', 0);
if ($cart_page_id) {
  $cart_url = get_permalink((int) $cart_page_id);
} else {
  $cart_url = home_url('/sepet');
}
?>
<div class="emc-checkout-wrapper checkout-page">
  <div class="emc-checkout-container">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="<?php echo esc_url(home_url('/')); ?>">Anasayfa</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <a href="<?php echo esc_url($cart_url); ?>">Sepetim</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <span class="breadcrumb-current">Ödeme</span>
    </div>

    <div class="page-header">
      <h1 class="page-title">Ödeme</h1>
      <div class="page-title-sub">Siparişinizi tamamlamak için bilgilerinizi girin</div>
    </div>

    <!-- Steps Bar -->
    <div class="steps-bar">
      <div class="step active" data-step="1">
        <div class="step-num"><span>1</span></div>
        <div class="step-text">
          <div class="step-label">Teslimat Adresi</div>
          <div class="step-desc">Kargo bilgileri</div>
        </div>
      </div>
      <div class="step" data-step="2">
        <div class="step-num"><span>2</span></div>
        <div class="step-text">
          <div class="step-label">Ödeme Yöntemi</div>
          <div class="step-desc">Kart veya havale</div>
        </div>
      </div>
      <div class="step" data-step="3">
        <div class="step-num"><span>3</span></div>
        <div class="step-text">
          <div class="step-label">Sipariş Onayı</div>
          <div class="step-desc">Son kontrol</div>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div id="emcCheckoutLoading" class="empty-cart-message" style="display:block;">
      <div class="empty-icon"><span class="emc-spinner"></span></div>
      <h2>Sepet yükleniyor…</h2>
      <p>Lütfen bekleyin.</p>
    </div>

    <!-- Empty Cart -->
    <div id="emcCheckoutEmpty" class="empty-cart-message" style="display:none;">
      <div class="empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
      </div>
      <h2>Sepetiniz Boş</h2>
      <p>Henüz sepetinize ürün eklemediniz.</p>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="success-btn">Tasarıma Başla</a>
    </div>

    <!-- Checkout Form + Summary -->
    <div id="emcCheckoutFormWrap" class="checkout-layout" style="display:none;">
      <div class="checkout-form-column">
        <form id="emcCheckoutForm">

          <!-- STEP 1: Teslimat -->
          <div class="form-section active" id="step1">
            <div class="form-section-header">
              <div class="form-section-icon shipping">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
              </div>
              <div class="form-section-title">Teslimat Bilgileri</div>
            </div>
            <div class="form-body">
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Ad <span class="required">*</span></label>
                  <input type="text" class="form-input" id="emcCustomerAd" placeholder="Adınız" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Soyad <span class="required">*</span></label>
                  <input type="text" class="form-input" id="emcCustomerSoyad" placeholder="Soyadınız" required>
                </div>
                <div class="form-group full">
                  <label class="form-label">E-posta <span class="required">*</span></label>
                  <input type="email" class="form-input" id="emcCustomerEmail" placeholder="ornek@email.com" required>
                </div>
                <div class="form-group full">
                  <label class="form-label">Telefon <span class="required">*</span></label>
                  <input type="tel" class="form-input" id="emcCustomerTelefon" placeholder="+90 5XX XXX XX XX" required>
                </div>
                <div class="form-group full">
                  <label class="form-label">Adres <span class="required">*</span></label>
                  <input type="text" class="form-input" id="emcCustomerAdres" placeholder="Sokak, cadde, bina no, daire no" required>
                </div>
                <div class="form-group">
                  <label class="form-label">İl <span class="required">*</span></label>
                  <select class="form-input" id="emcCustomerIl">
                    <option value="">Seçiniz</option>
                    <option value="İstanbul">İstanbul</option>
                    <option value="Ankara">Ankara</option>
                    <option value="İzmir">İzmir</option>
                    <option value="Bursa">Bursa</option>
                    <option value="Antalya">Antalya</option>
                    <option value="Konya">Konya</option>
                    <option value="Gaziantep">Gaziantep</option>
                    <option value="Adana">Adana</option>
                    <option value="Diğer">Diğer</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">İlçe <span class="required">*</span></label>
                  <input type="text" class="form-input" id="emcCustomerIlce" placeholder="İlçe" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Posta Kodu</label>
                  <input type="text" class="form-input" id="emcCustomerPostaKodu" placeholder="XXXXX">
                </div>
                <div class="form-group">
                  <label class="form-label">Ülke</label>
                  <select class="form-input" id="emcCustomerUlke">
                    <option value="Türkiye">Türkiye</option>
                    <option value="Almanya">Almanya</option>
                    <option value="İngiltere">İngiltere</option>
                    <option value="ABD">ABD</option>
                    <option value="Diğer">Diğer</option>
                  </select>
                </div>
                <div class="form-group full">
                  <label class="form-label">Sipariş Notu</label>
                  <textarea class="form-input" id="emcCustomerNot" placeholder="Özel bir isteminiz varsa buraya yazın..." rows="3" style="height:80px;resize:none"></textarea>
                </div>
              </div>
              <div class="form-nav">
                <div></div>
                <button type="button" class="form-next-btn emc-step-next" data-next="2">Ödeme Yöntemine Geç<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-7-7 7 7-7 7"/></svg></button>
              </div>
            </div>
          </div>

          <!-- STEP 2: Ödeme Yöntemi -->
          <div class="form-section" id="step2">
            <div class="form-section-header">
              <div class="form-section-icon payment">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
              </div>
              <div class="form-section-title">Ödeme Yöntemi</div>
            </div>
            <div class="form-body">
              <div class="payment-methods" id="payMethods">
                <div class="pay-method active" data-method="card">
                  <div class="pay-method-radio"></div>
                  <div class="pay-method-icon">💳</div>
                  <div class="pay-method-text">
                    <div class="pay-method-name">Kredi / Banka Kartı</div>
                    <div class="pay-method-desc">Visa, Mastercard, Amex — PayTR güvencesi ile</div>
                  </div>
                </div>
                <div class="pay-method" data-method="transfer">
                  <div class="pay-method-radio"></div>
                  <div class="pay-method-icon">🏦</div>
                  <div class="pay-method-text">
                    <div class="pay-method-name">Havale / EFT</div>
                    <div class="pay-method-desc">Banka havalesi ile ödeme</div>
                  </div>
                </div>
              </div>

              <!-- Kart formu (PayTR iframe öncesi bilgi amaçlı önizleme) -->
              <div id="cardSection">
                <div class="card-preview">
                  <div class="card-face">
                    <div class="card-brand" id="cardDisplayBrand">VISA</div>
                    <div class="card-chip"></div>
                    <div class="card-number" id="cardDisplayNum">•••• •••• •••• ••••</div>
                    <div class="card-bottom">
                      <div>
                        <div class="card-holder-label">Kart Sahibi</div>
                        <div class="card-holder-value" id="cardDisplayName">AD SOYAD</div>
                      </div>
                      <div>
                        <div class="card-expiry-label">Son Kullanma</div>
                        <div class="card-expiry-value" id="cardDisplayExpiry">MM/YY</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-grid">
                  <div class="form-group full">
                    <label class="form-label">Kart Üzerindeki İsim <span class="required">*</span></label>
                    <input type="text" class="form-input" id="cardName" placeholder="AHMET YILMAZ">
                  </div>
                  <div class="form-group full">
                    <label class="form-label">Kart Numarası <span class="required">*</span></label>
                    <input type="text" class="form-input" id="cardNumber" placeholder="0000 0000 0000 0000" maxlength="19">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Son Kullanma <span class="required">*</span></label>
                    <input type="text" class="form-input" id="cardExpiry" placeholder="MM/YY" maxlength="5">
                  </div>
                  <div class="form-group">
                    <label class="form-label">CVV <span class="required">*</span></label>
                    <input type="password" class="form-input" id="cardCvv" placeholder="•••" maxlength="4">
                  </div>
                </div>
              </div>

              <div id="transferSection" style="display:none">
                <div class="bank-info">
                  <div class="bank-info-row"><span class="bank-info-label">Banka</span><span class="bank-info-value">Ziraat Bankası</span></div>
                  <div class="bank-info-row"><span class="bank-info-label">Şube</span><span class="bank-info-value">İstanbul / Merkez</span></div>
                  <div class="bank-info-row"><span class="bank-info-label">Hesap Sahibi</span><span class="bank-info-value">Entry Mark Halıcılık A.Ş.</span></div>
                  <div class="bank-info-row"><span class="bank-info-label">IBAN</span><span class="bank-info-value">TR00 0000 0000 0000 0000 0000 00 <button type="button" class="copy-btn" data-copy="TR00000000000000000000000000"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></span></div>
                </div>
              </div>
              <div class="form-nav">
                <button type="button" class="form-back-btn emc-step-prev" data-prev="1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>Geri</button>
                <button type="button" class="form-next-btn emc-step-next" data-next="3">Siparişi İncele<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-7-7 7 7-7 7"/></svg></button>
              </div>
            </div>
          </div>

          <!-- STEP 3: Sipariş Onayı -->
          <div class="form-section" id="step3">
            <div class="form-section-header">
              <div class="form-section-icon review">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
              <div class="form-section-title">Sipariş Onayı</div>
            </div>
            <div class="form-body">
              <div class="review-items" id="reviewItems"></div>
              <div class="review-address-card" id="reviewAddress">
                <div class="review-address-label">Teslimat Adresi</div>
                <div class="review-address-text" id="reviewAddressText">—</div>
              </div>
              <div class="review-address-card" id="reviewPayment">
                <div class="review-address-label">Ödeme Yöntemi</div>
                <div class="review-address-text" id="reviewPaymentText">Kredi / Banka Kartı (PayTR)</div>
              </div>
              <div class="form-nav">
                <button type="button" class="form-back-btn emc-step-prev" data-prev="2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7-7-7 7 7 7"/></svg>Geri</button>
                <button type="submit" class="form-submit-btn" id="emcPaytrBtn">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                  <span class="btn-text">Siparişi Onayla</span>
                  <span class="btn-loader" style="display:none;"><span class="spinner"></span>İşleniyor...</span>
                </button>
              </div>
            </div>
          </div>

        </form>
      </div>

      <!-- Order Summary Sidebar -->
      <div class="order-summary">
        <div class="summary-header"><div class="summary-title">Sipariş Özeti</div></div>
        <div class="summary-items" id="emcOrderItems"></div>
        <div class="summary-pricing">
          <div class="summary-row"><span class="summary-row-label">Ara Toplam</span><span class="summary-row-value" id="emcSubtotal">0,00 TL</span></div>
          <div class="summary-row"><span class="summary-row-label">Kargo</span><span class="summary-row-value" id="emcShipping">0,00 TL</span></div>
          <div class="summary-row"><span class="summary-row-label">KDV (%10)</span><span class="summary-row-value" id="emcTax">0,00 TL</span></div>
          <div class="summary-divider"></div>
          <div class="summary-total-row"><span class="summary-total-label">Toplam</span><span class="summary-total-value" id="emcTotal">0,00 TL</span></div>
        </div>
        <div class="summary-secure"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>SSL ile güvenli ödeme</div>
      </div>
    </div>

    <!-- PayTR iFrame -->
    <div id="emcPaytrIframeWrap" class="paytr-section" style="display:none;">
      <div class="iframe-container">
        <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
        <iframe id="emcPaytrIframe" title="Ödeme"></iframe>
      </div>
    </div>

    <!-- Success Modal -->
    <div id="emcCheckoutSuccess" class="success-modal" aria-hidden="true">
      <div class="success-card">
        <div class="success-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg></div>
        <h2 class="success-title">Siparişiniz Alındı!</h2>
        <p class="success-text">Siparişiniz başarıyla oluşturuldu. Sipariş detaylarınız e-posta adresinize gönderilecektir.</p>
        <div class="success-order-id" id="emcSuccessOrderId">—</div>
        <p class="success-text" style="font-size:13px;">Tasarımcı ekibimiz üretim öncesi onayınız için size özel bir ön izleme sunacaktır.</p>
        <br>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="success-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Anasayfaya Dön</a>
      </div>
    </div>

    <!-- Fail Message -->
    <div id="emcCheckoutFail" class="fail-modal" style="display:none;">
      <div class="success-card fail-card">
        <div class="fail-icon">⚠️</div>
        <h2 class="success-title">Ödeme Başarısız</h2>
        <p class="success-text">Ödeme işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin veya farklı bir ödeme yöntemi kullanın.</p>
        <button type="button" class="success-btn" onclick="location.reload()">Tekrar Dene</button>
      </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><span id="toastMsg"></span></div>

  </div>
</div>
