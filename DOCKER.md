# Docker ile Çalıştırma

## Gereksinimler

- Docker ve Docker Compose yüklü olmalı.

## Hızlı başlangıç

```bash
# İsteğe bağlı: .env dosyası (varsayılanlar zaten docker-compose'ta)
cp .env.example .env

# Build ve çalıştır
docker compose up -d --build
```

Site adresi: **http://localhost:9080**

- **İlk çalıştırmada** `entrymarkcarpets-export.sql` dosyası otomatik olarak `wordpress` veritabanına yüklenir; kurulum ekranı yerine mevcut site açılır.
- Veritabanı volume'u zaten doluysa (daha önce `up` çalıştırdıysanız) SQL tekrar çalışmaz. Sıfırdan import için: `docker compose down -v` sonra tekrar `docker compose up -d`.
- Veritabanı bilgileri: DB: `wordpress`, kullanıcı: `wordpress`, şifre: `wordpress`, sunucu: `db`.

## Ortam değişkenleri (.env)

| Değişken | Varsayılan | Açıklama |
|----------|------------|----------|
| `DB_NAME` | wordpress | Veritabanı adı |
| `DB_USER` | wordpress | Veritabanı kullanıcısı |
| `DB_PASSWORD` | wordpress | Veritabanı şifresi |
| `MYSQL_ROOT_PASSWORD` | rootpassword | MySQL root şifresi |
| `WP_URL` | http://localhost:9080 | Site adresi (farklı port kullanıyorsanız güncelleyin) |
| `WP_DEBUG` | false | Hata ayıklama (true/false) |

## Komutlar

```bash
# Durdur
docker compose down

# Logları izle
docker compose logs -f wordpress

# Sadece build (çalıştırmadan)
docker compose build
```

## SQL import

- Proje kökündeki `entrymarkcarpets-export.sql`, ilk açılışta (veritabanı volume'u boşken) `wordpress` veritabanına otomatik yüklenir.
- Yeni bir SQL ile baştan başlamak için: `docker compose down -v` (volume'ları siler), ardından `docker compose up -d`.

## Notlar

- **wp-config.php**: Docker ortamında container başlarken ortam değişkenlerinden otomatik oluşturulur. Proje kökünde `wp-config.php` varsa ve image build edilirken `.dockerignore` ile hariç tutulduğu için container içinde yoktur; entrypoint yeni bir tane yazar.
- Yerel bir `wp-config.php` kullanıyorsanız ve Docker’da onu kullanmak istiyorsanız, içinde `DB_HOST` değerini `db` yapın ve dosyayı volume ile bağlayabilirsiniz (gelişmiş kullanım).
- Yüklenen medya dosyaları `wp-content/uploads` volume’da saklanır; `docker compose down -v` kullanırsanız volume’lar da silinir.
