/**
 * Entry Mark Carpet - Modern Customizer
 * Fully Responsive & Mobile First
 */

(function() {
  'use strict';

  // ===== STATE =====
  const state = {
    step: 1,
    shape: 'horizontal',
    recess: false,
    textureId: null,
    texturePrice: 0,
    textureName: '',
    sizeId: null,
    width: 40,
    height: 70,
    sizePrice: 0,
    shippingId: null,
    shippingPrice: 0,
    shippingName: '',
    images: [],
    selectedImageId: null,
    text: '',
    textX: 0.5,
    textY: 0.65,
    textScale: 1,
    textBold: false,
    textItalic: false,
    textColor: '#FFFFFF',
    textFont: 'Arial',
    zoom: 1,
    isDragging: false,
    dragType: null,
    dragId: null,
    textures: [],
    sizes: [],
    shippings: []
  };

  // ===== DOM ELEMENTS =====
  const els = {};
  
  function cacheElements() {
    // Canvas
    els.mainCanvas = document.getElementById('emcMainCanvas');
    els.mainCtx = els.mainCanvas?.getContext('2d');
    els.summaryCanvas = document.getElementById('emcSummaryCanvas');
    els.summaryCtx = els.summaryCanvas?.getContext('2d');
    
    // Progress
    els.progressFill = document.querySelector('.emc-progress-fill');
    els.steps = document.querySelectorAll('.emc-step');
    
    // Panels
    els.panels = document.querySelectorAll('.control-panel');
    
    // Canvas controls
    els.badge = document.getElementById('emcBadge');
    els.zoomValue = document.getElementById('emcZoomValue');
    
    // Navigation
    els.prevBtn = document.getElementById('emcPrevBtn');
    els.nextBtn = document.getElementById('emcNextBtn');
    
    // Shape
    els.shapeOptions = document.querySelectorAll('.shape-option');
    els.recessBtns = document.querySelectorAll('[data-recess]');
    
    // Lists
    els.textureList = document.getElementById('emcTextureList');
    els.sizeList = document.getElementById('emcSizeList');
    els.shippingList = document.getElementById('emcShippingList');
    
    // Custom size
    els.toggleCustom = document.getElementById('emcToggleCustom');
    els.customForm = document.getElementById('emcCustomForm');
    els.customW = document.getElementById('emcCustomW');
    els.customH = document.getElementById('emcCustomH');
    els.applyCustom = document.getElementById('emcApplyCustom');
    
    // Upload
    els.uploadZone = document.getElementById('emcUploadZone');
    els.imageInput = document.getElementById('emcImageInput');
    els.imageList = document.getElementById('emcImageList');
    
    // Text
    els.textInput = document.getElementById('emcTextInput');
    els.textCount = document.getElementById('emcTextCount');
    els.boldBtn = document.getElementById('emcBoldBtn');
    els.italicBtn = document.getElementById('emcItalicBtn');
    els.textColor = document.getElementById('emcTextColor');
    els.fontSelect = document.getElementById('emcFontSelect');
    
    // Summary
    els.summaryToggle = document.getElementById('emcSummaryToggle');
    els.summaryContent = document.getElementById('emcSummaryContent');
    els.summaryPrice = document.getElementById('emcSummaryPrice');
    els.sumShape = document.getElementById('emcSumShape');
    els.sumTexture = document.getElementById('emcSumTexture');
    els.sumSize = document.getElementById('emcSumSize');
    els.sumShipping = document.getElementById('emcSumShipping');
    els.priceProduct = document.getElementById('emcPriceProduct');
    els.priceShip = document.getElementById('emcPriceShip');
    els.priceTax = document.getElementById('emcPriceTax');
    els.priceTotal = document.getElementById('emcPriceTotal');
    
    // Actions
    els.addCart = document.getElementById('emcAddCart');
    els.download = document.getElementById('emcDownload');
    els.share = document.getElementById('emcShare');
    els.whatsapp = document.getElementById('emcWhatsApp');
    
    // Overlay
    els.toast = document.getElementById('emcToast');
    els.loading = document.getElementById('emcLoading');
  }

  // ===== UTILS =====
  function formatPrice(price) {
    // Simple Turkish format: 1.234,56 TL
    const num = parseFloat(price) || 0;
    const parts = num.toFixed(2).split('.');
    const intPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return intPart + ',' + parts[1] + ' TL';
  }

  function showToast(msg) {
    els.toast.textContent = msg;
    els.toast.classList.add('show');
    setTimeout(() => els.toast.classList.remove('show'), 3000);
  }

  function showLoading(show) {
    els.loading.classList.toggle('show', show);
  }

  function clamp(val, min, max) {
    return Math.max(min, Math.min(max, val));
  }

  // ===== DATA LOADING =====
  function loadConfig() {
    const restData = typeof EMC_REST_DATA !== 'undefined' ? EMC_REST_DATA : null;
    const restUrl = restData && restData.rest_url ? restData.rest_url.replace(/\/$/, '') + '/config' : '';
    var inlineConfig = (typeof EMC_CONFIG !== 'undefined' && EMC_CONFIG && !EMC_CONFIG.error) ? EMC_CONFIG : null;

    // Önce inline config ile hemen göster (sayfa render anındaki veri; cache yoksa güncel)
    if (inlineConfig) {
      window.EMC_CONFIG = inlineConfig;
      window.EMC_FULL_CONFIG = inlineConfig;
      processConfig(inlineConfig);
    }

    // Ardından REST'ten güncel config çek; başarılı olursa ekranı güncelle (cache bypass)
    if (restUrl && restData.nonce) {
      var cacheBuster = '?t=' + (Date.now ? Date.now() : Math.floor(Date.now() / 1000));
      fetch(restUrl + cacheBuster, { headers: { 'X-WP-Nonce': restData.nonce } })
        .then(function(r) {
          if (!r.ok) throw new Error('HTTP ' + r.status);
          return r.json();
        })
        .then(function(data) {
          if (data && !data.error) {
            window.EMC_CONFIG = data;
            window.EMC_FULL_CONFIG = data;
            processConfig(data);
          }
        })
        .catch(function(err) {
          console.warn('REST config failed, inline config used:', err);
          if (!inlineConfig) showToast('Yapılandırma yüklenemedi');
        });
      return;
    }
    if (!inlineConfig) {
      showToast('Yapılandırma yüklenemedi');
    }
  }

  function processConfig(data) {
    console.log('Config loaded:', data);
    
    // Store full config for later use
    window.EMC_FULL_CONFIG = data;
    
    state.textures = data.textures || [];
    state.shippings = data.shipping || [];
    
    // Resim URL'sini sayfa ile aynı origin yap (sunucu taşıma / farklı domain'de görsel gelmesin)
    state.textures.forEach((t) => {
      if (t.image_url && t.image_url.indexOf('http') === 0) {
        try {
          const u = new URL(t.image_url);
          if (u.origin !== window.location.origin) {
            t.image_url = window.location.origin + u.pathname + (u.search || '');
          }
        } catch (e) { /* aynen bırak */ }
      }
    });
    
    // Debug textures
    console.log('Textures:', state.textures);
    console.log('Sizes:', data.sizes);
    
    // Process sizes for current shape
    updateSizesForShape();
    
    // Load texture images
    state.textures.forEach((t, i) => {
      t.id = t.id || `tex_${i}`;
      t.price_per_m2 = data.pricing?.price_per_m2 ?? 20.45;
      if (t.image_url) {
        t.img = new Image();
        t.img.crossOrigin = 'anonymous';
        t.img.onload = () => { t.loaded = true; render(); };
        t.img.onerror = () => { t.loaded = false; render(); };
        t.img.src = t.image_url;
      }
    });
    
    renderTextures();
    renderShippings();
    
    // Font dropdown: config'ten doldur
    const fonts = data.text_options?.fonts || [];
    if (els.fontSelect && fonts.length > 0) {
      els.fontSelect.innerHTML = fonts.map(f => 
        `<option value="${(f.family || f.name || 'Arial, sans-serif').replace(/"/g, '&quot;')}">${f.name || f.family || 'Font'}</option>`
      ).join('');
      state.textFont = fonts[0].family || fonts[0].name || 'Arial, sans-serif';
      els.fontSelect.value = state.textFont;
    }
    
    // Önce texture'u seç (fiyat için gerekli)
    if (state.textures.length > 0) selectTexture(state.textures[0].id);
    
    // Sonra ölçüyü seç (texture fiyatı ayarlandıktan sonra)
    if (state.sizes.length > 0) {
      const default40x70 = state.sizes.find(function(s) { return s.width === 40 && s.height === 70; });
      selectSize((default40x70 || state.sizes[0]).id);
    }
    
    if (state.shippings.length > 0) selectShipping(state.shippings[0].id || 0);
    
    // WhatsApp ayarlarını kaydet
    state.whatsapp = data.whatsapp || { enabled: false, number: '', button_text: 'WhatsApp ile Sipariş Ver', message: '' };
    updateWhatsAppButton();
    
    // Ölçü listesini son olarak render et (doğru fiyat hesaplaması için)
    renderSizes();
    render();
    
    // URL parametrelerinden state yükle (varsa)
    loadStateFromUrl();
  }
  
  function loadStateFromUrl() {
    try {
      const url = new URL(window.location.href);
      const params = url.searchParams;
      
      // URL'de parametre var mı kontrol et
      if (!params.has('shape') && !params.has('texture') && !params.has('width')) {
        console.log('No URL params found');
        return;
      }
      
      console.log('=== Loading state from URL ===');
      console.log('URL:', window.location.href);
      console.log('Params:', Object.fromEntries(params));
      console.log('Current state before:', { shape: state.shape, textureId: state.textureId, width: state.width, height: state.height });
      
      // Shape ayarla
      const shape = params.get('shape');
      if (shape && ['horizontal', 'vertical', 'round'].includes(shape)) {
        console.log('1. Setting shape:', shape);
        state.shape = shape;
        updateSizesForShape();
        
        // Shape UI güncelle
        els.shapeOptions?.forEach(el => {
          el.classList.toggle('active', el.dataset.shape === shape);
        });
      }
      
      // Texture ayarla (fiyat hesaplaması için önemli)
      const textureId = params.get('texture');
      if (textureId) {
        console.log('2. Looking for texture:', textureId);
        console.log('   Available textures:', state.textures.map(t => t.id));
        
        // Önce direkt eşleşme dene
        let texture = state.textures.find(t => String(t.id) === String(textureId));
        
        // Bulunamazsa ve format 't-X' ise index olarak dene
        if (!texture && textureId.startsWith('t-')) {
          const index = parseInt(textureId.replace('t-', ''));
          if (!isNaN(index) && index >= 0 && index < state.textures.length) {
            texture = state.textures[index];
            console.log('   Found texture by index:', index);
          }
        }
        
        // Hala bulunamazsa 'tex_X' formatını dene
        if (!texture && textureId.startsWith('t-')) {
          const texId = 'tex_' + textureId.replace('t-', '');
          texture = state.textures.find(t => String(t.id) === texId);
          console.log('   Trying texId format:', texId);
        }
        
        if (texture) {
          console.log('   Found texture:', texture.name, 'ID:', texture.id);
          state.textureId = texture.id; // Gerçek ID'yi kullan
          state.texturePrice = texture.price_per_m2 ?? 20.45;
          state.textureName = texture.name || '';
        } else {
          console.warn('   Texture NOT found:', textureId);
        }
      }
      
      // Size ayarla
      const width = parseInt(params.get('width'));
      const height = parseInt(params.get('height'));
      if (width && height) {
        console.log('3. Looking for size:', width, 'x', height);
        console.log('   Available sizes:', state.sizes.map(s => `${s.width}x${s.height}`));
        
        const size = state.sizes.find(s => s.width === width && s.height === height);
        if (size) {
          console.log('   Found size:', size);
          state.sizeId = size.id;
          state.width = size.width;
          state.height = size.height;
          state.sizePrice = size.price;
        } else {
          console.warn('   Size NOT found:', width, 'x', height);
          // Özel ölçü olarak ayarla
          state.width = width;
          state.height = height;
        }
      }
      
      // Text ayarla
      const text = params.get('text');
      if (text !== null && els.textInput) {
        console.log('4. Setting text:', text);
        state.text = text;
        els.textInput.value = text;
      }
      
      // Text Color ayarla
      const textColor = params.get('textColor');
      if (textColor && els.textColor) {
        console.log('5. Setting textColor:', textColor);
        state.textColor = textColor;
        els.textColor.value = textColor;
      }
      
      // Logo/Images yükle
      const imagesParam = params.get('images');
      console.log('6. Images param from URL:', imagesParam);
      console.log('6b. Full URL:', window.location.href);
      if (imagesParam && imagesParam.trim() !== '' && imagesParam !== 'null' && imagesParam !== 'undefined') {
        console.log('   Loading images from URL');
        const decodedParam = decodeURIComponent(imagesParam);
        console.log('   Decoded:', decodedParam);
        const imageUrls = decodedParam.split(',').filter(url => url.trim());
        console.log('   URL count:', imageUrls.length, 'URLs:', imageUrls);
        
        // Önceki image'ları temizle
        state.images = [];
        
        // Her bir URL için image yükle
        imageUrls.forEach((url, idx) => {
          const trimmedUrl = url.trim();
          console.log('   Loading image ' + idx + ':', trimmedUrl);
          
          const imgObj = {
            id: Date.now() + idx,
            src: trimmedUrl,
            name: 'logo_' + (idx + 1),
            x: 0.5,  // Merkez (0-1 arası)
            y: 0.5,
            scale: 1,
            naturalWidth: 0,
            naturalHeight: 0,
            element: new Image()
          };
          
          // CORS için crossOrigin ayarla (sunucu destekliyorsa)
          if (trimmedUrl.indexOf(window.location.hostname) === -1) {
            imgObj.element.crossOrigin = 'anonymous';
          }
          
          imgObj.element.onload = () => {
            console.log('   Image loaded successfully:', imgObj.name, imgObj.element.naturalWidth + 'x' + imgObj.element.naturalHeight);
            imgObj.naturalWidth = imgObj.element.naturalWidth;
            imgObj.naturalHeight = imgObj.element.naturalHeight;
            console.log('   State images count:', state.images.length);
            render();
            renderImages();
          };
          
          imgObj.element.onerror = (e) => {
            console.error('   FAILED to load image:', trimmedUrl, e);
          };
          
          imgObj.element.src = trimmedUrl;
          state.images.push(imgObj);
          console.log('   Added to state.images, count now:', state.images.length);
        });
        
        console.log('   Loading', imageUrls.length, 'images');
        renderImages();
      }
      
      // Düzenleme modu kontrolü
      const editItem = params.get('edit_item');
      if (editItem !== null) {
        console.log('Edit mode active for item:', editItem);
        // Kullanıcıya bildirim göster
        setTimeout(() => {
          alert('Tasarımınızı düzenliyorsunuz.\n\nNot: Daha önce yüklediğiniz görselleri tekrar yüklemeniz gerekebilir.');
        }, 500);
      }
      
      // UI Güncelle
      console.log('6. Updating UI...');
      console.log('   State after:', { shape: state.shape, textureId: state.textureId, width: state.width, height: state.height });
      
      renderTextures();
      renderSizes();
      render();
      updateSummary();
      
      console.log('=== State loaded successfully ===');
    } catch (e) {
      console.error('Error loading state from URL:', e);
    }
  }
  
  function updateWhatsAppButton() {
    if (!els.whatsapp) return;
    const wa = state.whatsapp || {};
    if (wa.enabled && wa.number) {
      els.whatsapp.style.display = 'flex';
      els.whatsapp.querySelector('span').textContent = wa.button_text || 'WhatsApp ile Sipariş Ver';
    } else {
      els.whatsapp.style.display = 'none';
    }
  }

  function getDefaultSizeId() {
    if (!state.sizes.length) return null;
    const ref = state.sizes.find(function(s) { return s.width === 40 && s.height === 70; });
    return ref ? ref.id : state.sizes[0].id;
  }

  function getSizeOffset(s) {
    if (s == null) return 0;
    if (typeof s.offset === 'number' && !isNaN(s.offset)) return s.offset;
    if (s.offset != null) return parseFloat(String(s.offset).replace(/[^0-9.-]/g, '')) || 0;
    if (Array.isArray(s) && s.length > 3) {
      var v = s[3];
      if (typeof v === 'number' && !isNaN(v)) return v;
      if (v != null) return parseFloat(String(v).replace(/[^0-9.-]/g, '')) || 0;
    }
    if (typeof s[3] === 'number') return s[3];
    if (typeof s[3] === 'string') return parseFloat(s[3].replace(/[^0-9.-]/g, '')) || 0;
    return 0;
  }

  function updateSizesForShape() {
    const config = (typeof window.EMC_FULL_CONFIG !== 'undefined' && window.EMC_FULL_CONFIG) ? window.EMC_FULL_CONFIG : (typeof EMC_CONFIG !== 'undefined' ? EMC_CONFIG : {});
    const rawSizes = config.sizes?.[state.shape] || [];
    state.sizes = rawSizes.map((s, i) => {
      const w = parseInt(s[0] != null ? s[0] : s.width, 10) || 0;
      const h = parseInt(s[1] != null ? s[1] : s.height, 10) || 0;
      const label = (s[2] != null ? s[2] : s.label) || (w && h ? w + '×' + h + ' cm' : '');
      const price = getSizeOffset(s);
      return {
        id: i + 1,
        width: w,
        height: h,
        label: label,
        price: price
      };
    });
  }

  // ===== RENDER FUNCTIONS =====
  function renderTextures() {
    if (!els.textureList) return;
    
    console.log('Rendering textures:', state.textures);
    
    if (state.textures.length === 0) {
      els.textureList.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:30px;color:#666;font-size:0.95em">Doku bulunamadı.<br><small>Yönetici: WordPress → Paspas → Dokular sayfasından en az bir doku (ad + görsel) ekleyip kaydedin.</small></div>';
      return;
    }
    
    els.textureList.innerHTML = state.textures.map((t, i) => {
      const imageUrl = (t.image_url || t.url || '').replace(/"/g, '&quot;').replace(/</g, '&lt;');
      const nameSafe = (t.name || 'Doku ' + (i + 1)).replace(/</g, '&lt;').replace(/"/g, '&quot;');
      return `
      <div class="texture-item ${t.id === state.textureId ? 'active' : ''}" data-id="${t.id}" data-index="${i}">
        <div style="aspect-ratio:1;background:#f0f0f0;display:flex;align-items:center;justify-content:center;overflow:hidden;">
          ${imageUrl ? `<img src="${imageUrl}" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.parentElement.innerHTML='<span style=\'font-size:12px;color:#999\'>Resim yok</span>'">` : '<span style="font-size:12px;color:#999">Resim yok</span>'}
        </div>
        <span>${nameSafe}</span>
      </div>
    `}).join('');
  }

  function renderSizes() {
    if (!els.sizeList) return;
    
    if (state.sizes.length === 0) {
      els.sizeList.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:30px;color:#999">Ölçü bulunamadı</div>';
      return;
    }
    
    // Fallback: texturePrice 0 ise default 20.45 kullan
    const texturePrice = state.texturePrice > 0 ? state.texturePrice : 20.45;
    
    // Seçili ölçünün toplam fiyatını hesapla (alan * m2 fiyat + offset)
    const selectedSize = state.sizes.find(x => x.id === state.sizeId);
    let selectedTotalPrice = 0;
    if (selectedSize) {
      const selectedArea = (selectedSize.width * selectedSize.height) / 10000;
      selectedTotalPrice = (selectedArea * texturePrice * 100) + (parseFloat(selectedSize.price) || 0);
    }
    
    els.sizeList.innerHTML = state.sizes.map(s => {
      // Her ölçünün toplam fiyatını hesapla
      const area = (s.width * s.height) / 10000;
      const totalPrice = (area * texturePrice * 100) + (parseFloat(s.price) || 0);
      
      // Seçili ölçüye göre farkı hesapla
      let diff = 0;
      if (selectedSize) {
        diff = totalPrice - selectedTotalPrice;
      }
      
      const displayNum = Math.round(diff * 100) / 100;
      let priceStr = '';
      if (displayNum > 0) {
        priceStr = '+' + displayNum.toFixed(2).replace(/\.00$/, '').replace('.', ',') + ' TL';
      } else if (displayNum < 0) {
        priceStr = displayNum.toFixed(2).replace(/\.00$/, '').replace('.', ',') + ' TL';
      } else {
        priceStr = '';
      }
      
      return `
      <div class="size-item ${s.id === state.sizeId ? 'active' : ''}" data-id="${s.id}">
        <span class="size-label">${s.label}</span>
        ${priceStr ? `<span class="size-price">${priceStr}</span>` : ''}
      </div>
    `}).join('');
  }

  function renderShippings() {
    if (!els.shippingList) return;
    
    if (state.shippings.length === 0) {
      els.shippingList.innerHTML = '<div style="text-align:center;padding:30px;color:#999">Gönderim seçeneği bulunamadı</div>';
      return;
    }
    
    els.shippingList.innerHTML = state.shippings.map((s, i) => `
      <label class="shipping-item ${(s.id || i) === state.shippingId ? 'active' : ''}">
        <input type="radio" name="shipping" value="${s.id || i}" ${(s.id || i) === state.shippingId ? 'checked' : ''}>
        <div class="shipping-info">
          <div class="shipping-title">${s.label || s.name}</div>
          <div class="shipping-desc">${s.desc || s.description || ''}</div>
        </div>
        <div class="shipping-price">+${s.extra || s.price || 0} TL</div>
      </label>
    `).join('');
  }

  function renderImages() {
    if (!els.imageList) return;
    
    els.imageList.innerHTML = state.images.map(img => {
      // Element varsa onun boyutlarını kullan, yoksa bekliyoruz
      const width = img.element?.naturalWidth || img.naturalWidth || 0;
      const height = img.element?.naturalHeight || img.naturalHeight || 0;
      const dimText = (width && height) ? `${width}×${height}px` : 'Yükleniyor...';
      
      return `
      <div class="image-item ${img.id === state.selectedImageId ? 'selected' : ''}" data-id="${img.id}">
        <img src="${img.src}" alt="" onload="this.nextElementSibling.querySelector('.image-dim').textContent=this.naturalWidth+'×'+this.naturalHeight+'px'">
        <div class="image-info">
          <div class="image-name">Görsel</div>
          <div class="image-dim">${dimText}</div>
        </div>
        <div class="image-scale">
          <input type="range" min="5" max="150" value="${Math.round((img.scale || 1) * 100)}" data-id="${img.id}">
          <span class="scale-val">${Math.round((img.scale || 1) * 100)}%</span>
        </div>
        <button class="image-remove" data-id="${img.id}">×</button>
      </div>
    `}).join('');
  }

  // ===== CANVAS RENDERING =====
  function getCanvasDims() {
    const baseW = 640, baseH = 480;
    let w, h;
    
    if (state.shape === 'round') {
      const size = Math.min(baseW, baseH) * 0.85;
      w = h = size;
    } else if (state.shape === 'vertical') {
      h = baseH * 0.9;
      w = h * 0.6;
    } else {
      w = baseW * 0.9;
      h = w * 0.6;
    }
    
    return { w, h, cx: baseW / 2, cy: baseH / 2 };
  }

  function drawCanvas(ctx, canvas, isSummary = false) {
    if (!ctx || !canvas) return;
    
    const { w, h, cx, cy } = getCanvasDims();
    
    // Clear
    ctx.fillStyle = isSummary ? '#1a1a2e' : '#e5e7eb';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    ctx.save();
    
    // For summary canvas, fit content to view
    if (isSummary) {
      const padding = 20;
      const availW = canvas.width - (padding * 2);
      const availH = canvas.height - (padding * 2);
      const scaleX = availW / 640;
      const scaleY = availH / 480;
      const fitScale = Math.min(scaleX, scaleY);
      
      ctx.translate(canvas.width / 2, canvas.height / 2);
      ctx.scale(fitScale, fitScale);
      ctx.translate(-320, -240); // Center of 640x480
    } else if (state.zoom !== 1) {
      // Apply zoom for main canvas only
      ctx.translate(canvas.width / 2, canvas.height / 2);
      ctx.scale(state.zoom, state.zoom);
      ctx.translate(-canvas.width / 2, -canvas.height / 2);
    }
    
    // Texture
    const tex = state.textures.find(t => t.id === state.textureId);
    if (tex?.img?.complete && tex.img.naturalWidth > 0) {
      const ptrn = ctx.createPattern(tex.img, 'repeat');
      ctx.fillStyle = ptrn;
    } else {
      ctx.fillStyle = tex?.image_url ? '#3a3a4a' : '#333';
    }
    
    // Draw shape
    ctx.beginPath();
    if (state.shape === 'round') {
      ctx.arc(cx, cy, w / 2, 0, Math.PI * 2);
    } else {
      roundRect(ctx, cx - w/2, cy - h/2, w, h, 8);
    }
    ctx.fill();
    
    // Border
    if (!state.recess) {
      ctx.strokeStyle = 'rgba(255,255,255,0.2)';
      ctx.lineWidth = 3;
      ctx.stroke();
    }
    
    // Clip for content
    ctx.save();
    ctx.clip();
    
    // Draw images
    state.images.forEach(img => {
      if (!img.element?.complete) return;
      // Element boyutlarını al (naturalWidth/Height property'leri veya element'den)
      const imgWidth = img.naturalWidth || img.element.naturalWidth;
      const imgHeight = img.naturalHeight || img.element.naturalHeight;
      if (!imgWidth || !imgHeight) return; // Boyutlar yoksa çizme
      // Scale relative to carpet size (w is the carpet width in pixels)
      // img.scale is a multiplier (1 = original size relative to carpet)
      const baseScale = w / 640; // Base scale to normalize to carpet size
      const drawW = imgWidth * img.scale * baseScale * 0.5; // 0.5 to make default smaller
      const drawH = imgHeight * img.scale * baseScale * 0.5;
      const x = cx - w/2 + img.x * w - drawW/2;
      const y = cy - h/2 + img.y * h - drawH/2;
      
      ctx.drawImage(img.element, x, y, drawW, drawH);
      
      if (img.id === state.selectedImageId && !isSummary) {
        // Selection border
        ctx.strokeStyle = '#d4a84b';
        ctx.lineWidth = 2;
        ctx.setLineDash([5, 3]);
        ctx.strokeRect(x-2, y-2, drawW+4, drawH+4);
        ctx.setLineDash([]);
        
        // Resize handles (corners)
        const handleSize = 8;
        ctx.fillStyle = '#d4a84b';
        
        // Top-left
        ctx.fillRect(x - handleSize, y - handleSize, handleSize * 2, handleSize * 2);
        // Top-right
        ctx.fillRect(x + drawW - handleSize, y - handleSize, handleSize * 2, handleSize * 2);
        // Bottom-left
        ctx.fillRect(x - handleSize, y + drawH - handleSize, handleSize * 2, handleSize * 2);
        // Bottom-right
        ctx.fillRect(x + drawW - handleSize, y + drawH - handleSize, handleSize * 2, handleSize * 2);
        
        // Store handle positions for hit testing
        img._handles = {
          tl: { x: x - handleSize, y: y - handleSize, w: handleSize * 2, h: handleSize * 2, cursor: 'nwse-resize' },
          tr: { x: x + drawW - handleSize, y: y - handleSize, w: handleSize * 2, h: handleSize * 2, cursor: 'nesw-resize' },
          bl: { x: x - handleSize, y: y + drawH - handleSize, w: handleSize * 2, h: handleSize * 2, cursor: 'nesw-resize' },
          br: { x: x + drawW - handleSize, y: y + drawH - handleSize, w: handleSize * 2, h: handleSize * 2, cursor: 'nwse-resize' }
        };
        img._drawInfo = { x, y, drawW, drawH, centerX: x + drawW/2, centerY: y + drawH/2 };
      }
    });
    
    // Draw text
    if (state.text) {
      const fontSize = 24 * state.textScale * (w / 300);
      ctx.font = `${state.textBold ? 'bold ' : ''}${state.textItalic ? 'italic ' : ''}${fontSize}px ${state.textFont}`;
      ctx.fillStyle = state.textColor;
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.shadowColor = 'rgba(0,0,0,0.5)';
      ctx.shadowBlur = 4;
      ctx.fillText(state.text, cx - w/2 + state.textX * w, cy - h/2 + state.textY * h);
      ctx.shadowBlur = 0;
    }
    
    ctx.restore();
    ctx.restore();
  }

  function roundRect(ctx, x, y, w, h, r) {
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + w, y, x + w, y + h, r);
    ctx.arcTo(x + w, y + h, x, y + h, r);
    ctx.arcTo(x, y + h, x, y, r);
    ctx.arcTo(x, y, x + w, y, r);
    ctx.closePath();
  }

  function render() {
    drawCanvas(els.mainCtx, els.mainCanvas, false);
    drawCanvas(els.summaryCtx, els.summaryCanvas, true);
    updateSummary();
  }

  // ===== SUMMARY & PRICING =====
  function updateSummary() {
    const shapeNames = { horizontal: 'Yatay', vertical: 'Dikey', round: 'Yuvarlak' };
    if (els.sumShape) els.sumShape.textContent = shapeNames[state.shape] || state.shape;
    if (els.sumTexture) els.sumTexture.textContent = state.textureName || '—';
    if (els.sumShipping) els.sumShipping.textContent = state.shippingName || '—';
    
    // Ölçü seçiliyse fiyatı göster (adım kontrolü olmadan)
    const hasSize = state.width > 0 && state.height > 0;
    const showPrice = hasSize;
    
    // Fallback: texturePrice 0 ise default 20.45 kullan
    const texturePrice = state.texturePrice > 0 ? state.texturePrice : 20.45;
    
    let productPrice = 0;
    let tax = 0;
    let total = 0;
    if (showPrice) {
      const config = typeof EMC_CONFIG !== 'undefined' ? EMC_CONFIG : {};
      const pricePerImage = parseFloat(config.pricing?.price_per_image) || 0;
      const textExtra = parseFloat(config.pricing?.text_extra) || 0;
      const rawMin = config.pricing?.min_total;
      const minTotal = (rawMin !== undefined && rawMin !== '' && !Number.isNaN(parseFloat(rawMin))) ? parseFloat(rawMin) : 200;
      const area = (state.width * state.height) / 10000;
      // Base price: area * price per m2 + size offset
      // Note: texturePrice is stored as 20.45 for 2045 TL, so multiply by 100
      productPrice = (area * texturePrice * 100) + state.sizePrice;
      if (minTotal > 0 && productPrice < minTotal) productPrice = minTotal;
      productPrice += (state.images.length * pricePerImage);
      if (state.text && state.text.trim()) productPrice += textExtra;
      tax = productPrice * 0.1;
      total = productPrice + state.shippingPrice + tax;
    }
    
    const zeroStr = '0,00 TL';
    if (els.priceProduct) els.priceProduct.textContent = showPrice ? formatPrice(productPrice) : zeroStr;
    if (els.priceShip) els.priceShip.textContent = showPrice ? formatPrice(state.shippingPrice) : zeroStr;
    if (els.priceTax) els.priceTax.textContent = showPrice ? formatPrice(tax) : zeroStr;
    if (els.priceTotal) els.priceTotal.textContent = showPrice ? formatPrice(total) : zeroStr;
    if (els.summaryPrice) els.summaryPrice.textContent = showPrice ? formatPrice(total) : zeroStr;
    if (els.badge) els.badge.textContent = hasSize ? `${state.width} × ${state.height} cm` : 'Ölçü seçin';
    if (els.sumSize) els.sumSize.textContent = hasSize ? `${state.width} × ${state.height} cm` : '—';
  }

  // ===== SELECTION FUNCTIONS =====
  function selectTexture(id, skipRender = false) {
    state.textureId = id;
    const t = state.textures.find(x => x.id === id);
    state.texturePrice = t?.price_per_m2 ?? 20.45;
    state.textureName = t?.name || '';
    if (!skipRender) {
      renderTextures();
      renderSizes(); // Fiyat farkları texture değişince güncellenmeli
      render();
    }
  }

  function selectSize(id, skipRender = false) {
    state.sizeId = id;
    const s = state.sizes.find(x => x.id === id);
    if (s) {
      state.width = s.width;
      state.height = s.height;
      state.sizePrice = s.price;
    }
    if (!skipRender) {
      renderSizes();
      render();
    }
  }

  function selectShipping(id) {
    state.shippingId = id;
    const s = state.shippings.find((x, i) => (x.id || i.toString()) === id.toString());
    state.shippingPrice = parseFloat(s?.extra || s?.price || 0);
    state.shippingName = s?.label || s?.name || '';
    renderShippings();
    render();
  }

  // ===== NAVIGATION =====
  function goToStep(step) {
    if (step < 1 || step > 5) return;
    state.step = step;
    
    // Update progress
    if (els.progressFill) els.progressFill.style.width = (step * 20) + '%';
    els.steps.forEach((el, i) => {
      el.classList.remove('active', 'completed');
      if (i + 1 < step) el.classList.add('completed');
      else if (i + 1 === step) el.classList.add('active');
    });
    
    // Show panel
    els.panels.forEach(el => el.classList.remove('active'));
    const panel = document.querySelector(`.control-panel[data-panel="${step}"]`);
    if (panel) panel.classList.add('active');
    
    // Update nav buttons
    if (els.prevBtn) els.prevBtn.style.display = step === 1 ? 'none' : 'block';
    if (els.nextBtn) els.nextBtn.textContent = step === 5 ? 'Sepete Ekle →' : 'İleri →';
  }

  // ===== IMAGE HANDLING =====
  function addImage(file) {
    const id = 'img_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
    const img = new Image();
    img.crossOrigin = 'anonymous';
    
    img.onload = () => {
      // Calculate initial scale to fit image within carpet bounds
      // Target: max 40% of carpet width, max 50% of carpet height
      const maxW = 640 * 0.4; // 40% of canvas width
      const maxH = 480 * 0.5; // 50% of canvas height
      const scaleW = maxW / img.naturalWidth;
      const scaleH = maxH / img.naturalHeight;
      const initialScale = Math.min(scaleW, scaleH, 1); // Never upscale beyond 1:1
      
      state.images.push({
        id,
        src: URL.createObjectURL(file),
        element: img,
        naturalWidth: img.naturalWidth,
        naturalHeight: img.naturalHeight,
        x: 0.5,
        y: 0.5, // Center vertically
        scale: initialScale // Auto-fit scale
      });
      state.selectedImageId = id;
      renderImages();
      render();
    };
    
    img.src = URL.createObjectURL(file);
  }

  // ===== DRAG & DROP =====
  function getCanvasPos(e) {
    const rect = els.mainCanvas.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    return {
      x: (clientX - rect.left) / rect.width,
      y: (clientY - rect.top) / rect.height
    };
  }

  function hitTest(nx, ny) {
    const { w, h, cx, cy } = getCanvasDims();
    const x = nx * els.mainCanvas.width;
    const y = ny * els.mainCanvas.height;
    
    // Check images (reverse order - top first)
    for (let i = state.images.length - 1; i >= 0; i--) {
      const img = state.images[i];
      if (!img.element?.complete) continue;
      const baseScale = w / 640;
      const drawW = img.naturalWidth * img.scale * baseScale * 0.5;
      const drawH = img.naturalHeight * img.scale * baseScale * 0.5;
      const ix = cx - w/2 + img.x * w - drawW/2;
      const iy = cy - h/2 + img.y * h - drawH/2;
      
      // Check resize handles first (if selected)
      if (img.id === state.selectedImageId && img._handles) {
        for (const [handle, rect] of Object.entries(img._handles)) {
          if (x >= rect.x && x <= rect.x + rect.w && y >= rect.y && y <= rect.y + rect.h) {
            return { 
              type: 'resize', 
              id: img.id, 
              handle: handle,
              cursor: rect.cursor,
              startX: x, 
              startY: y,
              startScale: img.scale
            };
          }
        }
      }
      
      // Check image body
      if (x >= ix && x <= ix + drawW && y >= iy && y <= iy + drawH) {
        return { type: 'image', id: img.id, offsetX: x - ix - drawW/2, offsetY: y - iy - drawH/2 };
      }
    }
    
    // Check text
    if (state.text) {
      const tx = cx - w/2 + state.textX * w;
      const ty = cy - h/2 + state.textY * h;
      const tw = els.mainCtx.measureText(state.text).width;
      if (Math.abs(x - tx) < tw/2 && Math.abs(y - ty) < 25) {
        return { type: 'text', offsetX: x - tx, offsetY: y - ty };
      }
    }
    
    return null;
  }

  function onCanvasDown(e) {
    const pos = getCanvasPos(e);
    const hit = hitTest(pos.x, pos.y);
    
    if (hit) {
      state.isDragging = true;
      state.dragType = hit.type;
      state.dragId = hit.id;
      state.dragOffsetX = hit.offsetX || 0;
      state.dragOffsetY = hit.offsetY || 0;
      
      if (hit.type === 'image') {
        state.selectedImageId = hit.id;
        renderImages();
        els.mainCanvas.style.cursor = 'grabbing';
      } else if (hit.type === 'resize') {
        state.selectedImageId = hit.id;
        state.resizeHandle = hit.handle;
        state.startScale = hit.startScale;
        state.startX = hit.startX;
        state.startY = hit.startY;
        els.mainCanvas.style.cursor = hit.cursor;
        renderImages();
      }
      
      render();
    } else {
      state.selectedImageId = null;
      renderImages();
      render();
    }
  }

  function onCanvasMove(e) {
    const pos = getCanvasPos(e);
    
    if (!state.isDragging) {
      const hit = hitTest(pos.x, pos.y);
      if (hit?.type === 'resize') {
        els.mainCanvas.style.cursor = hit.cursor;
      } else if (hit?.type === 'image') {
        els.mainCanvas.style.cursor = 'grab';
      } else {
        els.mainCanvas.style.cursor = 'default';
      }
      return;
    }
    
    e.preventDefault();
    const { w, h, cx, cy } = getCanvasDims();
    const x = pos.x * els.mainCanvas.width;
    const y = pos.y * els.mainCanvas.height;
    
    if (state.dragType === 'image') {
      const img = state.images.find(i => i.id === state.dragId);
      if (img) {
        img.x = clamp((x - state.dragOffsetX - (cx - w/2)) / w, 0, 1);
        img.y = clamp((y - state.dragOffsetY - (cy - h/2)) / h, 0, 1);
      }
    } else if (state.dragType === 'text') {
      state.textX = clamp((x - state.dragOffsetX - (cx - w/2)) / w, 0, 1);
      state.textY = clamp((y - state.dragOffsetY - (cy - h/2)) / h, 0, 1);
    } else if (state.dragType === 'resize') {
      const img = state.images.find(i => i.id === state.dragId);
      if (img && img._drawInfo) {
        const dx = x - state.startX;
        const dy = y - state.startY;
        
        // Calculate distance moved (use larger of dx, dy for uniform scaling)
        let distance = 0;
        if (state.resizeHandle === 'br' || state.resizeHandle === 'tl') {
          distance = (dx + dy) / 2;
        } else if (state.resizeHandle === 'bl' || state.resizeHandle === 'tr') {
          distance = (dx - dy) / 2;
        }
        
        // Scale factor based on distance
        const scaleFactor = 1 + (distance / 200);
        const newScale = clamp(state.startScale * scaleFactor, 0.05, 2.0);
        
        img.scale = newScale;
        
        // Update slider in UI
        const slider = document.querySelector(`input[type="range"][data-id="${img.id}"]`);
        if (slider) {
          slider.value = Math.round(newScale * 100);
          const valSpan = slider.parentElement?.querySelector('.scale-val');
          if (valSpan) valSpan.textContent = Math.round(newScale * 100) + '%';
        }
      }
    }
    
    render();
  }

  function onCanvasUp() {
    state.isDragging = false;
    state.dragType = null;
    state.dragId = null;
    state.resizeHandle = null;
    els.mainCanvas.style.cursor = 'grab';
  }

  // ===== EVENT BINDING =====
  function bindEvents() {
    console.log('bindEvents called');
    console.log('els.nextBtn:', els.nextBtn);
    console.log('els.addCart:', els.addCart);
    
    // Steps
    els.steps.forEach((el, i) => {
      el.addEventListener('click', () => goToStep(i + 1));
    });
    
    // Navigation
    els.prevBtn?.addEventListener('click', () => goToStep(state.step - 1));
    
    // Next/Add to Cart button - hem adım ilerletme hem sepete ekleme
    if (els.nextBtn) {
      console.log('Binding nextBtn click event');
      els.nextBtn.addEventListener('click', () => {
        console.log('Next/Add button clicked, current step:', state.step);
        if (state.step === 5) {
          console.log('Step 5, calling addToCart...');
          addToCart();
        } else {
          goToStep(state.step + 1);
        }
      });
    } else {
      console.error('els.nextBtn not found!');
    }
    
    // Ayrıca Sepete Ekle butonuna doğrudan event listener (eğer varsa)
    if (els.addCart) {
      console.log('Binding addCart click event');
      els.addCart.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        console.log('Add to cart button clicked');
        addToCart();
      });
    } else {
      console.log('els.addCart not found (this is OK if using nextBtn)');
    }
    
    // Shape selection
    els.shapeOptions.forEach(el => {
      el.addEventListener('click', () => {
        els.shapeOptions.forEach(o => o.classList.remove('active'));
        el.classList.add('active');
        state.shape = el.dataset.shape;
        updateSizesForShape();
        renderSizes();
        if (state.sizes.length > 0) selectSize(getDefaultSizeId());
        render();
      });
    });
    
    // Recess toggle
    document.querySelectorAll('[data-recess]').forEach(el => {
      el.addEventListener('click', () => {
        document.querySelectorAll('[data-recess]').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        state.recess = el.dataset.recess === 'yes';
        render();
      });
    });
    
    // Texture list
    els.textureList?.addEventListener('click', e => {
      const item = e.target.closest('.texture-item');
      if (item) selectTexture(item.dataset.id);
    });
    
    // Size list
    els.sizeList?.addEventListener('click', e => {
      const item = e.target.closest('.size-item');
      if (item) selectSize(parseInt(item.dataset.id));
    });
    
    // Shipping list
    els.shippingList?.addEventListener('change', e => {
      if (e.target.name === 'shipping') selectShipping(e.target.value);
    });
    
    // Custom size toggle
    els.toggleCustom?.addEventListener('click', () => {
      els.toggleCustom.classList.toggle('open');
      els.customForm?.classList.toggle('show');
    });
    
    els.applyCustom?.addEventListener('click', () => {
      const w = parseInt(els.customW?.value);
      const h = parseInt(els.customH?.value);
      if (w && h && w >= 20 && h >= 20 && w <= 500 && h <= 500) {
        state.width = w;
        state.height = h;
        state.sizePrice = 0;
        state.sizeId = null;
        renderSizes();
        render();
        showToast('Özel ölçü uygulandı');
      }
    });
    
    // Upload
    els.uploadZone?.addEventListener('click', () => els.imageInput?.click());
    els.uploadZone?.addEventListener('dragover', e => {
      e.preventDefault();
      els.uploadZone.classList.add('dragover');
    });
    els.uploadZone?.addEventListener('dragleave', () => els.uploadZone.classList.remove('dragover'));
    els.uploadZone?.addEventListener('drop', e => {
      e.preventDefault();
      els.uploadZone.classList.remove('dragover');
      Array.from(e.dataTransfer.files).forEach(addImage);
    });
    
    els.imageInput?.addEventListener('change', () => {
      Array.from(els.imageInput.files).forEach(addImage);
      els.imageInput.value = '';
    });
    
    // Image list interactions
    els.imageList?.addEventListener('click', e => {
      const item = e.target.closest('.image-item');
      const remove = e.target.closest('.image-remove');
      
      if (remove) {
        state.images = state.images.filter(i => i.id !== remove.dataset.id);
        if (state.selectedImageId === remove.dataset.id) state.selectedImageId = null;
        renderImages();
        render();
      } else if (item) {
        state.selectedImageId = item.dataset.id;
        renderImages();
        render();
      }
    });
    
    // Slider input event (separate for real-time updates)
    els.imageList?.addEventListener('input', e => {
      if (e.target.type === 'range') {
        const slider = e.target;
        const img = state.images.find(i => i.id === slider.dataset.id);
        if (img) {
          img.scale = parseInt(slider.value) / 100;
          const valSpan = slider.parentElement.querySelector('.scale-val');
          if (valSpan) valSpan.textContent = slider.value + '%';
          render();
        }
      }
    });
    
    // Text
    els.textInput?.addEventListener('input', () => {
      state.text = els.textInput.value;
      if (els.textCount) els.textCount.textContent = state.text.length;
      render();
    });
    
    els.boldBtn?.addEventListener('click', () => {
      state.textBold = !state.textBold;
      els.boldBtn.classList.toggle('active', state.textBold);
      render();
    });
    
    els.italicBtn?.addEventListener('click', () => {
      state.textItalic = !state.textItalic;
      els.italicBtn.classList.toggle('active', state.textItalic);
      render();
    });
    
    els.textColor?.addEventListener('input', () => {
      state.textColor = els.textColor.value;
      render();
    });
    
    els.fontSelect?.addEventListener('change', () => {
      state.textFont = els.fontSelect.value;
      render();
    });
    
    // Zoom
    document.getElementById('emcZoomOut')?.addEventListener('click', () => {
      state.zoom = clamp(state.zoom - 0.1, 0.5, 2);
      if (els.zoomValue) els.zoomValue.textContent = Math.round(state.zoom * 100) + '%';
      render();
    });
    
    document.getElementById('emcZoomIn')?.addEventListener('click', () => {
      state.zoom = clamp(state.zoom + 0.1, 0.5, 2);
      if (els.zoomValue) els.zoomValue.textContent = Math.round(state.zoom * 100) + '%';
      render();
    });
    
    document.getElementById('emcZoomReset')?.addEventListener('click', () => {
      state.zoom = 1;
      if (els.zoomValue) els.zoomValue.textContent = '100%';
      render();
    });
    
    // Canvas drag
    els.mainCanvas?.addEventListener('mousedown', onCanvasDown);
    els.mainCanvas?.addEventListener('mousemove', onCanvasMove);
    window.addEventListener('mouseup', onCanvasUp);
    els.mainCanvas?.addEventListener('touchstart', onCanvasDown, { passive: false });
    els.mainCanvas?.addEventListener('touchmove', onCanvasMove, { passive: false });
    window.addEventListener('touchend', onCanvasUp);
    
    // Summary toggle (mobile)
    els.summaryToggle?.addEventListener('click', () => {
      if (window.innerWidth < 768) {
        els.summaryToggle.classList.toggle('open');
        els.summaryContent.classList.toggle('show');
      }
    });
    
    // Download & Share
    els.download?.addEventListener('click', () => {
      try {
        // CORS kontrolü
        const dataUrl = els.mainCanvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = 'tasarimim.png';
        link.href = dataUrl;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        showToast('Tasarım indirildi');
      } catch (e) {
        console.error('Download error:', e);
        showToast('İndirilemedi: ' + (e.message || 'Farklı kaynaklı görsel içeriyor'));
      }
    });
    
    els.share?.addEventListener('click', async () => {
      try {
        const blob = await new Promise(r => els.mainCanvas.toBlob(r, 'image/png'));
        if (!blob) throw new Error('Canvas boş');
        if (navigator.share && navigator.canShare({ files: [new File([blob], 'tasarim.png', { type: 'image/png' })] })) {
          await navigator.share({
            title: 'Paspas Tasarımım',
            files: [new File([blob], 'tasarim.png', { type: 'image/png' })]
          });
        } else {
          await navigator.clipboard.writeText(window.location.href);
          showToast('Bağlantı kopyalandı');
        }
      } catch (e) {
        console.error('Share error:', e);
        showToast('Paylaşılamadı: ' + (e.message || 'Hata oluştu'));
      }
    });
    
    // WhatsApp
    els.whatsapp?.addEventListener('click', async () => {
      const wa = state.whatsapp || {};
      
      // Debug: WhatsApp ayarlarını kontrol et
      console.log('WhatsApp clicked:', wa);
      
      if (!wa.enabled) {
        showToast('WhatsApp sipariş hattı aktif değil');
        return;
      }
      if (!wa.number) {
        showToast('WhatsApp numarası ayarlanmamış');
        return;
      }
      
      // Tasarım bilgilerini mesaja ekle
      const shapeNames = { horizontal: 'Yatay', vertical: 'Dikey', round: 'Yuvarlak' };
      const textureName = state.textureName || 'Seçili doku';
      const sizeText = `${state.width} × ${state.height} cm`;
      const totalPrice = els.priceTotal?.textContent || '';
      
      // Özelleştirilmiş link oluştur
      const currentUrl = new URL(window.location.href);
      currentUrl.searchParams.set('shape', state.shape);
      currentUrl.searchParams.set('texture', state.textureId || '');
      currentUrl.searchParams.set('width', state.width);
      currentUrl.searchParams.set('height', state.height);
      if (state.text) currentUrl.searchParams.set('text', state.text);
      if (state.textColor) currentUrl.searchParams.set('textColor', state.textColor);
      const designLink = currentUrl.toString();
      
      let message = wa.message || 'Merhaba, paspas tasarımım hakkında bilgi almak istiyorum.';
      message += `\n\n📐 Şekil: ${shapeNames[state.shape] || state.shape}`;
      message += `\n🎨 Doku: ${textureName}`;
      message += `\n📏 Ölçü: ${sizeText}`;
      if (state.text) message += `\n✏️ Yazı: "${state.text}"`;
      if (state.images.length > 0) message += `\n🖼️ Görsel: ${state.images.length} adet`;
      message += `\n💰 Toplam: ${totalPrice}`;
      message += `\n\n🔗 Tasarımım: ${designLink}`;
      
      // ÖNCE WhatsApp'ı aç
      const phone = wa.number.replace(/\D/g, '');
      const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
      window.open(whatsappUrl, '_blank');
      
      // Mobil cihazlarda Web Share API dene (opsiyonel)
      try {
        const blob = await new Promise(r => els.mainCanvas.toBlob(r, 'image/png'));
        const file = new File([blob], 'paspas-tasarimim.png', { type: 'image/png' });
        
        if (navigator.canShare && navigator.canShare({ files: [file] })) {
          await navigator.share({
            title: 'Paspas Tasarımım',
            text: message,
            files: [file]
          });
        }
      } catch (e) {
        // Web Share desteklenmiyor, sessizce devam et
        console.log('Web Share API desteklenmiyor:', e);
      }
    });
  }

  // ===== CART =====
  function addToCart() {
    showLoading(true);
    console.log('addToCart called - using form submit');
    
    const restData = typeof EMC_REST_DATA !== 'undefined' ? EMC_REST_DATA : {};
    const config = typeof EMC_CONFIG !== 'undefined' ? EMC_CONFIG : {};
    
    // Fiyat hesapla
    const pricePerImage = parseFloat(config.pricing?.price_per_image) || 0;
    const textExtra = parseFloat(config.pricing?.text_extra) || 0;
    const rawMin = config.pricing?.min_total;
    const minTotal = (rawMin !== undefined && rawMin !== '' && !Number.isNaN(parseFloat(rawMin))) ? parseFloat(rawMin) : 200;
    
    const texturePrice = state.texturePrice > 0 ? state.texturePrice : 20.45;
    const area = (state.width * state.height) / 10000;
    let productPrice = area * texturePrice * 100;
    
    if (minTotal > 0 && productPrice < minTotal) productPrice = minTotal;
    productPrice += (state.images.length * pricePerImage);
    if (state.text && state.text.trim()) productPrice += textExtra;
    
    const tax = productPrice * 0.1;
    const total = productPrice + state.shippingPrice + tax;
    
    // Logo verilerini hazırla (dataUrl veya src olabilir)
    const designImages = [];
    for (const img of state.images) {
      const imgData = img.dataUrl || img.src;
      if (imgData) {
        designImages.push({
          data_url: imgData,
          name: img.name || 'logo',
          x: img.x || 50,
          y: img.y || 50,
          width: img.width || 100,
          height: img.height || 100
        });
      }
    }
    
    // Form oluştur ve submit et
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.location.href;
    form.style.display = 'none';
    
    // Form alanları
    const fields = {
      'emc_add_to_cart': '1',
      'emc_cart_nonce': restData.nonce || '',
      'shape': state.shape,
      'texture_id': state.textureId || '',
      'texture_name': state.textureName || '',
      'width': state.width,
      'height': state.height,
      'text': state.text || '',
      'textColor': state.textColor || '#FFFFFF',
      'shipping': state.shippingId || '',
      'shipping_name': state.shippingName || '',
      'shipping_price': state.shippingPrice || 0,
      'images_count': state.images.length,
      'design_images': JSON.stringify(designImages),
      'base_price': productPrice,
      'tax': tax,
      'total': total,
      'preview_data_url': (state.selectedImageId = null, render(), els.mainCanvas.toDataURL('image/jpeg', 0.6))
    };
    
    for (const [key, value] of Object.entries(fields)) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = key;
      input.value = value;
      form.appendChild(input);
    }
    
    document.body.appendChild(form);
    console.log('Submitting form...');
    form.submit();
  }

  // ===== INIT =====
  function init() {
    cacheElements();
    if (!els.mainCanvas) {
      console.error('Canvas not found');
      return;
    }
    
    // Load watermark image
    window.emcWatermarkImg = new Image();
    window.emcWatermarkImg.crossOrigin = 'anonymous';
    window.emcWatermarkImg.onload = () => {
      console.log('Watermark logo loaded');
      render();
    };
    window.emcWatermarkImg.onerror = () => {
      console.error('Failed to load watermark logo');
    };
    // Try to load from theme assets, fallback to placeholder
    const logoPath = typeof EMC_THEME_URL !== 'undefined' 
      ? EMC_THEME_URL + '/assets/img/logo-header.png'
      : '/wp-content/themes/nextcore/assets/img/logo-header.png';
    window.emcWatermarkImg.src = logoPath;
    
    bindEvents();
    loadConfig();
    goToStep(1);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
