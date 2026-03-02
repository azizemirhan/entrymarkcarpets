/**
 * Entry Mark Paspas - Ödeme Sayfası (checkout.html tasarımı + 3 adım + PayTR)
 */
(function() {
  'use strict';

  const restData = window.EMC_REST_DATA;
  if (!restData?.rest_url) {
    console.error('EMC_REST_DATA not available');
    return;
  }

  const restUrl = restData.rest_url;
  const nonce = restData.nonce;

  const $ = (id) => document.getElementById(id);

  let currentStep = 1;
  let payMethod = 'card';
  let cartItems = [];

  function formatTL(n) {
    return n.toFixed(2).replace('.', ',') + ' TL';
  }

  function getQueryParam(name) {
    const m = window.location.search.match(new RegExp('[?&]' + name + '=([^&]*)'));
    return m ? decodeURIComponent(m[1]) : '';
  }

  function showToast(msg) {
    const t = $('toast');
    const msgEl = $('toastMsg');
    if (!t || !msgEl) return;
    msgEl.textContent = msg;
    t.classList.add('show');
    clearTimeout(window._emcToastT);
    window._emcToastT = setTimeout(() => t.classList.remove('show'), 3000);
  }

  function checkPaymentStatus() {
    const success = getQueryParam('emc_order_success');
    const fail = getQueryParam('emc_order_fail');
    const orderId = getQueryParam('emc_order_id');
    if (success === '1' && orderId) {
      showSuccess(orderId);
      return true;
    }
    if (fail === '1') {
      showFail();
      return true;
    }
    return false;
  }

  function showSuccess(orderId) {
    if ($('emcCheckoutLoading')) $('emcCheckoutLoading').style.display = 'none';
    const el = $('emcCheckoutSuccess');
    if (el) {
      el.classList.add('active');
      el.setAttribute('aria-hidden', 'false');
    }
    const orderIdEl = $('emcSuccessOrderId');
    if (orderIdEl) orderIdEl.textContent = '#' + orderId;
    const wrap = $('emcCheckoutFormWrap');
    if (wrap) wrap.style.display = 'none';
    const empty = $('emcCheckoutEmpty');
    if (empty) empty.style.display = 'none';
    const iframeWrap = $('emcPaytrIframeWrap');
    if (iframeWrap) iframeWrap.style.display = 'none';
  }

  function showFail() {
    const el = $('emcCheckoutFail');
    if (el) el.style.display = 'flex';
    if ($('emcCheckoutFormWrap')) $('emcCheckoutFormWrap').style.display = 'none';
    if ($('emcCheckoutEmpty')) $('emcCheckoutEmpty').style.display = 'none';
    if ($('emcPaytrIframeWrap')) $('emcPaytrIframeWrap').style.display = 'none';
  }

  function showLoading() {
    if ($('emcCheckoutLoading')) $('emcCheckoutLoading').style.display = 'block';
    if ($('emcCheckoutEmpty')) $('emcCheckoutEmpty').style.display = 'none';
    if ($('emcCheckoutFormWrap')) $('emcCheckoutFormWrap').style.display = 'none';
  }

  function showEmpty() {
    if ($('emcCheckoutLoading')) $('emcCheckoutLoading').style.display = 'none';
    if ($('emcCheckoutEmpty')) $('emcCheckoutEmpty').style.display = 'block';
    if ($('emcCheckoutFormWrap')) $('emcCheckoutFormWrap').style.display = 'none';
  }

  function showForm() {
    if ($('emcCheckoutLoading')) $('emcCheckoutLoading').style.display = 'none';
    if ($('emcCheckoutEmpty')) $('emcCheckoutEmpty').style.display = 'none';
    if ($('emcCheckoutFormWrap')) $('emcCheckoutFormWrap').style.display = 'grid';
  }

  function goToStep(n) {
    currentStep = n;
    document.querySelectorAll('.emc-checkout-wrapper .form-section').forEach(s => s.classList.remove('active'));
    const section = $('step' + n);
    if (section) section.classList.add('active');
    document.querySelectorAll('.emc-checkout-wrapper .step').forEach(s => {
      const sn = parseInt(s.dataset.step, 10);
      s.classList.remove('active', 'completed');
      if (sn === n) s.classList.add('active');
      if (sn < n) s.classList.add('completed');
    });
    window.scrollTo({ top: 0, behavior: 'smooth' });
    if (n === 3) buildReview();
  }

  function validateStep1() {
    const ids = ['emcCustomerAd', 'emcCustomerSoyad', 'emcCustomerEmail', 'emcCustomerTelefon', 'emcCustomerAdres', 'emcCustomerIl', 'emcCustomerIlce'];
    let valid = true;
    ids.forEach(id => {
      const el = $(id);
      if (!el) return;
      if (!el.value.trim()) { el.classList.add('error'); valid = false; } else el.classList.remove('error');
    });
    if (!valid) showToast('Lütfen zorunlu alanları doldurun');
    return valid;
  }

  function validateStep2() {
    if (payMethod !== 'card') return true;
    const ids = ['cardName', 'cardNumber', 'cardExpiry', 'cardCvv'];
    let valid = true;
    ids.forEach(id => {
      const el = $(id);
      if (!el) return;
      const v = el.value.trim();
      if (!v) { el.classList.add('error'); valid = false; } else el.classList.remove('error');
    });
    const numEl = $('cardNumber');
    if (numEl && numEl.value.replace(/\D/g, '').length < 15) {
      if (numEl) numEl.classList.add('error');
      valid = false;
    }
    if (!valid) showToast('Lütfen kart bilgilerini doldurun');
    return valid;
  }

  function buildReview() {
    const reviewItems = $('reviewItems');
    if (reviewItems && cartItems.length) {
      reviewItems.innerHTML = cartItems.map(item => {
        const sum = item.summary || {};
        const pr = item.pricing || {};
        const preview = item.preview_data_url ? `<img src="${item.preview_data_url}" alt="">` : '';
        return `<div class="review-item">
          <div class="review-item-img">${preview}</div>
          <div class="review-item-info">
            <div class="review-item-name">${sum.texture_name || 'Kişiselleştirilmiş Paspas'}</div>
            <div class="review-item-meta">${sum.size_label || ''} · ${sum.shipping_label || ''}</div>
          </div>
          <div class="review-item-price">${formatTL(pr.total || 0)}</div>
        </div>`;
      }).join('');
    }
    const addr = $('reviewAddressText');
    if (addr) {
      const ad = ($('emcCustomerAd') && $('emcCustomerAd').value.trim()) || '';
      const soyad = ($('emcCustomerSoyad') && $('emcCustomerSoyad').value.trim()) || '';
      const adres = ($('emcCustomerAdres') && $('emcCustomerAdres').value.trim()) || '';
      const il = ($('emcCustomerIl') && $('emcCustomerIl').value.trim()) || '';
      const ilce = ($('emcCustomerIlce') && $('emcCustomerIlce').value.trim()) || '';
      const posta = ($('emcCustomerPostaKodu') && $('emcCustomerPostaKodu').value.trim()) || '';
      const tel = ($('emcCustomerTelefon') && $('emcCustomerTelefon').value.trim()) || '';
      const parts = [];
      if (ad || soyad) parts.push('<strong>' + ad + ' ' + soyad + '</strong>');
      if (adres) parts.push(adres);
      if (ilce || il) parts.push((ilce && il ? ilce + ' / ' + il : ilce || il) + (posta ? ' ' + posta : ''));
      if (tel) parts.push(tel);
      addr.innerHTML = parts.length ? parts.join('<br>') : '—';
    }
    const pay = $('reviewPaymentText');
    if (pay) {
      if (payMethod === 'card') {
        const numEl = $('cardNumber');
        const nameEl = $('cardName');
        const last4 = numEl && numEl.value ? numEl.value.replace(/\D/g, '').slice(-4) : '';
        const name = nameEl && nameEl.value ? nameEl.value.trim() : '';
        pay.innerHTML = last4 ? 'Kredi Kartı · •••• ' + last4 + (name ? '<br>' + name : '') : 'Kredi / Banka Kartı (PayTR)';
      } else {
        pay.textContent = 'Havale / EFT · Ziraat Bankası';
      }
    }
  }

  async function loadCart() {
    showLoading();
    const headers = { 'X-WP-Nonce': nonce };
    if (restData.cart_id) headers['X-EMC-Cart-ID'] = restData.cart_id;
    try {
      const response = await fetch(`${restUrl}/cart`, {
        method: 'GET',
        credentials: 'include',
        headers
      });
      const data = await response.json();
      const items = Array.isArray(data.items) ? data.items : [];
      if (items.length === 0) {
        showEmpty();
        return;
      }
      cartItems = items;
      renderOrderItems(items);
      calculateTotals(items);
      showForm();
    } catch (err) {
      console.error('Error loading cart:', err);
      showEmpty();
      showToast('Sepet yüklenemedi. Sayfayı yenileyin veya sepet sayfasından tekrar deneyin.');
    }
  }

  function renderOrderItems(items) {
    const container = $('emcOrderItems');
    if (!container) return;
    container.innerHTML = items.map(item => {
      const sum = item.summary || {};
      const pr = item.pricing || {};
      const preview = item.preview_data_url ? `<img src="${item.preview_data_url}" alt="">` : '';
      return `<div class="summary-mini-item">
        <div class="summary-mini-img">${preview}</div>
        <div class="summary-mini-info">
          <div class="summary-mini-name">${sum.texture_name || 'Kişiselleştirilmiş Paspas'}</div>
          <div class="summary-mini-meta">${sum.size_label || ''} · ${sum.shipping_label || ''}</div>
        </div>
        <div class="summary-mini-price">${formatTL(pr.total || 0)}</div>
      </div>`;
    }).join('');
  }

  function calculateTotals(items) {
    let subtotal = 0, shipping = 0, tax = 0;
    items.forEach(item => {
      const pr = item.pricing || {};
      subtotal += pr.base || 0;
      shipping += pr.shipCost || 0;
      tax += pr.tax || 0;
    });
    const total = subtotal + shipping + tax;
    if ($('emcSubtotal')) $('emcSubtotal').textContent = formatTL(subtotal);
    const shipEl = $('emcShipping');
    if (shipEl) {
      shipEl.textContent = shipping === 0 ? 'Ücretsiz' : formatTL(shipping);
      shipEl.classList.toggle('free', shipping === 0);
    }
    if ($('emcTax')) $('emcTax').textContent = formatTL(tax);
    if ($('emcTotal')) $('emcTotal').textContent = formatTL(total);
  }

  async function handleSubmit(e) {
    e.preventDefault();
    if (currentStep !== 3) return;
    if (!validateStep1()) return;

    const btn = $('emcPaytrBtn');
    const btnText = btn && btn.querySelector('.btn-text');
    const btnLoader = btn && btn.querySelector('.btn-loader');
    if (btn) btn.disabled = true;
    if (btnText) btnText.style.display = 'none';
    if (btnLoader) btnLoader.style.display = 'flex';

    const adresLine = ($('emcCustomerAdres') && $('emcCustomerAdres').value.trim()) || '';
    const ilce = ($('emcCustomerIlce') && $('emcCustomerIlce').value.trim()) || '';
    const il = ($('emcCustomerIl') && $('emcCustomerIl').value.trim()) || '';
    const posta = ($('emcCustomerPostaKodu') && $('emcCustomerPostaKodu').value.trim()) || '';
    let adresFull = adresLine;
    if (ilce || il) adresFull += (adresFull ? ', ' : '') + (ilce && il ? ilce + ' / ' + il : ilce || il);
    if (posta) adresFull += (adresFull ? ' ' : '') + posta;

    const customer = {
      ad: ($('emcCustomerAd') && $('emcCustomerAd').value.trim()) || '',
      soyad: ($('emcCustomerSoyad') && $('emcCustomerSoyad').value.trim()) || '',
      email: ($('emcCustomerEmail') && $('emcCustomerEmail').value.trim()) || '',
      telefon: ($('emcCustomerTelefon') && $('emcCustomerTelefon').value.trim()) || '',
      adres: adresFull || adresLine
    };

    const checkoutHeaders = { 'Content-Type': 'application/json', 'X-WP-Nonce': nonce };
    if (restData.cart_id) checkoutHeaders['X-EMC-Cart-ID'] = restData.cart_id;
    try {
      const response = await fetch(`${restUrl}/checkout`, {
        method: 'POST',
        credentials: 'include',
        headers: checkoutHeaders,
        body: JSON.stringify({ nonce, customer })
      });
      const data = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || 'Sipariş oluşturulamadı');

      if (data.paytr_iframe_url) {
        if ($('emcCheckoutFormWrap')) $('emcCheckoutFormWrap').style.display = 'none';
        if ($('emcPaytrIframeWrap')) $('emcPaytrIframeWrap').style.display = 'block';
        const iframe = $('emcPaytrIframe');
        if (iframe) iframe.src = data.paytr_iframe_url;
        if (typeof iFrameResize === 'function') {
          try { iFrameResize({}, '#emcPaytrIframe'); } catch (err) { console.warn('iFrameResize:', err); }
        }
        if ($('emcPaytrIframeWrap')) $('emcPaytrIframeWrap').scrollIntoView({ behavior: 'smooth' });
      } else {
        const url = new URL(window.location.href);
        url.searchParams.set('emc_order_success', '1');
        url.searchParams.set('emc_order_id', data.order_id);
        window.location.href = url.toString();
      }
    } catch (err) {
      console.error('Checkout error:', err);
      showToast(err.message || 'Bir hata oluştu. Lütfen tekrar deneyin.');
    }
    if (btn) btn.disabled = false;
    if (btnText) btnText.style.display = 'block';
    if (btnLoader) btnLoader.style.display = 'none';
  }

  function init() {
    if (checkPaymentStatus()) return;
    loadCart();

    const form = $('emcCheckoutForm');
    if (form) form.addEventListener('submit', handleSubmit);

    document.querySelectorAll('.emc-checkout-wrapper .emc-step-next').forEach(btn => {
      btn.addEventListener('click', () => {
        const next = parseInt(btn.dataset.next, 10);
        if (currentStep === 1 && !validateStep1()) return;
        if (currentStep === 2 && !validateStep2()) return;
        goToStep(next);
      });
    });
    document.querySelectorAll('.emc-checkout-wrapper .emc-step-prev').forEach(btn => {
      btn.addEventListener('click', () => goToStep(parseInt(btn.dataset.prev, 10)));
    });
    document.querySelectorAll('.emc-checkout-wrapper .step').forEach(s => {
      s.addEventListener('click', () => {
        const sn = parseInt(s.dataset.step, 10);
        if (sn < currentStep) goToStep(sn);
      });
    });

    document.querySelectorAll('.emc-checkout-wrapper .pay-method').forEach(m => {
      m.addEventListener('click', () => {
        document.querySelectorAll('.emc-checkout-wrapper .pay-method').forEach(p => p.classList.remove('active'));
        m.classList.add('active');
        payMethod = m.dataset.method || 'card';
        const cardSection = $('cardSection');
        const transferSection = $('transferSection');
        if (cardSection) cardSection.style.display = payMethod === 'card' ? 'block' : 'none';
        if (transferSection) transferSection.style.display = payMethod === 'transfer' ? 'block' : 'none';
      });
    });

    // Kart canlı önizleme
    const cardNumberEl = $('cardNumber');
    const cardNameEl = $('cardName');
    const cardExpiryEl = $('cardExpiry');
    const cardDisplayNum = $('cardDisplayNum');
    const cardDisplayName = $('cardDisplayName');
    const cardDisplayExpiry = $('cardDisplayExpiry');
    const cardDisplayBrand = $('cardDisplayBrand');
    if (cardNumberEl && cardDisplayNum) {
      cardNumberEl.addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '').substring(0, 16);
        let formatted = v.replace(/(.{4})/g, '$1 ').trim();
        this.value = formatted;
        let display = formatted || '•••• •••• •••• ••••';
        while (display.replace(/\s/g, '').length < 16) display += '•';
        cardDisplayNum.textContent = display.replace(/(.{4})/g, '$1 ').trim().substring(0, 19);
        if (cardDisplayBrand) {
          if (v.startsWith('4')) cardDisplayBrand.textContent = 'VISA';
          else if (v.startsWith('5')) cardDisplayBrand.textContent = 'MC';
          else if (v.startsWith('3')) cardDisplayBrand.textContent = 'AMEX';
          else cardDisplayBrand.textContent = 'CARD';
        }
      });
    }
    if (cardNameEl && cardDisplayName) {
      cardNameEl.addEventListener('input', function() {
        cardDisplayName.textContent = this.value.toUpperCase() || 'AD SOYAD';
      });
    }
    if (cardExpiryEl && cardDisplayExpiry) {
      cardExpiryEl.addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '').substring(0, 4);
        if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2);
        this.value = v;
        cardDisplayExpiry.textContent = v || 'MM/YY';
      });
    }

    document.querySelectorAll('.emc-checkout-wrapper .copy-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const text = btn.dataset.copy || '';
        if (navigator.clipboard && text) {
          navigator.clipboard.writeText(text).then(() => showToast('IBAN kopyalandı'));
        }
      });
    });

    document.querySelectorAll('.emc-checkout-wrapper .form-input').forEach(inp => {
      inp.addEventListener('focus', () => inp.classList.remove('error'));
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
