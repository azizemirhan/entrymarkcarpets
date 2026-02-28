/**
 * İletişim sayfası — reveal, FAQ accordion, form validasyonu, toast
 */
(function () {
  var page = document.querySelector('.contact-page');
  if (!page) return;

  var revealObserver = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObserver.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12 }
  );
  page.querySelectorAll('.reveal').forEach(function (el) {
    revealObserver.observe(el);
  });

  page.querySelectorAll('.faq-question').forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.parentElement;
      var wasOpen = item.classList.contains('open');
      page.querySelectorAll('.faq-item').forEach(function (i) {
        i.classList.remove('open');
      });
      if (!wasOpen) {
        item.classList.add('open');
      }
    });
  });

  var form = document.getElementById('contactForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      var name = document.getElementById('cfName');
      var last = document.getElementById('cfLast');
      var email = document.getElementById('cfEmail');
      var subject = document.getElementById('cfSubject');
      var msg = document.getElementById('cfMsg');
      var consent = document.getElementById('cfConsent');
      var valid = true;
      [name, last, email, subject, msg].forEach(function (el) {
        if (el && !el.value.trim()) {
          el.classList.add('error');
          valid = false;
        } else if (el) {
          el.classList.remove('error');
        }
      });
      if (consent && !consent.checked) {
        valid = false;
        showToast('Lütfen gizlilik politikasını kabul edin.');
      }
      if (!valid && consent && consent.checked) {
        showToast('Lütfen zorunlu alanları doldurun.');
      }
      if (!valid) {
        e.preventDefault();
      }
    });
  }

  page.querySelectorAll('.form-input').forEach(function (i) {
    i.addEventListener('focus', function () {
      i.classList.remove('error');
    });
  });

  var toastEl = document.getElementById('toast');
  var toastMsg = document.getElementById('toastMsg');
  function showToast(m) {
    if (!toastEl || !toastMsg) return;
    toastMsg.textContent = m;
    toastEl.classList.add('show');
    clearTimeout(window._contactToastTimer);
    window._contactToastTimer = setTimeout(function () {
      toastEl.classList.remove('show');
    }, 3500);
  }

  var urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('contact_sent') === '1') {
    showToast('Mesajınız başarıyla gönderildi! En kısa sürede dönüş yapacağız.');
    if (window.history && window.history.replaceState) {
      window.history.replaceState({}, '', window.location.pathname + window.location.search.replace(/\?contact_sent=1&?|&?contact_sent=1/, '').replace(/\?$/, '') || '?');
    }
  }
})();
