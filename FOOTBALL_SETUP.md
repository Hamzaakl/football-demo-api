# Football Live - Canlı Futbol Takip Platformu

Bu proje RapidAPI Football API'si kullanarak canlı futbol skorlarını takip etme ve maçlar için canlı sohbet özelliği sunan bir Laravel uygulamasıdır.

## Özellikler

- 🏈 **Canlı Futbol Skorları**: RapidAPI Football API entegrasyonu
- 💬 **Canlı Sohbet**: Her maç için gerçek zamanlı sohbet odaları
- 📊 **Maç İstatistikleri**: Detaylı maç verileri ve istatistikler
- 🎯 **Gerçek Zamanlı Güncellemeler**: WebSocket ile canlı veri akışı
- 📱 **Responsive Tasarım**: Mobil uyumlu modern arayüz

## Kurulum

### 1. Bağımlılıkları Yükleyin

```bash
composer install
npm install
```

### 2. Çevre Değişkenlerini Ayarlayın

`.env.example` dosyasını kopyalayıp `.env` olarak kaydedin ve aşağıdaki ayarları yapın:

```env
APP_NAME="Football Live"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=football_demo_api
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=pusher

# RapidAPI Football API Key
# Bu key'i https://rapidapi.com/api-sports/api/api-football adresinden alabilirsiniz
RAPIDAPI_FOOTBALL_KEY=your_rapidapi_football_key_here

# Pusher Ayarları (Canlı sohbet için)
# https://pusher.com adresinden ücretsiz hesap oluşturabilirsiniz
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_app_key
PUSHER_APP_SECRET=your_pusher_app_secret
PUSHER_APP_CLUSTER=mt1
```

### 3. Uygulama Anahtarı Oluşturun

```bash
php artisan key:generate
```

### 4. Veritabanını Oluşturun ve Migrationları Çalıştırın

```bash
# Veritabanını oluşturun (MySQL)
mysql -u root -p
CREATE DATABASE football_demo_api;
exit

# Migration'ları çalıştırın
php artisan migrate
```

### 5. Storage Linkini Oluşturun

```bash
php artisan storage:link
```

### 6. Frontend Varlıklarını Derleyin

```bash
npm run dev
# veya production için
npm run build
```

## API Anahtarları Nasıl Alınır?

### RapidAPI Football API

1. [RapidAPI](https://rapidapi.com) sitesine kayıt olun
2. [API-Football](https://rapidapi.com/api-sports/api/api-football) sayfasına gidin
3. "Subscribe to Test" butonuna tıklayın
4. Ücretsiz planı seçin (ayda 100 istek)
5. API anahtarınızı kopyalayıp `.env` dosyasına ekleyin

### Pusher (Canlı Sohbet için)

1. [Pusher](https://pusher.com) sitesine kayıt olun
2. Yeni bir uygulama oluşturun
3. "App Keys" sekmesinden anahtarları kopyalayın
4. `.env` dosyasına PUSHER bilgilerini ekleyin

## Kullanım

### Sunucuyu Başlatın

```bash
php artisan serve
```

Uygulama http://localhost:8000 adresinde çalışacaktır.

### Veri Senkronizasyonu

Canlı maç verilerini API'den çekmek için:

```bash
# Manual senkronizasyon
curl -X POST http://localhost:8000/api/football/sync-data

# Veya tarayıcıda API endpoint'ini ziyaret edin
```

### Cron Job Kurulumu (İsteğe Bağlı)

Otomatik veri güncellemesi için cron job ekleyin:

```bash
# Crontab'ı düzenleyin
crontab -e

# Her 2 dakikada bir veri güncellemesi
*/2 * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

## API Endpoints

### Football API

- `GET /api/football/live-scores` - Canlı skorlar
- `GET /api/football/todays-matches` - Bugünkü maçlar
- `GET /api/football/popular-leagues` - Popüler ligler
- `GET /api/football/match/{id}` - Maç detayları
- `POST /api/football/sync-data` - Veri senkronizasyonu

### Chat API

- `GET /api/chat/fixture/{id}/messages` - Maç sohbet mesajları
- `POST /api/chat/fixture/{id}/send` - Mesaj gönder
- `GET /api/chat/fixture/{id}/stats` - Sohbet istatistikleri

## Sayfalar

- `/` - Ana sayfa (bugünkü ve canlı maçlar)
- `/match-tracking/match/{id}` - Maç detay sayfası (canlı sohbet ile)
- `/match-tracking/live-scores` - Canlı skorlar sayfası
- `/match-tracking/league/{id}` - Lig sayfası
- `/match-tracking/team/{id}` - Takım sayfası
- `/match-tracking/search` - Arama sayfası

## Teknolojiler

- **Backend**: Laravel 9+, MySQL, Pusher
- **Frontend**: Bootstrap 5, FontAwesome, Laravel Echo
- **API**: RapidAPI Football API
- **Real-time**: Pusher WebSockets
- **Styling**: Custom CSS with modern gradients and animations

## Özelleştirme

### Yeni Ligler Eklemek

`app/Http/Controllers/MatchTrackingController.php` dosyasındaki `index` metodunu düzenleyin:

```php
$popularLeagues = League::current()
    ->whereIn('country', ['Turkey', 'England', 'Spain', 'Italy', 'Germany', 'Your Country'])
    ->get();
```

### Chat Özelliklerini Genişletmek

`app/Http/Controllers/Api/ChatController.php` dosyasında yeni fonksiyonlar ekleyebilirsiniz:

- Mesaj filtreleme
- Kullanıcı banı
- Mesaj raporlama
- Emoji desteği

### Tasarımı Özelleştirmek

`resources/views/layouts/app.blade.php` dosyasındaki CSS değişkenlerini düzenleyin:

```css
:root {
  --primary-color: #your-color;
  --secondary-color: #your-secondary-color;
  /* ... */
}
```

## Sorun Giderme

### API Limiti Aşılırsa

RapidAPI ücretsiz planında aylık 100 istek limiti vardır. Limit aşılırsa:

1. Ücretli plana geçiş yapın
2. Veya veri senkronizasyon sıklığını azaltın

### Pusher Bağlantı Sorunu

```javascript
// Tarayıcı konsolunda kontrol edin
console.log(window.Echo);

// Pusher anahtarlarını doğrulayın
```

### Veritabanı Bağlantı Hatası

1. MySQL servisinin çalıştığından emin olun
2. `.env` dosyasındaki veritabanı bilgilerini kontrol edin
3. Veritabanının oluşturulduğundan emin olun

## Lisans

Bu proje MIT lisansı altında yayınlanmıştır.

## Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Değişiklikleri commit edin (`git commit -m 'Add some amazing feature'`)
4. Branch'i push edin (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## İletişim

Herhangi bir sorunuz için GitHub Issues kullanabilirsiniz.

---

🏈 **Football Live** - Canlı futbol heyecanını yaşayın!
