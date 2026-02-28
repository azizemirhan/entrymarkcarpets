# Entry Mark Paspas Özelleştirici & Satış Eklentisi — Teknik Spesifikasyon

Bu doküman, `tasarim/customizer.html` mantığının WordPress’e nasıl entegre edileceğini ve paspas özelleştirme + e-ticaret + PayTR ödeme sürecini tanımlar.

---

## 1. Mevcut Customizer Özeti (customizer.html)

### 1.1 Kullanıcı seçenekleri
| Alan | Açıklama | Şu an | Eklentide |
|------|----------|--------|-----------|
| **Yön (Orient)** | Yatay / Dikey / Yuvarlak | Sabit 3 seçenek | Aynı (admin’den kapatılabilir) |
| **Çökertme (Recess)** | Hayır (çerçeveli) / Evet (çerçevesiz) | Sabit | Aynı |
| **Renk** | Arka plan rengi | 24 sabit hex renk | **Admin’den yüklenen “yüzey doku görselleri”** (gerçek renk değil) |
| **Ölçü** | En × Boy (cm) | Sabit listeler (yatay/dikey/yuvarlak) | **Admin’den girilen ölçüler** |
| **Özel ölçü** | Min/max (10–500 cm) | Sabit | Admin’den min/max ve izin aç/kapa |
| **Logo** | Kullanıcı yükler | 5MB, image/* | Aynı (opsiyonel limit admin’den) |
| **Yazı** | Metin, kalın/italik, renk, font | 100 karakter, 7 font | Admin’den font listesi / max karakter |
| **Gönderim** | Standart / Express | Sabit +0 / +1500 TL | **Admin’den süre ve fiyat** |

### 1.2 Fiyatlandırma (mevcut)
- `base = (en * boy / 10000) * fiyat_m2` (min 200 TL)
- Express: sabit ek ücret
- KDV %10 (subtotal üzerinden)
- **Eklentide:** m² fiyatı, min tutar, express ücreti, KDV oranı admin’den.

### 1.3 Canvas çizim mantığı
- Arka plan: `ctx.fillStyle = state.color` → **Eklentide:** Seçilen “doku” görseli `createPattern(img, 'repeat')` veya tam alan `drawImage` ile kaplanacak (hex renk yok).
- Üzerine: texture (çizgi deseni), çerçeve (recess’e göre), logo (varsa), metin.
- **Doku:** Admin’in yüklediği görsel; frontend’de “renk” seçimi = doku görseli seçimi.

---

## 2. Eklenti Mimarisi

### 2.1 İsim ve kapsam
- **Eklenti adı:** Entry Mark Paspas (veya “Entry Mark Doormat Customizer”)
- **Sadece:** Paspas özelleştirme + sepete ekleme + sipariş + PayTR ile ödeme. Genel WooCommerce mağaza değil; tek ürün tipi: özelleştirilmiş paspas.

### 2.2 Veri modeli (Admin’den yönetilen)

1. **Yüzey dokuları (eski “renkler”)**
   - Her biri: ad (örn. “Siyah P0020”), **bir görsel** (paspas yüzey dokusu), sıra.
   - Frontend’de grid’de bu görseller gösterilir; seçilen doku canvas’ta arka planı kaplar (pattern veya stretch).

2. **Ölçü setleri**
   - Yön: yatay / dikey / yuvarlak.
   - Her yön için: (en, boy) listesi (cm). Yuvarlak için tek sayı (çap).
   - Özel ölçü: açık/kapalı, min_en, min_boy, max_en, max_boy (cm).

3. **Fiyatlandırma**
   - m² başına fiyat (TL), minimum sepet tutarı (TL), KDV oranı (%).
   - Gönderim seçenekleri: Standart (ad, ek ücret, tahmini gün), Express (ad, ek ücret, tahmini gün).

4. **Yazı / logo**
   - İzin verilen font listesi (isim + Google Font veya sistem fontu).
   - Max karakter sayısı (örn. 100).
   - Logo: max dosya boyutu (MB), izin verilen tipler (png, jpg, svg).

5. **PayTR**
   - Merchant ID, Merchant Key, Merchant Salt, test/canlı mod, başarı/hata/iptal callback URL’leri.

---

## 3. Admin Paneli

### 3.1 Menü
- **Entry Mark Paspas** ana menü:
  - **Dokular (Renkler):** Yüzey doku görselleri listesi. Ekle/Düzenle: Ad, görsel yükle (media library), sıra. Sıralama sürükle-bırak.
  - **Ölçüler:** Yatay / Dikey / Yuvarlak sekmeleri. Her biri için (en, boy) çiftleri ekle/sil. “Özel ölçü” toggle + min/max alanları.
  - **Fiyatlandırma:** m² fiyatı, min tutar, KDV %, gönderim seçenekleri (ad, ek ücret TL, tahmini gün).
  - **Yazı & Logo:** Font listesi, max karakter, logo max MB ve tipler.
  - **PayTR Ayarları:** Merchant ID, Key, Salt, mod, callback URL’leri (veya “otomatik”).
  - **Sayfa:** “Paspas özelleştirici” sayfası seçimi (dropdown: hangi sayfada shortcode kullanılıyor / özel sayfa).

### 3.2 Dokular (Renkler) ekranı
- Tablo: Sıra, Önizleme (küçük resim), Ad, İşlemler (Düzenle / Sil).
- “Yeni doku” butonu: Ad + Medya kütüphanesinden görsel. Frontend’de bu görsel “renk” yerine seçilecek; canvas’ta arka plan bu görsel ile doldurulacak.

---

## 4. Frontend (Özelleştirici Sayfası)

### 4.1 Sayfa ve shortcode
- Shortcode örn. `[entrymark_paspas_customizer]`.
- Bu shortcode’un olduğu sayfa tam ekran özelleştirici layout’u kullanır (sidebar + canvas + özet panel).
- Tasarım: `tasarim/customizer.html` ile aynı UX; veriler WP’den gelir.

### 4.2 Veri akışı
- Sayfa yüklenirken:
  - REST API veya `wp_localize_script` ile admin’den gelen veriler gönderilir:
    - **textures:** `[{ id, name, image_url }, ...]`
    - **sizes:** `{ horizontal: [{w,h},...], vertical: [...], round: [{w,h},...] }`, `custom_size: { enabled, min_w, min_h, max_w, max_h }`
    - **pricing:** `{ price_per_m2, min_total, tax_rate, shipping_options: [{ id, label, extra, days }] }`
    - **text_options:** `{ max_length, fonts: [{name, family}], logo_max_mb, logo_types }`
- Renk grid’i: Her “renk” aslında bir **doku** kartı: thumbnail = `image_url`, tıklanınca `state.textureId` / `state.textureImageUrl` güncellenir.
- Canvas’ta arka plan: `state.textureImageUrl` varsa bu görseli yükle, `createPattern(img,'repeat')` veya `drawImage` ile çiz; yoksa fallback (örn. gri).

### 4.3 Sepet ve sipariş
- “Sepete Ekle”: Mevcut state (orient, recess, texture_id, size, text, text options, logo base64 veya yüklenen dosya ID’si, shipping) JSON olarak saklanır.
- Sepet öğesi: “Özelleştirilmiş Paspas” + özet (ölçü, doku adı, vs.). Fiyat hesaplanmış toplam (frontend’deki formül ile aynı: m², min, KDV, kargo).
- Checkout’ta bu öğe normal satır olarak gösterilir; ödeme PayTR’ye yönlendirilir.
- Sipariş tamamlanınca: Sipariş meta’da tasarım verisi (state) ve mümkünse o anki canvas çıktısı (base64 veya yüklenen görsel URL’i) saklanır; admin/satıcı üretim için kullanır.

### 4.4 PayTR entegrasyonu
- Ödeme butonu: “PayTR ile Öde” (veya “Güvenli Ödeme”).
- Form/iframe: PayTR’nin beklediği parametreler (merchant_id, user_basket, payment_amount, currency, success_url, fail_url, no_installment, vb.) backend’de oluşturulur; token alınır; frontend’de iframe veya yönlendirme ile PayTR sayfasına gidilir.
- Callback (success/fail): PayTR’den gelen hash doğrulanır; sipariş durumu güncellenir (completed / failed); kullanıcı başarı/hata sayfasına yönlendirilir.

---

## 5. Teknik Notlar

### 5.1 Customizer.html’den uyarlanacaklar
- **State:** `color` → `textureId`, `textureImageUrl` (ve canvas’ta yüklü Image). `colorName` → doku adı.
- **buildColors():** Admin’den gelen `textures` ile grid’i doldur; her öğe bir `<img>` veya `background-image` ile doku önizlemesi.
- **render():** Arka plan: `ctx.fillStyle = pattern` (pattern = texture görselinden) veya `drawImage(textureImg, 0, 0, cs.w, cs.h)` (stretch/tile).
- **Sizes:** `sizes.horizontal` vb. doğrudan WP’den; custom size limitleri admin’den.
- **calcPrice():** `price_per_m2`, `min_total`, `shipping_options`, `tax_rate` kullan.

### 5.2 Eklenti dosya yapısı (öneri)
```
wp-content/plugins/entrymark-paspas/
├── entrymark-paspas.php          # Ana plugin, sabitler, loader
├── includes/
│   ├── class-emc-admin.php       # Admin menü, dokular, ölçüler, fiyat, PayTR ayarları
│   ├── class-emc-rest.php        # REST endpoint: textures, sizes, pricing, text_options
│   ├── class-emc-frontend.php    # Shortcode, script/style enqueue, localized config
│   ├── class-emc-cart.php       # Sepet öğesi, fiyat hesaplama, tasarım verisi
│   └── class-emc-paytr.php       # PayTR token, callback, doğrulama
├── assets/
│   ├── admin.css / admin.js
│   └── frontend/
│       ├── customizer.js         # customizer.html’deki engine (state, render, UI)
│       └── customizer.css        # customizer stilleri (inline’dan ayrıştırılmış)
└── templates/ (isteğe bağlı)
    └── shortcode-customizer.php  # Shortcode markup
```

### 5.3 Güvenlik ve validasyon
- REST ve form: nonce, capability (admin için `manage_options`, frontend için public).
- Sepete eklerken: ölçü/size listesinde var mı, texture_id geçerli mi, max karakter aşılmış mı kontrol.
- PayTR callback: hash kontrolü zorunlu; sipariş ID ve tutar eşleşmeli.

---

## 6. Özet Kontrol Listesi

- [ ] Admin: Doku (yüzey görseli) CRUD, sıralama.
- [ ] Admin: Ölçü setleri (yatay/dikey/yuvarlak) + özel ölçü limitleri.
- [ ] Admin: Fiyat (m², min, KDV) ve gönderim seçenekleri.
- [ ] Admin: Yazı (font listesi, max karakter), logo (max MB, tipler).
- [ ] Admin: PayTR (Merchant ID, Key, Salt, mod, URL’ler).
- [ ] Frontend: Shortcode ile özelleştirici sayfası; config WP’den.
- [ ] Frontend: “Renk” = doku seçimi; canvas’ta doku görseli ile arka plan.
- [ ] Frontend: Sepete ekle (tasarım state + hesaplanan fiyat).
- [ ] Checkout: PayTR ile ödeme, callback ile sipariş güncelleme.
- [ ] Sipariş: Tasarım verisi ve (mümkünse) önizleme görseli saklama.

Bu spesifikasyon, eklentinin sıfırdan yazılması ve `customizer.html` mantığının bire bir taşınması için yeterli çerçeveyi sağlar.
