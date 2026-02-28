/**
 * Entry Mark Paspas - Cart Page JavaScript
 */

(function() {
  'use strict';

  // Toast notification
  let toastTimeout;
  function showToast(message) {
    const toast = document.getElementById('emcToast');
    const msgEl = document.getElementById('emcToastMsg');
    if (!toast || !msgEl) return;
    
    msgEl.textContent = message;
    toast.classList.add('show');
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => toast.classList.remove('show'), 3000);
  }

  // Update cart item quantity
  function updateQuantity(cartId, action) {
    console.log('updateQuantity called:', cartId, action);
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.location.href;
    form.style.display = 'none';
    form.acceptCharset = 'UTF-8';
    
    const fields = {
      'emc_cart_action': 'update_qty',
      'cart_id': cartId,
      'action': action
    };
    
    for (const [key, value] of Object.entries(fields)) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = key;
      input.value = value;
      form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
  }

  // Modal handling
  let pendingRemove = null;
  const modal = document.getElementById('emcDeleteModal');
  const modalCancel = document.getElementById('emcModalCancel');
  const modalConfirm = document.getElementById('emcModalConfirm');
  
  function openModal(cartId, element) {
    pendingRemove = { cartId, element };
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  
  function closeModal() {
    modal.classList.remove('active');
    document.body.style.overflow = '';
    pendingRemove = null;
  }
  
  function confirmRemove() {
    if (!pendingRemove) return;
    const { cartId, element } = pendingRemove;
    
    element.classList.add('removing');
    closeModal();
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.location.href;
    form.style.display = 'none';
    
    const fields = {
      'emc_cart_action': 'remove',
      'cart_id': cartId
    };
    
    for (const [key, value] of Object.entries(fields)) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = key;
      input.value = value;
      form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
  }
  
  // Modal event listeners
  if (modalCancel) modalCancel.addEventListener('click', closeModal);
  if (modalConfirm) modalConfirm.addEventListener('click', confirmRemove);
  if (modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
    });
  }
  
  // Remove cart item
  function removeItem(cartId, element) {
    openModal(cartId, element);
  }

  // Clear cart
  function clearCart() {
    if (!confirm('Sepetinizdeki tüm ürünler silinecek. Emin misiniz?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.location.href;
    form.style.display = 'none';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'emc_cart_action';
    input.value = 'clear';
    form.appendChild(input);
    
    document.body.appendChild(form);
    form.submit();
  }

  // Apply coupon
  function applyCoupon() {
    const input = document.getElementById('couponInput');
    const msg = document.getElementById('couponMsg');
    const msgText = document.getElementById('couponMsgText');
    
    if (!input || !msg || !msgText) return;
    
    const code = input.value.trim().toUpperCase();
    
    if (code === 'ENTRYMARK10') {
      msg.className = 'emc-coupon-msg success';
      msgText.textContent = '%10 indirim uygulandı!';
      showToast('%10 indirim kuponu uygulandı!');
    } else if (code === 'WELCOME20') {
      msg.className = 'emc-coupon-msg success';
      msgText.textContent = '%20 hoş geldin indirimi uygulandı!';
      showToast('%20 indirim kuponu uygulandı!');
    } else {
      msg.className = 'emc-coupon-msg error';
      msgText.textContent = 'Geçersiz kupon kodu';
    }
  }

  // Initialize cart page
  function init() {
    console.log('Cart JS initialized');
    
    // Quantity buttons
    document.querySelectorAll('.emc-qty-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const cartId = this.dataset.id;
        const action = this.dataset.action;
        console.log('Qty button clicked:', cartId, action);
        if (cartId && action) {
          updateQuantity(cartId, action);
        }
      });
    });

    // Remove buttons
    document.querySelectorAll('.emc-cart-remove').forEach(btn => {
      btn.addEventListener('click', function() {
        const cartId = this.dataset.id;
        const item = this.closest('.emc-cart-item');
        console.log('Remove button clicked:', cartId);
        if (cartId && item) {
          removeItem(cartId, item);
        }
      });
    });

    // Clear cart button
    const clearBtn = document.getElementById('clearCartBtn');
    if (clearBtn) {
      clearBtn.addEventListener('click', clearCart);
    }

    // Coupon apply button
    const couponBtn = document.getElementById('couponApply');
    if (couponBtn) {
      couponBtn.addEventListener('click', applyCoupon);
    }

    // Coupon input enter key
    const couponInput = document.getElementById('couponInput');
    if (couponInput) {
      couponInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          applyCoupon();
        }
      });
    }
    
    console.log('Cart event listeners bound');
  }

  // Run on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
