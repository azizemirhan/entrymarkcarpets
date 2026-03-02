(function () {
  'use strict';

  var cfg = window.nextcoreGallery || {};
  var customizerUrl = cfg.customizerUrl || '';
  var INITIAL_COUNT = Math.max(1, parseInt(cfg.initialCount, 10) || 8);
  var loadMoreText = cfg.loadMoreText || 'Daha Fazla Göster';
  var counterFormat = cfg.counterFormat || '%shown% / %total% ürün gösteriliyor';
  var tagByCategory = cfg.tagByCategory || { hotel: 'OTEL KOLEKSİYONU', office: 'OFİS KOLEKSİYONU', custom: 'ÖZEL TASARIM', residential: 'KONUT KOLEKSİYONU' };
  var tagDefault = cfg.tagDefault || 'KOLEKSİYON';
  var badgeLabels = { new: cfg.badgeNew || 'Yeni', popular: cfg.badgePopular || 'Popüler', sale: cfg.badgeSale || 'İndirim' };

  function getTagLabel(cat) {
    return tagByCategory[cat] || tagDefault;
  }

  function getBadgeLabel(badge) {
    return badgeLabels[badge] || '';
  }

  var defaultItems = [
    { id: 1, title: 'Aegean Luxe Ocean', collection: 'Aegean Luxe Series', category: 'hotel', color: '#1B4F8A', colors: ['#1B4F8A', '#0D47A1', '#2D3748'], material: 'Yün Karışım', size: '200 × 300 cm', thickness: '14mm', price: '₺4.250', desc: 'Akdeniz kıyılarının derinliklerinden ilham alan, otel lobileri için tasarlanmış lüks halı.', badge: 'new', layout: 'featured', bg: 'linear-gradient(135deg,#16354d,#1B4F8A,#2a6ab0)' },
    { id: 2, title: 'Urban Slate Charcoal', collection: 'Urban Slate', category: 'office', color: '#4a4a4a', colors: ['#4a4a4a', '#2D2D2D', '#696969'], material: 'Naylon', size: '100 × 150 cm', thickness: '10mm', price: '₺1.890', desc: 'Minimalist ofis alanları için tasarlanmış kurumsal halı.', badge: null, layout: 'normal', bg: 'linear-gradient(135deg,#3a3a3a,#555,#6a6a6a)' },
    { id: 3, title: 'Anatolian Heritage Gold', collection: 'Anatolian Heritage', category: 'residential', color: '#C4A265', colors: ['#C4A265', '#8B7355', '#DDA944'], material: '%100 Yün', size: '160 × 230 cm', thickness: '16mm', price: '₺5.670', desc: 'Anadolu motiflerinden ilham alan el yapımı koleksiyon.', badge: 'popular', layout: 'tall', bg: 'linear-gradient(135deg,#8a7040,#C4A265,#dbb870)' },
    { id: 4, title: 'Nordic Frost White', collection: 'Nordic Frost', category: 'residential', color: '#C0C0C0', colors: ['#C0C0C0', '#E8E8E8', '#A9A9A9'], material: 'Polipropilen', size: '120 × 170 cm', thickness: '8mm', price: '₺1.340', desc: 'İskandinav tasarım felsefesiyle üretilen konut halısı.', badge: null, layout: 'normal', bg: 'linear-gradient(135deg,#a0a0a0,#c8c8c8,#e0e0e0)' },
    { id: 5, title: 'Forest Emerald', collection: 'Nature Collection', category: 'hotel', color: '#2E5A1C', colors: ['#2E5A1C', '#355E3B', '#556B2F'], material: 'Yün Karışım', size: '250 × 350 cm', thickness: '14mm', price: '₺7.890', desc: 'Doğanın yeşilinden esinlenen premium halı.', badge: null, layout: 'wide', bg: 'linear-gradient(135deg,#1e3d12,#2E5A1C,#4a7a30)' },
    { id: 6, title: 'Royal Bordo', collection: 'Classic Elegance', category: 'hotel', color: '#800020', colors: ['#800020', '#5C0015', '#A0002A'], material: 'İpek Dokunuş', size: '200 × 300 cm', thickness: '18mm', price: '₺9.450', desc: '5 yıldızlı otel süitleri için ultra lüks koleksiyon.', badge: null, layout: 'normal', bg: 'linear-gradient(135deg,#4a0012,#800020,#a02040)' },
    { id: 7, title: 'Custom Logo Mat Pro', collection: 'Designer Lab', category: 'custom', color: '#1a1a1e', colors: ['#1a1a1e', '#4a4a4a', '#1B4F8A'], material: 'Polipropilen', size: 'Özel Ölçü', thickness: '12mm', price: "₺573'den", desc: 'Logolu özel tasarım paspas.', badge: null, layout: 'tall', bg: 'linear-gradient(135deg,#111115,#1a1a1e,#2a2a30)' },
    { id: 8, title: 'Petrol Harmony', collection: 'Modern Collection', category: 'office', color: '#008B8B', colors: ['#008B8B', '#006060', '#40E0D0'], material: 'Naylon Karışım', size: '150 × 200 cm', thickness: '10mm', price: '₺2.780', desc: 'Modern ofis alanlarına enerji katan kurumsal halı.', badge: 'new', layout: 'normal', bg: 'linear-gradient(135deg,#005858,#008B8B,#20b0b0)' },
    { id: 9, title: 'Sunrise Orange', collection: 'Vibrant Series', category: 'custom', color: '#FF8C00', colors: ['#FF8C00', '#f5a524', '#DDA944'], material: 'Polipropilen', size: 'Özel Ölçü', thickness: '10mm', price: "₺680'den", desc: 'Mağaza girişleri için logo paspas.', badge: 'sale', layout: 'normal', bg: 'linear-gradient(135deg,#c06800,#FF8C00,#ffaa30)' },
    { id: 10, title: 'Midnight Navy', collection: 'Executive Line', category: 'office', color: '#0D47A1', colors: ['#0D47A1', '#1B4F8A', '#2D3748'], material: 'Yün Karışım', size: '200 × 250 cm', thickness: '14mm', price: '₺4.890', desc: 'Yönetici ofisleri için sofistike lacivert halı.', badge: null, layout: 'wide', bg: 'linear-gradient(135deg,#08285a,#0D47A1,#2060c0)' },
    { id: 11, title: 'Sahara Beige', collection: 'Nature Collection', category: 'residential', color: '#C4A265', colors: ['#C4A265', '#D2B48C', '#DDA944'], material: '%100 Yün', size: '140 × 200 cm', thickness: '16mm', price: '₺3.450', desc: 'Doğal yün halı.', badge: null, layout: 'normal', bg: 'linear-gradient(135deg,#a08040,#c4a265,#dcc090)' },
    { id: 12, title: 'Custom Round Designer', collection: 'Designer Lab', category: 'custom', color: '#CC3366', colors: ['#CC3366', '#DA70D6', '#800020'], material: 'İpek Dokunuş', size: 'Ø 150 cm', thickness: '12mm', price: '₺2.150', desc: 'Yuvarlak formda tasarım paspas.', badge: null, layout: 'normal', bg: 'linear-gradient(135deg,#8a1a40,#CC3366,#e05080)' }
  ];

  var items = (cfg.items && Array.isArray(cfg.items) && cfg.items.length > 0) ? cfg.items : defaultItems;
  items = items.map(function (it, idx) {
    var id = it.id != null ? it.id : idx + 1;
    var color = it.color || '#2D3748';
    var colors = Array.isArray(it.colors) ? it.colors : [color];
    var bg = it.bg || ('linear-gradient(135deg,' + color + ',' + color + ')');
    return {
      id: id,
      title: it.title || '',
      collection: it.collection || '',
      category: it.category || 'custom',
      color: color,
      colors: colors,
      material: it.material || '',
      size: it.size || '',
      thickness: it.thickness || '',
      price: it.price || '',
      desc: it.desc || '',
      badge: it.badge || null,
      layout: it.layout || 'normal',
      bg: bg
    };
  });

  var currentFilter = 'all';
  var showAll = false;

  var grid = document.getElementById('galleryGrid');
  if (!grid) return;

  function createCard(item) {
    var card = document.createElement('div');
    var layoutClass = item.layout === 'tall' ? ' tall' : item.layout === 'wide' ? ' wide' : item.layout === 'featured' ? ' featured' : '';
    card.className = 'gallery-card' + layoutClass;
    card.dataset.category = item.category;
    card.dataset.id = item.id;

    var isRound = item.title.indexOf('Round') !== -1;
    var badgeHtml = item.badge ? '<div class="card-badge ' + item.badge + '">' + getBadgeLabel(item.badge) + '</div>' : '';

    card.innerHTML =
      '<div class="card-visual">' +
        '<div class="card-bg" style="background:' + item.bg + ';"></div>' +
        '<div class="carpet-preview" style="background:' + item.color + '; border-radius:' + (isRound ? '50%' : '10px') + ';">' +
          '<div class="carpet-preview-texture"></div>' +
          '<div class="carpet-preview-frame" style="border-radius:' + (isRound ? '50%' : '7px') + ';"></div>' +
          '<div class="carpet-preview-logo"><svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>' +
        '</div>' +
      '</div>' +
      badgeHtml +
      '<div class="card-overlay">' +
        '<div class="card-overlay-tag">' + item.collection + '</div>' +
        '<div class="card-overlay-title">' + item.title + '</div>' +
        '<div class="card-overlay-meta">' +
          '<span>' + item.material + '</span>' +
          '<span class="card-meta-dot"></span>' +
          '<span>' + item.size + '</span>' +
          '<span class="card-meta-dot"></span>' +
          '<span style="color:var(--gal-gold);font-weight:600;">' + item.price + '</span>' +
        '</div>' +
        '<div class="card-overlay-actions">' +
          (customizerUrl ? '<a href="' + customizerUrl + '" class="card-action-btn primary-action">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>' +
            (cfg.btnCustomize || 'Özelleştir') + '</a>' : '') +
          '<button type="button" class="card-action-btn" data-action="view" title="Detay">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>' +
          '</button>' +
          '<button type="button" class="card-action-btn" data-action="fav" title="Favori">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>' +
          '</button>' +
        '</div>' +
      '</div>';

    var viewBtn = card.querySelector('[data-action="view"]');
    if (viewBtn) viewBtn.addEventListener('click', function (e) { e.stopPropagation(); openLightbox(item); });
    var favBtn = card.querySelector('[data-action="fav"]');
    if (favBtn) {
      favBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        var svg = e.currentTarget.querySelector('svg');
        var filled = svg.getAttribute('fill') !== 'none';
        svg.setAttribute('fill', filled ? 'none' : 'var(--gal-gold)');
        svg.style.color = filled ? '' : 'var(--gal-gold)';
      });
    }
    card.addEventListener('click', function () { openLightbox(item); });

    return card;
  }

  function renderGrid() {
    grid.innerHTML = '';
    var filtered = currentFilter === 'all' ? items.slice() : items.filter(function (i) { return i.category === currentFilter; });
    var visible = showAll ? filtered : filtered.slice(0, INITIAL_COUNT);

    visible.forEach(function (item, i) {
      var card = createCard(item);
      card.style.transitionDelay = (i * 0.08) + 's';
      grid.appendChild(card);
      requestAnimationFrame(function () {
        requestAnimationFrame(function () { card.classList.add('visible'); });
      });
    });

    var counterWrap = document.getElementById('galleryCounterWrap');
    var counterTextEl = document.getElementById('galleryCounterText');
    if (counterTextEl && counterFormat) {
      counterTextEl.textContent = counterFormat.replace(/%shown%/g, visible.length).replace(/%total%/g, filtered.length);
    }
    var loadBtn = document.getElementById('loadMoreBtn');
    if (loadBtn) loadBtn.style.display = visible.length >= filtered.length ? 'none' : '';

    // Filtre butonlarındaki sayıları güncelle
    var filterBar = document.getElementById('filterBar');
    if (filterBar) {
      filterBar.querySelectorAll('.filter-btn').forEach(function (btn) {
        var slug = btn.dataset.filter;
        var count = slug === 'all' ? items.length : items.filter(function (i) { return i.category === slug; }).length;
        var span = btn.querySelector('.filter-count');
        if (span) span.textContent = count;
      });
    }
  }

  document.querySelectorAll('.filter-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.filter-btn').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      currentFilter = btn.dataset.filter;
      showAll = false;
      grid.querySelectorAll('.gallery-card').forEach(function (c) {
        c.style.opacity = '0';
        c.style.transform = 'translateY(20px) scale(0.97)';
      });
      setTimeout(renderGrid, 300);
    });
  });

  var loadMoreBtn = document.getElementById('loadMoreBtn');
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function () {
      showAll = true;
      renderGrid();
    });
  }

  var lb = document.getElementById('lightbox');
  var currentLbIdx = 0;
  var currentLbList = [];

  function openLightbox(item) {
    currentLbList = currentFilter === 'all' ? items.slice() : items.filter(function (i) { return i.category === currentFilter; });
    currentLbIdx = currentLbList.findIndex(function (i) { return i.id === item.id; });
    if (currentLbIdx < 0) currentLbIdx = 0;
    populateLightbox(item);
    lb.classList.add('active');
    lb.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function populateLightbox(item) {
    var lbCarpet = document.getElementById('lbCarpet');
    var isRound = item.title.indexOf('Round') !== -1;
    if (lbCarpet) {
      lbCarpet.style.background = item.color;
      lbCarpet.style.borderRadius = isRound ? '50%' : '12px';
    }
    setText('lbTag', getTagLabel(item.category));
    setText('lbTitle', item.title);
    setText('lbCollection', item.collection);
    setText('lbDesc', item.desc);
    setText('lbMaterial', item.material);
    setText('lbSize', item.size);
    setText('lbThickness', item.thickness);
    setText('lbPrice', item.price);

    var colorsWrap = document.getElementById('lbColors');
    if (colorsWrap) {
      colorsWrap.innerHTML = '';
      item.colors.forEach(function (c, i) {
        var dot = document.createElement('div');
        dot.className = 'lightbox-color-dot' + (i === 0 ? ' active' : '');
        dot.style.background = c;
        dot.addEventListener('click', function () {
          colorsWrap.querySelectorAll('.lightbox-color-dot').forEach(function (d) { d.classList.remove('active'); });
          dot.classList.add('active');
          if (lbCarpet) lbCarpet.style.background = c;
        });
        colorsWrap.appendChild(dot);
      });
    }
  }

  function setText(id, text) {
    var el = document.getElementById(id);
    if (el) el.textContent = text;
  }

  function closeLightbox() {
    lb.classList.remove('active');
    lb.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  var closeBtn = document.getElementById('lightboxClose');
  if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
  lb.addEventListener('click', function (e) { if (e.target === lb) closeLightbox(); });

  var lbPrev = document.getElementById('lbPrev');
  var lbNext = document.getElementById('lbNext');
  if (lbPrev) {
    lbPrev.addEventListener('click', function (e) {
      e.stopPropagation();
      currentLbIdx = (currentLbIdx - 1 + currentLbList.length) % currentLbList.length;
      populateLightbox(currentLbList[currentLbIdx]);
    });
  }
  if (lbNext) {
    lbNext.addEventListener('click', function (e) {
      e.stopPropagation();
      currentLbIdx = (currentLbIdx + 1) % currentLbList.length;
      populateLightbox(currentLbList[currentLbIdx]);
    });
  }

  document.addEventListener('keydown', function (e) {
    if (!lb.classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft' && lbPrev) lbPrev.click();
    if (e.key === 'ArrowRight' && lbNext) lbNext.click();
  });

  var lbFav = document.getElementById('lbFav');
  if (lbFav) {
    lbFav.addEventListener('click', function (e) {
      var svg = e.currentTarget.querySelector('svg');
      var filled = svg.getAttribute('fill') !== 'none';
      svg.setAttribute('fill', filled ? 'none' : 'var(--gal-gold)');
      svg.style.color = filled ? '' : 'var(--gal-gold)';
    });
  }

  renderGrid();
})();
