/**
 * Entry Mark Carpets - Main JavaScript
 *
 * Header and Footer functionality
 */

(function() {
  'use strict';

  // Wait for DOM to be ready
  document.addEventListener('DOMContentLoaded', function() {
    
    // ─── Sticky header scroll effect ───
    const header = document.getElementById('mainHeader');
    if (header) {
      window.addEventListener('scroll', function() {
        header.classList.toggle('scrolled', window.scrollY > 60);
      });
    }

    // ─── Mega Menu ───
    const megaOverlay = document.getElementById('megaOverlay');
    const navItems = document.querySelectorAll('.nav-item.has-mega');
    let megaTimeout;

    navItems.forEach(function(item) {
      item.addEventListener('mouseenter', function() {
        clearTimeout(megaTimeout);
        navItems.forEach(function(n) { n.classList.remove('open'); });
        item.classList.add('open');
        if (megaOverlay) megaOverlay.classList.add('active');
      });

      item.addEventListener('mouseleave', function() {
        megaTimeout = setTimeout(function() {
          item.classList.remove('open');
          if (megaOverlay) megaOverlay.classList.remove('active');
        }, 200);
      });
    });

    if (megaOverlay) {
      megaOverlay.addEventListener('mouseenter', function() {
        navItems.forEach(function(n) { n.classList.remove('open'); });
        megaOverlay.classList.remove('active');
      });
    }

    // ─── Search Overlay ───
    const searchToggle = document.getElementById('searchToggle');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchClose = document.getElementById('searchClose');

    if (searchToggle && searchOverlay) {
      searchToggle.addEventListener('click', function() {
        searchOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(function() {
          const input = searchOverlay.querySelector('input');
          if (input) input.focus();
        }, 300);
      });
    }

    if (searchClose && searchOverlay) {
      searchClose.addEventListener('click', function() {
        searchOverlay.classList.remove('active');
        document.body.style.overflow = '';
      });
    }

    if (searchOverlay) {
      searchOverlay.addEventListener('click', function(e) {
        if (e.target === searchOverlay) {
          searchOverlay.classList.remove('active');
          document.body.style.overflow = '';
        }
      });
    }

    // Search tags
    const searchTags = document.querySelectorAll('.search-tag');
    searchTags.forEach(function(tag) {
      tag.addEventListener('click', function() {
        const input = document.querySelector('.search-overlay-input');
        if (input) input.value = tag.textContent;
      });
    });

    // ─── Auth Modal ───
    const authModal = document.getElementById('authModal');
    const authClose = document.getElementById('authClose');
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const authTabs = document.querySelectorAll('.auth-tab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const authTitle = document.getElementById('authTitle');
    const authSubtitle = document.getElementById('authSubtitle');

    function openAuth(tab) {
      if (authModal) {
        authModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        switchTab(tab);
      }
    }

    function switchTab(tab) {
      authTabs.forEach(function(t) {
        t.classList.toggle('active', t.dataset.tab === tab);
      });
      if (tab === 'login') {
        if (loginForm) loginForm.style.display = '';
        if (registerForm) registerForm.style.display = 'none';
        if (authTitle) authTitle.textContent = 'Welcome Back';
        if (authSubtitle) authSubtitle.textContent = 'Sign in to your Entry Mark account';
      } else {
        if (loginForm) loginForm.style.display = 'none';
        if (registerForm) registerForm.style.display = '';
        if (authTitle) authTitle.textContent = 'Join Entry Mark';
        if (authSubtitle) authSubtitle.textContent = 'Create your account for exclusive access';
      }
    }

    if (loginBtn) loginBtn.addEventListener('click', function() { openAuth('login'); });
    if (registerBtn) registerBtn.addEventListener('click', function() { openAuth('register'); });
    
    authTabs.forEach(function(tab) {
      tab.addEventListener('click', function() { switchTab(tab.dataset.tab); });
    });

    if (authClose && authModal) {
      authClose.addEventListener('click', function() {
        authModal.classList.remove('active');
        document.body.style.overflow = '';
      });
    }

    if (authModal) {
      authModal.addEventListener('click', function(e) {
        if (e.target === authModal) {
          authModal.classList.remove('active');
          document.body.style.overflow = '';
        }
      });
    }

    // ─── Cart Sidebar ───
    const cartToggle = document.getElementById('cartToggle');
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    const cartClose = document.getElementById('cartClose');

    function openCart() {
      if (cartSidebar) cartSidebar.classList.add('active');
      if (cartOverlay) cartOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeCart() {
      if (cartSidebar) cartSidebar.classList.remove('active');
      if (cartOverlay) cartOverlay.classList.remove('active');
      document.body.style.overflow = '';
    }

    if (cartToggle) cartToggle.addEventListener('click', openCart);
    if (cartClose) cartClose.addEventListener('click', closeCart);
    if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

    // Quantity buttons
    const qtyButtons = document.querySelectorAll('.cart-item-qty button');
    qtyButtons.forEach(function(btn) {
      btn.addEventListener('click', function() {
        const span = this.parentElement.querySelector('span');
        if (span) {
          let val = parseInt(span.textContent, 10);
          if (this.textContent === '+') val++;
          else if (val > 1) val--;
          span.textContent = val;
        }
      });
    });

    // ─── Wishlist toggle heart animation ───
    const wishlistBtn = document.getElementById('wishlistBtn');
    if (wishlistBtn) {
      wishlistBtn.addEventListener('click', function() {
        const svg = wishlistBtn.querySelector('svg');
        if (svg) {
          const isFilled = svg.getAttribute('fill') !== 'none';
          svg.setAttribute('fill', isFilled ? 'none' : 'var(--gold)');
          svg.style.color = isFilled ? 'var(--dark)' : 'var(--gold)';
          wishlistBtn.style.transform = 'scale(1.2)';
          setTimeout(function() {
            wishlistBtn.style.transform = '';
          }, 200);
        }
      });
    }

    // ─── Back to Top ───
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
      window.addEventListener('scroll', function() {
        backToTop.classList.toggle('visible', window.scrollY > 400);
      });

      backToTop.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // ─── Escape key to close overlays ───
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (searchOverlay) searchOverlay.classList.remove('active');
        if (authModal) authModal.classList.remove('active');
        closeCart();
        document.body.style.overflow = '';
      }
    });

  });

})();
