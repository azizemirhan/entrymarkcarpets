<?php
/**
 * Entry Mark Paspas - Modern Customizer Interface
 * Fully Responsive - Mobile First Design
 */
if (!defined('ABSPATH')) exit;
?>
<div class="emc-app-wrapper">
  <div class="emc-customizer">
    
    <!-- Progress Steps -->
    <div class="emc-progress">
      <div class="emc-progress-track">
        <div class="emc-progress-fill" style="width: 20%"></div>
      </div>
      <div class="emc-progress-steps">
        <div class="emc-step active" data-step="1">
          <div class="step-dot">1</div>
          <span class="step-name">Şekil</span>
        </div>
        <div class="emc-step" data-step="2">
          <div class="step-dot">2</div>
          <span class="step-name">Doku</span>
        </div>
        <div class="emc-step" data-step="3">
          <div class="step-dot">3</div>
          <span class="step-name">Ölçü</span>
        </div>
        <div class="emc-step" data-step="4">
          <div class="step-dot">4</div>
          <span class="step-name">Tasarım</span>
        </div>
        <div class="emc-step" data-step="5">
          <div class="step-dot">5</div>
          <span class="step-name">Sipariş</span>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="emc-content">
      
      <!-- LEFT/MAIN: Canvas Area -->
      <div class="emc-canvas-section">
        <div class="canvas-card">
          <div class="canvas-header">
            <h3>Tasarım Önizleme</h3>
            <span class="drag-hint">🖱️ Sürükleyerek konumlandır</span>
          </div>
          
          <div class="canvas-container">
            <canvas id="emcMainCanvas" width="640" height="480"></canvas>
            <div class="canvas-badge" id="emcBadge">40 × 70 cm</div>
          </div>
          
          <div class="canvas-zoom">
            <button class="zoom-btn" id="emcZoomOut" title="Uzaklaştır">−</button>
            <span class="zoom-value" id="emcZoomValue">100%</span>
            <button class="zoom-btn" id="emcZoomIn" title="Yakınlaştır">+</button>
            <button class="zoom-btn zoom-reset" id="emcZoomReset" title="Sığdır">⟲</button>
          </div>
        </div>
      </div>

      <!-- RIGHT: Controls & Summary -->
      <div class="emc-controls-section">
        
        <!-- Step 1: Shape -->
        <div class="control-panel active" data-panel="1">
          <h4 class="panel-title">Paspas Şekli Seçin</h4>
          
          <div class="shape-grid">
            <div class="shape-option active" data-shape="horizontal">
              <div class="shape-icon">
                <div class="shape-visual horizontal"></div>
              </div>
              <span>Yatay</span>
            </div>
            <div class="shape-option" data-shape="vertical">
              <div class="shape-icon">
                <div class="shape-visual vertical"></div>
              </div>
              <span>Dikey</span>
            </div>
            <div class="shape-option" data-shape="round">
              <div class="shape-icon">
                <div class="shape-visual round"></div>
              </div>
              <span>Yuvarlak</span>
            </div>
          </div>

          <div class="option-group">
            <label>Gömme Halı</label>
            <div class="toggle-group">
              <button class="toggle-btn active" data-recess="no">Çerçeveli</button>
              <button class="toggle-btn" data-recess="yes">Çerçevesiz</button>
            </div>
          </div>
        </div>

        <!-- Step 2: Texture -->
        <div class="control-panel" data-panel="2">
          <h4 class="panel-title">Doku Seçin</h4>
          <div class="texture-list" id="emcTextureList">
            <!-- JS ile doldurulacak -->
          </div>
        </div>

        <!-- Step 3: Size -->
        <div class="control-panel" data-panel="3">
          <h4 class="panel-title">Ölçü Seçin</h4>
          <div class="size-list" id="emcSizeList">
            <!-- JS ile doldurulacak -->
          </div>
          
          <div class="custom-size-toggle" id="emcToggleCustom">
            <span>Özel ölçü gir</span>
            <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          
          <div class="custom-size-form" id="emcCustomForm">
            <div class="custom-inputs">
              <input type="number" id="emcCustomW" placeholder="En (cm)" min="20" max="500">
              <span>×</span>
              <input type="number" id="emcCustomH" placeholder="Boy (cm)" min="20" max="500">
            </div>
            <button class="btn-primary btn-small" id="emcApplyCustom">Uygula</button>
          </div>
        </div>

        <!-- Step 4: Design -->
        <div class="control-panel" data-panel="4">
          <h4 class="panel-title">Tasarımınızı Ekleyin</h4>
          
          <!-- Image Upload -->
          <div class="upload-section">
            <label>Görseller <small>(birden fazla seçebilirsiniz)</small></label>
            <div class="upload-zone" id="emcUploadZone">
              <input type="file" id="emcImageInput" accept="image/*" multiple hidden>
              <div class="upload-content">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <p>Sürükleyin veya <strong>tıklayın</strong></p>
                <span>PNG, JPG • Max 5MB her biri</span>
              </div>
            </div>
            
            <!-- Uploaded Images -->
            <div class="image-list" id="emcImageList"></div>
          </div>

          <!-- Text Input -->
          <div class="text-section">
            <label>Yazı Ekle</label>
            <textarea id="emcTextInput" placeholder="Metninizi yazın..." maxlength="100" rows="2"></textarea>
            <div class="text-counter"><span id="emcTextCount">0</span>/100</div>
            
            <div class="text-format">
              <button class="format-btn" id="emcBoldBtn" title="Kalın"><b>B</b></button>
              <button class="format-btn" id="emcItalicBtn" title="İtalik"><i>I</i></button>
              <input type="color" id="emcTextColor" value="#FFFFFF" title="Renk">
              <select id="emcFontSelect">
                <!-- JS ile EMC_CONFIG.text_options.fonts üzerinden doldurulur -->
                <option value="Arial, sans-serif">Arial</option>
              </select>
            </div>
            <div class="text-size-row">
              <label for="emcTextSize">Yazı boyutu</label>
              <div class="text-size-control">
                <input type="range" id="emcTextSize" min="50" max="300" value="100" step="5" title="Yazı boyutu">
                <span class="text-size-value" id="emcTextSizeValue">100%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Step 5: Shipping -->
        <div class="control-panel" data-panel="5">
          <h4 class="panel-title">Gönderim Seçeneği</h4>
          <div class="shipping-list" id="emcShippingList">
            <!-- JS ile doldurulacak -->
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="panel-nav">
          <button class="btn-secondary" id="emcPrevBtn" style="display:none">← Geri</button>
          <button class="btn-primary" id="emcNextBtn">İleri →</button>
        </div>
      </div>

      <!-- BOTTOM: Order Summary (Always visible on desktop, collapsible on mobile) -->
      <div class="emc-summary-section">
        <div class="summary-card">
          <div class="summary-header" id="emcSummaryToggle">
            <h4>Sipariş Özeti</h4>
            <span class="summary-price" id="emcSummaryPrice">0 TL</span>
            <svg class="summary-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          
          <div class="summary-content" id="emcSummaryContent">
            <div class="summary-preview">
              <canvas id="emcSummaryCanvas" width="280" height="200"></canvas>
            </div>
            
            <div class="summary-details">
              <div class="detail-item">
                <span>Şekil</span>
                <strong id="emcSumShape">Yatay</strong>
              </div>
              <div class="detail-item">
                <span>Doku</span>
                <strong id="emcSumTexture">—</strong>
              </div>
              <div class="detail-item">
                <span>Ölçü</span>
                <strong id="emcSumSize">40 × 70 cm</strong>
              </div>
              <div class="detail-item">
                <span>Gönderim</span>
                <strong id="emcSumShipping">—</strong>
              </div>
            </div>
            
            <div class="summary-totals">
              <div class="total-row">
                <span>Ürün</span>
                <span id="emcPriceProduct">0 TL</span>
              </div>
              <div class="total-row">
                <span>Kargo</span>
                <span id="emcPriceShip">0 TL</span>
              </div>
              <div class="total-row">
                <span>KDV (%10)</span>
                <span id="emcPriceTax">0 TL</span>
              </div>
              <div class="total-final">
                <span>Toplam</span>
                <strong id="emcPriceTotal">0 TL</strong>
              </div>
            </div>
            
            <button class="btn-add-cart" id="emcAddCart">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              Sepete Ekle
            </button>
            
            <button class="btn-whatsapp" id="emcWhatsApp" style="display:none;">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
              <span>WhatsApp ile Sipariş Ver</span>
            </button>
            
            <div class="summary-actions">
              <button class="action-btn" id="emcDownload" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="7 10 12 15 17 10"/>
                  <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                İndir
              </button>
              <button class="action-btn" id="emcShare" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="18" cy="5" r="3"/>
                  <circle cx="6" cy="12" r="3"/>
                  <circle cx="18" cy="19" r="3"/>
                  <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                  <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
                Paylaş
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div class="emc-toast" id="emcToast"></div>
    
    <!-- Loading Overlay -->
    <div class="emc-loading" id="emcLoading">
      <div class="loading-spinner"></div>
      <span>İşleniyor...</span>
    </div>

    <!-- Önizleme sayfası (Sepete Ekle öncesi son hal) -->
    <div class="emc-preview-overlay" id="emcPreviewOverlay" aria-hidden="true">
      <div class="emc-preview-backdrop" id="emcPreviewBackdrop"></div>
      <div class="emc-preview-modal">
        <div class="emc-preview-header">
          <h3>Tasarım Önizleme</h3>
          <button type="button" class="emc-preview-close" id="emcPreviewClose" title="Kapat">×</button>
        </div>
        <p class="emc-preview-desc">Paspas tasarımınızın son hali. Sepete eklemek için aşağıdaki butonu kullanın.</p>
        <div class="emc-preview-content">
          <div class="emc-preview-canvas-area">
            <div class="emc-preview-canvas-wrap">
              <img id="emcPreviewImage" alt="Paspas tasarım önizleme" class="emc-preview-image">
              <span class="emc-preview-size-badge" id="emcPreviewSizeBadge">40 × 70 cm</span>
            </div>
            <button type="button" class="emc-preview-zoom-btn" id="emcPreviewZoomBtn" title="Tam ekran incele">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
              </svg>
              <span>Büyüteç</span>
            </button>
          </div>
          <div class="emc-preview-summary">
            <div class="emc-preview-detail"><span>Şekil</span><strong id="emcPreviewShape">Yatay</strong></div>
            <div class="emc-preview-detail"><span>Doku</span><strong id="emcPreviewTexture">—</strong></div>
            <div class="emc-preview-detail"><span>Ölçü</span><strong id="emcPreviewSize">40 × 70 cm</strong></div>
            <div class="emc-preview-detail"><span>Gönderim</span><strong id="emcPreviewShipping">—</strong></div>
            <div class="emc-preview-total">
              <span>Toplam</span>
              <strong id="emcPreviewTotal">0 TL</strong>
            </div>
            <div class="emc-preview-actions">
              <button type="button" class="btn-secondary emc-preview-back-btn" id="emcPreviewBack">Düzenlemeye Dön</button>
              <button type="button" class="btn-add-cart emc-preview-add-cart" id="emcPreviewAddCart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                Sepete Ekle
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Tam sayfa tasarım görüntüleme (büyüteç tıklanınca) -->
      <div class="emc-preview-fullscreen" id="emcPreviewFullscreen" aria-hidden="true">
        <button type="button" class="emc-preview-fullscreen-close" id="emcPreviewFullscreenClose" title="Kapat">×</button>
        <div class="emc-preview-fullscreen-backdrop" id="emcPreviewFullscreenBackdrop"></div>
        <div class="emc-preview-fullscreen-zoom-controls">
          <button type="button" class="emc-fs-zoom-btn" id="emcFsZoomOut" title="Uzaklaştır">−</button>
          <span class="emc-fs-zoom-value" id="emcFsZoomValue">100%</span>
          <button type="button" class="emc-fs-zoom-btn" id="emcFsZoomIn" title="Yakınlaştır">+</button>
          <button type="button" class="emc-fs-zoom-btn emc-fs-zoom-reset" id="emcFsZoomReset" title="Sıfırla">1:1</button>
        </div>
        <div class="emc-preview-fullscreen-img-wrap" id="emcPreviewFullscreenImgWrap">
          <img id="emcPreviewFullscreenImage" alt="Paspas tasarımı tam ekran" class="emc-preview-fullscreen-img">
        </div>
      </div>
    </div>
  </div>
</div>
