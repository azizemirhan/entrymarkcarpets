# İbrahim Bey’e Bilgilendirme Mesajı (23.02.2026)

Aşağıdaki metni WhatsApp veya e-posta ile İbrahim Bey’e iletebilirsiniz.

---

**İbrahim Bey merhaba,**

Ödemeyi aldıktan sonra entrymarkcarpets.com altyapınız üzerinde çalışmaları tamamladım. Sizin için yapılan işlerin özeti aşağıdadır.

---

## 1. Kişiselleştirme Paneli (mistercarpets.tr benzeri)

Talep ettiğiniz **“Kişiselleştir”** modülü sitenize entegre edildi. Müşterileriniz:

- **Şekil:** Yatay / dikey / yuvarlak paspas formu seçebilir.
- **Doku (renk):** Admin panelinden eklediğiniz yüzey dokularından birini seçer; canlı önizlemede paspas yüzeyi buna göre güncellenir.
- **Ölçü:** Hazır ölçüler (yatay/dikey/yuvarlak setleri) veya özel en × boy (cm) girebilir; fiyat seçime göre anlık hesaplanır.
- **Tasarım:** Kendi logosunu veya görselini yükleyebilir, metin ekleyebilir (font, renk, kalın/italik). Önizleme alanında sürükleyerek konumlandırabilir.
- **Sipariş:** Gönderim seçeneğini (Standart / Express vb.) seçer, toplam fiyatı görür ve **Sepete Ekle** ile devam eder.

Tüm bu seçimler **canlı önizleme** ve **dinamik fiyat** ile çalışıyor; müşteri tasarımını bitirip sepete eklediğinde seçimler ve hesaplanan tutar siparişe kaydediliyor.

---

## 2. Sepet ve PayTR ile Ödeme

- Müşteri özelleştirmeyi tamamlayıp **Sepete Ekle** dediğinde ürün sepete eklenir.
- **Ödeme sayfasında** (sizin belirleyeceğiniz sayfa) sipariş özeti ve **PayTR ile güvenli ödeme** butonu yer alır. Ödeme tamamlandığında PayTR callback’i ile sipariş durumu güncellenir; müşteri başarı/hata mesajıyla yönlendirilir.

---

## 3. WordPress Admin Tarafı (Sizin Yönetiminiz)

**Paspas** menüsü altında:

- **Dokular:** Paspas yüzeyi olarak kullanılacak görselleri (ad + medya kütüphanesinden görsel) ekleyip sıralayabilirsiniz.
- **Ölçüler:** Yatay, dikey ve yuvarlak için standart ölçü listeleri; özel ölçü izni ve min/max değerleri.
- **Fiyat & Gönderim:** m² fiyatı, minimum tutar, KDV oranı; gönderim seçenekleri (Standart, Express vb.) ve ek ücretler.
- **Yazı & Logo:** Maksimum karakter sayısı, logo için max dosya boyutu, kullanılacak fontlar.
- **PayTR:** Merchant ID, Key, Salt; test/canlı mod; ödeme sayfası olarak kullanılacak sayfanın seçimi.
- **Siparişler:** Gelen siparişler listelenir; her siparişte müşterinin ne tasarladığı ve yüklediği dosya/veriler üretim için kullanılabilir.

---

## 4. Site Genel Yönetimi (Header & Footer)

- **Header ve footer** metinleri, logolar, iletişim ve sosyal medya linkleri **Next Content → Genel Ayarlar** (Header / Footer sekmeleri) üzerinden düzenleniyor.
- **Menüler** (üst menü ve mega menü, footer sütun linkleri) **Görünüm → Menüler** üzerinden yönetiliyor; tasarımınıza uygun şekilde ayarlanmış durumda.

---

## 5. Sizin Yapmanız Gerekenler (Kısa)

1. **Kişiselleştirme sayfası:** Yeni bir sayfa açıp içine şu shortcode’u ekleyin: **`[entrymark_paspas_customizer]`**. Bu sayfa müşterilerin paspası tasarlayacağı sayfa olacak.
2. **Ödeme sayfası:** İçinde **`[entrymark_paspas_checkout]`** shortcode’u olan bir sayfa oluşturun; **Paspas → PayTR** ekranında “Ödeme sayfası” olarak bu sayfayı seçin.
3. **PayTR canlı bilgileri:** Gerçek ödeme almak için PayTR panelinizden Merchant ID, Key ve Salt’ı alıp **Paspas → PayTR** bölümüne girin ve test modunu kapatın.
4. **Dokular:** **Paspas → Dokular** ekranından en az bir yüzey dokusu (görsel + isim) ekleyin; böylece kişiselleştiricide “renk/doku” seçimi görünür ve çalışır.

Bu adımlardan sonra müşteriler kişiselleştirme sayfasından tasarım yapıp sepete ekleyebilir ve PayTR üzerinden ödeme tamamlayabilir.

---

İsterseniz önce **test modunda** (PayTR test modu açık) bir deneme siparişi vererek akışı kontrol edebilirsiniz. Teknik bir sorunuz veya ek geliştirme talebiniz olursa yazmanız yeterli.

İyi çalışmalar,  
Aziz
