/**
 * Hakkımızda sayfası — scroll reveal ve sayaç animasyonu
 */
(function () {
  var page = document.querySelector('.about-page');
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
    { threshold: 0.15 }
  );
  page.querySelectorAll('.reveal').forEach(function (el) {
    revealObserver.observe(el);
  });

  var counters = page.querySelectorAll('.count');
  var counterObserver = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var target = parseInt(el.getAttribute('data-target'), 10) || 0;
        var duration = 1800;
        var startTime = performance.now();

        function easeOutQuart(x) {
          return 1 - Math.pow(1 - x, 4);
        }

        function update(currentTime) {
          var elapsed = currentTime - startTime;
          var progress = Math.min(elapsed / duration, 1);
          var eased = easeOutQuart(progress);
          el.textContent = Math.round(target * eased).toLocaleString('tr-TR');
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
})();
