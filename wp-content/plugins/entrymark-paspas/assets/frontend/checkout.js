/**
 * Entry Mark Paspas - Modern Checkout JavaScript
 * Enhanced checkout experience with loading states and error handling
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

  // DOM Elements
  const $ = (id) => document.getElementById(id);

  // Helper: Format TL
  function formatTL(n) {
    return n.toFixed(2).replace('.', ',') + ' TL';
  }

  // Helper: Get query param
  function getQueryParam(name) {
    const m = window.location.search.match(new RegExp('[?&]' + name + '=([^&]*)'));
    return m ? decodeURIComponent(m[1]) : '';
  }

  // Check for success/fail from PayTR callback
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
    $('emcCheckoutSuccess').style.display = 'block';
    $('emcSuccessOrderId').textContent = '#' + orderId;
    $('emcCheckoutFormWrap').style.display = 'none';
    $('emcCheckoutEmpty').style.display = 'none';
    $('emcPaytrIframeWrap').style.display = 'none';
  }

  function showFail() {
    $('emcCheckoutFail').style.display = 'block';
    $('emcCheckoutFormWrap').style.display = 'none';
    $('emcCheckoutEmpty').style.display = 'none';
    $('emcPaytrIframeWrap').style.display = 'none';
  }

  function showEmpty() {
    $('emcCheckoutEmpty').style.display = 'block';
    $('emcCheckoutFormWrap').style.display = 'none';
  }

  function showForm() {
    $('emcCheckoutEmpty').style.display = 'none';
    $('emcCheckoutFormWrap').style.display = 'block';
  }

  // Load cart items
  async function loadCart() {
    try {
      const response = await fetch(`${restUrl}/cart`, {
        method: 'GET',
        headers: { 'X-WP-Nonce': nonce }
      });

      const data = await response.json();
      const items = data.items || [];

      if (items.length === 0) {
        showEmpty();
        return;
      }

      renderOrderItems(items);
      calculateTotals(items);
      showForm();
    } catch (error) {
      console.error('Error loading cart:', error);
      showEmpty();
    }
  }

  // Render order items
  function renderOrderItems(items) {
    const container = $('emcOrderItems');
    if (!container) return;

    container.innerHTML = items.map(item => {
      const sum = item.summary || {};
      const pr = item.pricing || {};
      const design = item.design || {};
      
      return `
        <div class="order-item">
          <div class="order-item-image">
            ${item.preview_data_url ? 
              `<img src="${item.preview_data_url}" alt="Tasarım">` : 
              ''}
          </div>
          <div class="order-item-details">
            <h4>${sum.texture_name || 'Kişiselleştirilmiş Paspas'}</h4>
            <p>Ölçü: ${sum.size_label || ''}</p>
            <p>Kargo: ${sum.shipping_label || ''}</p>
          </div>
          <div class="order-item-price">${formatTL(pr.total || 0)}</div>
        </div>
      `;
    }).join('');
  }

  // Calculate totals
  function calculateTotals(items) {
    let subtotal = 0;
    let shipping = 0;
    let tax = 0;

    items.forEach(item => {
      const pr = item.pricing || {};
      subtotal += pr.base || 0;
      shipping += pr.shipCost || 0;
      tax += pr.tax || 0;
    });

    const total = subtotal + shipping + tax;

    const subtotalEl = $('emcSubtotal');
    const shippingEl = $('emcShipping');
    const taxEl = $('emcTax');
    const totalEl = $('emcTotal');

    if (subtotalEl) subtotalEl.textContent = formatTL(subtotal);
    if (shippingEl) shippingEl.textContent = formatTL(shipping);
    if (taxEl) taxEl.textContent = formatTL(tax);
    if (totalEl) totalEl.textContent = formatTL(total);
  }

  // Handle form submission
  async function handleSubmit(e) {
    e.preventDefault();

    const btn = $('emcPaytrBtn');
    const btnText = btn.querySelector('.btn-text');
    const btnLoader = btn.querySelector('.btn-loader');

    // Show loading state
    btn.disabled = true;
    if (btnText) btnText.style.display = 'none';
    if (btnLoader) btnLoader.style.display = 'flex';

    const customer = {
      ad: $('emcCustomerAd').value.trim(),
      soyad: $('emcCustomerSoyad').value.trim(),
      email: $('emcCustomerEmail').value.trim(),
      telefon: $('emcCustomerTelefon').value.trim(),
      adres: $('emcCustomerAdres').value.trim()
    };

    // Validate
    if (!customer.ad || !customer.soyad || !customer.email || !customer.telefon || !customer.adres) {
      alert('Lütfen tüm alanları doldurun.');
      resetButton();
      return;
    }

    try {
      const response = await fetch(`${restUrl}/checkout`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': nonce
        },
        body: JSON.stringify({ nonce, customer })
      });

      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || 'Sipariş oluşturulamadı');
      }

      // Handle PayTR iframe
      if (data.paytr_iframe_url) {
        $('emcCheckoutFormWrap').style.display = 'none';
        $('emcPaytrIframeWrap').style.display = 'block';
        
        const iframe = $('emcPaytrIframe');
        iframe.src = data.paytr_iframe_url;

        // Initialize iframe resizer
        if (typeof iFrameResize === 'function') {
          try {
            iFrameResize({}, '#emcPaytrIframe');
          } catch (e) {
            console.warn('iFrameResize error:', e);
          }
        }

        // Scroll to iframe
        $('emcPaytrIframeWrap').scrollIntoView({ behavior: 'smooth' });
      } else {
        // Direct success
        const url = new URL(window.location.href);
        url.searchParams.set('emc_order_success', '1');
        url.searchParams.set('emc_order_id', data.order_id);
        window.location.href = url.toString();
      }
    } catch (error) {
      console.error('Checkout error:', error);
      alert(error.message || 'Bir hata oluştu. Lütfen tekrar deneyin.');
      resetButton();
    }

    function resetButton() {
      btn.disabled = false;
      if (btnText) btnText.style.display = 'block';
      if (btnLoader) btnLoader.style.display = 'none';
    }
  }

  // Initialize
  function init() {
    // Check payment status first
    if (checkPaymentStatus()) return;

    // Load cart
    loadCart();

    // Setup form
    const form = $('emcCheckoutForm');
    if (form) {
      form.addEventListener('submit', handleSubmit);
    }
  }

  // Start when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
