/**
 * Anasayfa — Teknik Özellikler: scroll reveal, sayaç animasyonu, stagger
 */
(function () {
  var section = document.querySelector('.specs-section');
  if (!section) return;

  // Scroll reveal
  var reveals = section.querySelectorAll('.reveal');
  var revealObserver = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObserver.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 }
  );
  reveals.forEach(function (el) {
    revealObserver.observe(el);
  });

  // Counter animation
  var counters = section.querySelectorAll('.count');
  var counterObserver = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var target = parseInt(el.getAttribute('data-target'), 10) || 0;
        var duration = 1800;
        var startTime = performance.now();

        function easeOutQuart(t) {
          return 1 - Math.pow(1 - t, 4);
        }

        function update(currentTime) {
          var elapsed = currentTime - startTime;
          var progress = Math.min(elapsed / duration, 1);
          var eased = easeOutQuart(progress);
          el.textContent = Math.round(target * eased);
          if (progress < 1) {
            requestAnimationFrame(update);
          }
        }

        requestAnimationFrame(update);
        counterObserver.unobserve(el);
      });
    },
    { threshold: 0.5 }
  );
  counters.forEach(function (c) {
    counterObserver.observe(c);
  });

  // Stagger spec items
  var specItems = section.querySelectorAll('.spec-item');
  var itemObserver = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var panel = entry.target.closest('.spec-panel');
        if (!panel || panel.dataset.animated) return;
        panel.dataset.animated = 'true';
        var items = panel.querySelectorAll('.spec-item');
        items.forEach(function (item, i) {
          item.style.opacity = '0';
          item.style.transform = 'translateX(-12px)';
          item.style.transition =
            'opacity 0.5s ' + i * 0.06 + 's ease, transform 0.5s ' + i * 0.06 + 's ease';
          requestAnimationFrame(function () {
            requestAnimationFrame(function () {
              item.style.opacity = '1';
              item.style.transform = 'translateX(0)';
            });
          });
        });
        itemObserver.unobserve(entry.target);
      });
    },
    { threshold: 0.2 }
  );
  specItems.forEach(function (item) {
    itemObserver.observe(item);
  });

  // Stagger cert cards
  var certCards = section.querySelectorAll('.cert-card');
  if (certCards.length) {
    var certObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          certCards.forEach(function (card, i) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(16px)';
            card.style.transition =
              'opacity 0.5s ' +
              i * 0.08 +
              's ease, transform 0.5s ' +
              i * 0.08 +
              's ease, box-shadow 0.4s ease, border-color 0.4s ease';
            requestAnimationFrame(function () {
              requestAnimationFrame(function () {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
              });
            });
          });
          certObserver.unobserve(entry.target);
        });
      },
      { threshold: 0.2 }
    );
    certObserver.observe(certCards[0]);
  }
})();
