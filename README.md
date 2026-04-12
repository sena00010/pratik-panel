# Pratik Gümrük Tanıtım Sitesi

PHP + MySQL ile hazırlanmış tanıtım sitesi, blog, modül detay sayfaları ve gizli yönetim paneli.

## Kurulum

1. `config/app.php` içindeki veritabanı bilgilerini kontrol edin.
2. phpMyAdmin üzerinden `database/schema.sql` dosyasını içeri aktarın.
3. Alternatif olarak XAMPP PHP ile migration çalıştırın:

```bash
/Applications/XAMPP/xamppfiles/bin/php database/migrate.php
```

## Admin

Yönetim paneli yolu `config/app.php` içindeki `admin_path` alanından değiştirilebilir.

İlk giriş bilgileri:

- Kullanıcı adı: `admin`
- Şifre: `PratikGumruk2026`

Girişten sonra şifreyi veritabanındaki `admin_users.password_hash` alanını güncelleyerek değiştirin.

## İçerik

- Modül kartları ve modül detayları `modules` tablosundan gelir.
- SSS alanı `faqs` tablosundan gelir.
- Blog liste ve detay sayfaları `blog_posts` tablosundan gelir.
- SEO başlık/açıklama/anahtar kelime kayıtları `seo_meta` tablosundan yönetilir.

Tüm tablolar `utf8mb4_turkish_ci` ile oluşturulur.
