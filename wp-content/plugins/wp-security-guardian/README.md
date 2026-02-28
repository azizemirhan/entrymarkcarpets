# Nextmedya Security

Nextmedya WordPress güvenlik eklentisi - Malware tarama, WAF, login koruması ve 2FA.

## Özellikler

- **Malware Scanner**: İmza tabanlı ve heuristic dosya tarama
- **Veritabanı Tarama**: wp_options içinde şüpheli içerik tespiti
- **Dosya Bütünlüğü**: WordPress core dosyalarının checksum kontrolü
- **WAF**: SQL injection, XSS, path traversal koruması
- **IP Engelleme**: Brute force sonrası otomatik engelleme
- **Login Koruması**: Başarısız giriş limiti, rate limiting
- **2FA**: TOTP (Google Authenticator uyumlu)
- **Karantina**: Enfekte dosyaları izole etme
- **Core Restore**: Değişen WordPress core dosyalarını geri yükleme

## Gereksinimler

- PHP 7.4+
- WordPress 5.8+

## Kurulum

1. `wp-content/plugins/wp-security-guardian` klasörüne yükleyin
2. `composer install` çalıştırın (2FA için)
3. WordPress admin'de eklentiyi etkinleştirin
4. Nextmedya Security menüsünden ayarları yapılandırın

## Kullanım

- **Tarama**: Nextmedya Security > Tarama > Tarama Başlat
- **2FA**: Kullanıcılar > Profil > İki Faktörlü Kimlik Doğrulama
- **WAF**: Ayarlar sayfasından etkinleştirin
