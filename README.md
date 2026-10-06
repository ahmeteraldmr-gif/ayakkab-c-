# Yusuf Akboğa Ayakkabı — Modern & Premium E-Ticaret Platformu

Yusuf Akboğa Ayakkabı, modern sneaker ve klasik ayakkabı koleksiyonları için tasarlanmış, tam donanımlı, yüksek performanslı ve mobil uyumlu bir Laravel e-ticaret platformudur.

---

## 🚀 Özellikler

- **Vitrin & Katalog Deneyimi:**
  - Modern, ferah ve responsive arayüz (375px - 1440px ekran uyumu).
  - AJAX tabanlı anlık arama (debounced) ve canlı filtreleme (cinsiyet, kategori, marka, numara, fiyat aralığı).
  - Beden/numara stok matrisi ve anlık stok doğrulama.
  - Ziyaretçi dostu yerel favori listesi (LocalStorage).
  - Dinamik SEO, Open Graph ve Twitter Cards meta etiketleri.

- **Sepet & Sipariş Güvenliği:**
  - Server-side fiyat ve stok yeniden hesaplama (`OrderService`).
  - Session manipülasyonuna karşı tam koruma.
  - Güvenli sipariş başarı sayfası (IDOR korumalı `access_token` ve session doğrulaması).
  - Çift sipariş oluşturmayı engelleyen double-submit koruması.

- **Admin Yönetim Paneli:**
  - Bağımsız modern SaaS yönetim arayüzü (#111827 sidebar & #F6F8FC canvas).
  - Chart.js satış grafiği ve KPI istatistik kartları.
  - Ürün, beden stok matrisi, sipariş, kategori, marka, kampanya ve mesaj yönetimi.
  - Sipariş durum akış kuralları (`Order State Machine`).
  - Kritik işlemler için Audit Log (İşlem Denetim Kaydı) sistemi.
  - Yanlışlıkla silmeyi önleyen onaylama modalları (`Universal Confirmation Modal`).

---

## 🛠️ Teknoloji Yığını

- **Backend:** PHP 8.2+, Laravel 11 / 12
- **Veritabanı:** SQLite / MySQL / PostgreSQL
- **Frontend:** Blade, Tailwind CSS, Vanilla JavaScript, FontAwesome 6, Chart.js
- **Güvenlik:** Rate Limiting (Throttling), Security Headers, Access Tokens, MIME Whitelisting

---

## 💻 Kurulum Adımları

### 1. Projeyi Klonlayın veya İndirin
```bash
cd ayakkabici
```

### 2. Bağımlılıkları Yükleyin
```bash
composer install
```

### 3. Ortam Değişkenlerini Ayarlayın
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Veritabanını Hazırlayın ve Migration'ları Çalıştırın
```bash
php artisan migrate --seed
```

### 5. Storage Sembolik Bağlantısını Oluşturun
```bash
php artisan storage:link
```

### 6. Geliştirme Sunucusunu Başlatın
```bash
php artisan serve
```
Siteye tarayıcınızdan `http://127.0.0.1:8000` adresinden erişebilirsiniz.

---

## 🔑 Varsayılan Yönetici Bilgileri

- **Giriş URL:** `http://127.0.0.1:8000/admin/login`
- **E-posta:** `admin@yusufakboga.com`
- **Şifre:** `password`

---

## 🧪 Testleri Çalıştırma

Tüm güvenlik, sipariş doğrulaması, IDOR engelleme ve regresyon testlerini çalıştırmak için:

```bash
php artisan test
```

---

## 📦 Yedekleme (Backup) ve Kurtarma Rehberi

### Yedeklenmesi Gereken Dizinler & Veriler:
1. **Veritabanı:**
   - SQLite kullanılıyorsa: `database/database.sqlite`
   - MySQL kullanılıyorsa: `mysqldump -u <user> -p <database> > backup.sql`
2. **Kullanıcı Yüklemeleri:**
   - `storage/app/public/` (ürün görselleri, logolar, ayar dosyaları)
3. **Konfigürasyon:**
   - `.env` dosyası (güvenli bir ortamda saklanmalıdır)

### Geri Yükleme (Restore) Adımları:
1. Dosya ve storage yedeğini ilgili dizinlere aktarın.
2. Veritabanı yedeğini içe aktarın:
   ```bash
   # MySQL için:
   mysql -u <user> -p <database> < backup.sql
   ```
3. Cache temizleyin:
   ```bash
   php artisan optimize:clear
   php artisan storage:link
   ```

---

## 🌐 Production Deployment Kontrol Listesi

- [ ] `.env` içinde `APP_ENV=production` ve `APP_DEBUG=false` yapın.
- [ ] `APP_KEY` anahtarının tanımlandığından emin olun.
- [ ] `SESSION_SECURE_COOKIE=true` aktif edin (HTTPS kullanılıyorsa).
- [ ] `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache` komutlarını çalıştırın.
- [ ] Web sunucunuzda (Nginx / Apache) `public/` dizinini DocumentRoot olarak ayarlayın.
