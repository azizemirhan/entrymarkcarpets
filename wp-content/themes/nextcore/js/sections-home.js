/**
 * Anasayfa bölümleri — Hero renk noktaları, parallax, CTA animasyon, smooth scroll
 */
(function() {
	'use strict';

	function init() {
		var dots = document.querySelectorAll('.color-dot');
		var showcaseInner = document.getElementById('showcaseInner');
		var colorLabel = document.getElementById('colorLabel');
		if (!dots.length || !showcaseInner || !colorLabel) return;

		// Renk noktası tıklama
		dots.forEach(function(dot) {
			dot.addEventListener('click', function() {
				dots.forEach(function(d) { d.classList.remove('active'); });
				dot.classList.add('active');
				var color = dot.getAttribute('data-color');
				var name = dot.getAttribute('data-name');
				showcaseInner.style.setProperty('--preview-color', color);
				showcaseInner.style.background = color;
				colorLabel.textContent = name;
				colorLabel.style.color = 'rgba(255,255,255,0.6)';
				setTimeout(function() { colorLabel.style.color = ''; }, 400);
			});
		});

		// Parallax (hero visual)
		var heroVisual = document.querySelector('.hero-visual');
		var showcaseCard = document.getElementById('showcaseCard');
		var floatCards = document.querySelectorAll('.float-card');
		if (heroVisual && showcaseCard) {
			heroVisual.addEventListener('mousemove', function(e) {
				var rect = heroVisual.getBoundingClientRect();
				var x = (e.clientX - rect.left) / rect.width - 0.5;
				var y = (e.clientY - rect.top) / rect.height - 0.5;
				showcaseCard.style.transform = 'perspective(800px) rotateY(' + (x * 8) + 'deg) rotateX(' + (-y * 6) + 'deg) translateY(-8px) scale(1.02)';
				floatCards.forEach(function(fc, i) {
					var factor = (i + 1) * 8;
					fc.style.transform = 'translate(' + (x * factor) + 'px, ' + (y * factor) + 'px)';
				});
			});
			heroVisual.addEventListener('mouseleave', function() {
				showcaseCard.style.transform = '';
				floatCards.forEach(function(fc) { fc.style.transform = ''; });
			});
		}

		// CTA bölümü — step kartları görünürlük
		var stepCards = document.querySelectorAll('.step-card');
		var ctaSection = document.querySelector('.cta-section');
		if (stepCards.length && ctaSection) {
			var ctaObserver = new IntersectionObserver(function(entries) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						stepCards.forEach(function(card, i) {
							setTimeout(function() { card.style.opacity = '1'; }, i * 150);
						});
						ctaObserver.unobserve(entry.target);
					}
				});
			}, { threshold: 0.15 });
			stepCards.forEach(function(card, i) {
				card.style.opacity = '0';
				card.style.transition = 'opacity 0.6s ' + (i * 0.15) + 's ease, transform 0.6s ' + (i * 0.15) + 's ease, box-shadow 0.4s ease, border-color 0.4s ease';
			});
			ctaObserver.observe(ctaSection);
		}

		// Smooth scroll (#how-it-works vb.)
		document.querySelectorAll('a[href^="#"]').forEach(function(link) {
			link.addEventListener('click', function(e) {
				var href = link.getAttribute('href');
				if (href === '#') return;
				var target = document.querySelector(href);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
			});
		});

		// Otomatik renk döngüsü (isteğe bağlı)
		var autoColorIdx = 0;
		var autoColorInterval = setInterval(function() {
			if (document.hidden) return;
			autoColorIdx = (autoColorIdx + 1) % dots.length;
			dots[autoColorIdx].click();
		}, 4000);
		dots.forEach(function(dot) {
			dot.addEventListener('click', function() {
				clearInterval(autoColorInterval);
			}, { once: true });
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
