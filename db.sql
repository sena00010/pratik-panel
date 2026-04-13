-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 13 Nis 2026, 15:46:18
-- Sunucu sürümü: 10.4.24-MariaDB
-- PHP Sürümü: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `pratik_gumruk`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(80) COLLATE utf8mb4_turkish_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$zDWXnPtJrr/3QQ8I/Y4EEOPnRsbgxAfX8Sdn86c42Sl.bTTfpfRE6', '2026-04-12 15:16:21'),
(3, 'anılcan', 'anılcan2026.', '2026-04-13 13:28:41'),
(5, 'amilcan', '$2y$10$CthIge7jaQRSKIil3vRWpeUbAQo3MqrC9jdkkZyerPWfZofSOmE0S', '2026-04-13 13:32:48');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(220) COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_turkish_ci NOT NULL,
  `summary` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `content` mediumtext COLLATE utf8mb4_turkish_ci NOT NULL,
  `cover_image` varchar(500) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `published_at` datetime NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `summary`, `content`, `cover_image`, `published_at`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'GTİP tespitinde en sık yapılan 5 hata', 'gtip-tespitinde-en-sik-yapilan-5-hata', 'Yanlış ürün tanımı, eksik teknik bilgi ve emsal karar kontrolü yapılmaması ithalat maliyetini doğrudan etkiler.', 'GTİP tespitinde hata çoğu zaman ürün adının tek başına yeterli sanılmasından kaynaklanır. Teknik özellikler, kullanım amacı, malzeme bilgisi ve emsal BTB kararları birlikte değerlendirilmelidir.\n\nPratik Gümrük bu alanları tek akışta toplar ve sonuçları kaynakla birlikte sunar.', '', '2026-04-12 10:00:00', 1, '2026-04-12 15:16:21', NULL),
(2, 'Belge kontrolü neden beyanname öncesinde yapılmalı?', 'belge-kontrolu-neden-beyanname-oncesinde-yapilmali', 'Fatura, konşimento ve menşe belgesi arasındaki küçük uyumsuzluklar işlem süresini uzatabilir.', 'Beyanname hazırlanmadan önce belge kontrolü yapmak operasyon süresini kısaltır. Tutar, miktar, menşe ve ürün tanımı alanlarının birbiriyle uyumlu olması gerekir.\n\nOtomatik belge kontrolü, ekiplerin manuel kontrol yükünü azaltır ve eksikleri işlem başlamadan görünür hale getirir.', '', '2026-04-10 10:00:00', 1, '2026-04-12 15:16:21', NULL),
(3, 'İthalat maliyeti hesaplarken hangi kalemler unutuluyor?', 'ithalat-maliyeti-hesaplarken-hangi-kalemler-unutuluyor', 'GV ve KDV dışında ek mali yükümlülükler, damping ve lojistik masraflar toplam maliyeti değiştirebilir.', 'İthalat maliyeti hesabında yalnızca gümrük vergisi ve KDV’ye bakmak eksik sonuç verir. Menşe, ürün grubu, ek mali yükümlülük, damping ve gözetim uygulamaları kontrol edilmelidir.\n\nDoğru maliyet hesabı teklif aşamasında karlılığı korur.', '', '2026-04-08 10:00:00', 1, '2026-04-12 15:16:21', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `faqs`
--

CREATE TABLE `faqs` (
  `id` int(10) UNSIGNED NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `answer` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `sort_order`, `is_active`) VALUES
(1, 'Sonuçların doğruluğunu nasıl garanti ediyorsunuz?', 'Her sonuç tarife cetveli, BTB kararları ve ilgili mevzuat kaynaklarıyla eşleştirilir. Kritik sonuçlarda uzman müşavir kontrolü eklenebilir.', 1, 1),
(2, 'Yanlış GTİP sonucu halinde sorumluluk kime ait?', 'Platform karar destek sistemi olarak çalışır. Nihai beyan sorumluluğu kullanıcıdadır; ancak kaynaklı rapor ve uzman kontrolü riskinizi azaltmak için tasarlanmıştır.', 2, 1),
(3, 'Mevzuat değişikliklerini ne sıklıkla güncelliyorsunuz?', 'Kaynaklar düzenli olarak takip edilir; kritik tarife ve mevzuat değişiklikleri sisteme öncelikli olarak işlenir.', 3, 1),
(4, 'Mevcut sistemlerimizle entegre olabiliyor mu?', 'Kurumsal planda API ve özel entegrasyon seçenekleri sunulur. ERP ve gümrük yazılımı akışlarına bağlanabilir.', 4, 1),
(5, 'Ticari bilgilerim güvende mi, üçüncü taraflarla paylaşılıyor mu?', 'Verileriniz şifrelenmiş altyapıda saklanır ve model eğitimi için kullanılmaz. Erişimler rol bazlı olarak sınırlandırılır.', 5, 1),
(6, 'Gümrük müşaviri değilim, platformu kullanabilir miyim?', 'Evet. Başlangıç planı ithalat yapan ekipler ve öğrenmek isteyen kullanıcılar için sadeleştirilmiş akışlar sunar.', 6, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `landing_blocks`
--

CREATE TABLE `landing_blocks` (
  `id` int(10) UNSIGNED NOT NULL,
  `block_key` varchar(80) COLLATE utf8mb4_turkish_ci NOT NULL,
  `title` varchar(160) COLLATE utf8mb4_turkish_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `landing_blocks`
--

INSERT INTO `landing_blocks` (`id`, `block_key`, `title`, `payload`, `updated_at`) VALUES
(1, 'hero', 'Hero alanı', '{\"pill\": \"Gümrük profesyonelleri için yapay zeka platformu\", \"title_before\": \"Gümrük işlemlerinizi\", \"title_accent\": \"doğru, hızlı ve güvenli\", \"title_after\": \"yönetin\", \"description\": \"GTİP tespitinden beyanname hazırlamaya, mevzuat araştırmasından ceza risk analizine kadar tüm gümrük süreçlerini tek platformda yönetin. Resmi kaynak garantisi ile.\", \"primary_button\": \"Ücretsiz Başlayın\", \"secondary_button\": \"Demo İzleyin →\"}', NULL),
(2, 'trusted', 'Kullananlar bandı', '[\"ZORLU HOLDİNG\", \"ÜLKER\", \"ARÇELİK\", \"YILDIRIM GROUP\", \"EKOL LOJİSTİK\", \"ATA TAŞIMACILIK\"]', NULL),
(3, 'module_copy', 'Modül başlık metinleri', '{\"eyebrow\": \"PLATFORM MODÜLLERİ\", \"title\": \"Gümrük sürecinizin her adımı için araç\", \"description\": \"Her modül bağımsız çalışır; dilediğiniz modülden başlayın, ihtiyacınıza göre genişletin.\"}', NULL),
(4, 'process_copy', 'Nasıl çalışır başlık metinleri', '{\"eyebrow\": \"NASIL ÇALIŞIR\", \"title\": \"AI + uzman bilgisi = doğru sonuç\", \"description\": \"Pratik Gümrük, ham AI tahminini değil, resmi kaynaklarla doğrulanmış, uzman onaylı sonuçları sunar.\"}', NULL),
(5, 'process_steps', 'AI analiz adımları', '[{\"label\": \"ADIM 01 · GİRİŞ\", \"title\": \"Ürününüzü veya sorunuzu girin\", \"text\": \"Ürün adı, görsel, barkod, link veya doğrudan sorunuzu yazın.\", \"panel_html\": \"<div class=\\\"assistant-card\\\"><strong>GTİP Asistanı</strong><em>Aktif</em><p>Ürün adını, görselini, barkodunu veya ürün sayfası linkini girin. Yabancı dil desteği ile tedarikçi faturasındaki tanımı da doğrudan yapıştırabilirsiniz.</p></div><div class=\\\"input-card\\\"><strong>DESTEKLENEN GİRİŞLER</strong><p><span>Türkçe / İngilizce</span><span>Görsel / Foto</span><span>Barkod / GTİN</span><span>URL / Link</span><span>GTİP No</span><span>Teknik Tanım</span><span>Çince / Almanca</span></p></div>\"}, {\"label\": \"ADIM 02 · AI ANALİZ\", \"title\": \"AI analiz ve sınıflandırma\", \"text\": \"Motor; tarife cetveli, BTB kararı ve güncel mevzuatla çapraz doğrulama yapar.\", \"panel_html\": \"<div class=\\\"input-card process-note\\\"><strong>MOTOR ÇALIŞIYOR...</strong><p>97.000+ GTİP pozisyonu taranıyor → 370.000+ BTB emsal kararıyla çapraz doğrulama → Fasıl 84 öncelikli → 3 olası pozisyon tespit edildi → güven skoru hesaplanıyor...</p></div><div class=\\\"assistant-card result-card\\\"><strong>Tespit edilen pozisyonlar</strong><p><b>8479.89.97.00.19</b><span>%87 güven</span></p><p><b>8415.82.00.00.11</b><span>%52 güven</span></p><p><b>8414.51.00.00.11</b><span>%18 güven</span></p></div>\"}, {\"label\": \"ADIM 03 · SONUÇ & RİSK\", \"title\": \"Kaynaklı sonuç ve risk analizi\", \"text\": \"Her sonuç tıklanabilir resmi kaynakla desteklenir. Yanlış beyan ceza riski anlık hesaplanır.\", \"panel_html\": \"<div class=\\\"assistant-card code-card\\\"><strong>Önerilen GTİP</strong><em>BTB Onaylı</em><p><b>8479.89.97.00.19</b></p><p><span>GV: %3.7</span><span>KDV: %20</span><span>İGV: %20 (Çin)</span></p></div><div class=\\\"input-card risk-card\\\"><strong>RİSK ANALİZİ</strong><p>Bu ürünü 8414.51 koduyla beyan ederseniz: vergi farkı %5\'i aşacak → <b>eksik verginin 3 katı idari ceza</b> riski. Tahmini risk: ₺340.000</p></div>\"}, {\"label\": \"ADIM 04 · UZMAN ONAYI\", \"title\": \"Uzman onayı ve raporlama\", \"text\": \"İsterseniz sertifikalı gümrük müşaviri sonucu doğrular.\", \"panel_html\": \"<div class=\\\"chat-card\\\"><p><b>PG</b><span>GTİP tespiti tamamlandı. 8479.89.97.00.19 kodunu onaylıyorum. Evaporatif soğutucu sınıflandırması için 3 BTB emsal kararı görmek ister misiniz?</span></p><p class=\\\"user\\\"><span>Evet lütfen, ayrıca beyanname taslağını da hazırlayabilir misiniz?</span><b>S</b></p><p><b>PG</b><span>BTB kararları eklendi. Beyanname taslağı hazır, faturadaki değerleri otomatik doldurdum.</span></p></div>\"}]', NULL),
(6, 'comparison_features', 'Rakip karşılaştırma satırları', '[{\"text\": \"BTB emsal kararı entegrasyonu\", \"pratik\": \"ok\", \"others\": \"no\"}, {\"text\": \"Çakışan GTİP sınıflandırma sorusu\", \"pratik\": \"ok\", \"others\": \"no\"}, {\"text\": \"Ceza risk hesaplama aracı\", \"pratik\": \"ok\", \"others\": \"no\"}, {\"text\": \"Belge → beyanname otomasyonu\", \"pratik\": \"ok\", \"others\": \"warn\"}, {\"text\": \"Türkiye\'ye özel 12 haneli GTİP\", \"pratik\": \"ok\", \"others\": \"no\"}, {\"text\": \"Uzman müşavir gözden geçirme\", \"pratik\": \"ok\", \"others\": \"no\"}, {\"text\": \"TAREKS / BİLGE uyumluluk kontrolü\", \"pratik\": \"ok\", \"others\": \"no\"}]', NULL),
(7, 'comparison_metrics', 'Rakip karşılaştırma metrikleri', '[{\"value\": \"₺1.2\", \"suffix\": \"M+\", \"text\": \"Bu ay kullanıcıların önlediği toplam ceza riski. Doğru GTİP = doğru vergi = cezasız gümrük.\"}, {\"value\": \"370\", \"suffix\": \"K+\", \"text\": \"Bakanlık onaylı Bağlayıcı Tarife Bilgisi kararı. Her sonuç tıklanabilir resmi kaynaklarla desteklenir.\"}, {\"value\": \"%98\", \"suffix\": \".2\", \"text\": \"Gümrük müşaviri onay oranı. AI önerisi + uzman doğrulaması birlikte çalışır.\"}]', NULL),
(8, 'security_items', 'Güvenlik maddeleri', '[{\"icon\": \"shield\", \"title\": \"KVKK & GDPR Uyumlu\", \"text\": \"Kişisel ve ticari verileriniz Türk hukuku ve AB veri koruma mevzuatı kapsamında işlenir.\"}, {\"icon\": \"lock\", \"title\": \"Uçtan Uca Şifreleme\", \"text\": \"Tüm veriler AES-256 ile şifrelenmiş sunucularda saklanır. Transfer sırasında TLS 1.3 kullanılır.\"}, {\"icon\": \"clock\", \"title\": \"Denetim & İzlenebilirlik\", \"text\": \"Tüm kritik işlemler zaman damgalı, değiştirilemez kayıtlarla izlenir.\"}, {\"icon\": \"hexagon\", \"title\": \"Model Eğitimi Yok\", \"text\": \"Yüklediğiniz belgeler veya sorularınız AI modellerini eğitmek için kullanılmaz.\"}, {\"icon\": \"activity\", \"title\": \"%99.9 Uptime SLA\", \"text\": \"Kritik gümrük süreçleriniz kesintisiz çalışır. Bakım pencereleri önceden bildirilir.\"}, {\"icon\": \"users\", \"title\": \"Rol Bazlı Erişim\", \"text\": \"Ekip üyelerinize modül ve veri bazında farklı erişim seviyeleri tanımlayın.\"}]', NULL),
(9, 'pricing_plans', 'Fiyatlandırma planları', '[{\"badge\": \"BAŞLANGIÇ\", \"name\": \"Ücretsiz\", \"price_suffix\": \"\", \"description\": \"Küçük ithalatçılar ve öğrenmek isteyenler için\", \"features\": [\"Aylık 20 GTİP sorgusu\", \"Temel vergi hesaplama\", \"Mevzuat arama sınırlı\", \"Ceza risk hesabı\"], \"button\": \"Ücretsiz Başlayın\", \"featured\": false, \"recommended\": \"\"}, {\"badge\": \"PROFESYONEL\", \"name\": \"₺2.900\", \"price_suffix\": \"/ay\", \"description\": \"Gümrük müşavirleri ve ithalat-ihracat departmanları için\", \"features\": [\"Sınırsız GTİP sorgulama\", \"Belge kontrol 50/ay\", \"Derin mevzuat araştırması\", \"Beyanname taslağı 20/ay\", \"PDF raporlama\"], \"button\": \"14 Gün Ücretsiz Dene\", \"featured\": true, \"recommended\": \"Önerilen\"}, {\"badge\": \"KURUMSAL\", \"name\": \"Özel\", \"price_suffix\": \"\", \"description\": \"Büyük hacimli ithalatçılar, lojistik şirketleri ve müşavirlik büroları için\", \"features\": [\"Sınırsız her modül\", \"API erişimi + entegrasyon\", \"Müşavir öncelikli erişim\", \"Özel eğitim & onboarding\", \"7/24 destek\"], \"button\": \"Demo Talep Edin\", \"featured\": false, \"recommended\": \"\"}]', NULL),
(10, 'final_cta', 'Final çağrı alanı', '{\"title\": \"Gümrük süreçlerinizi bugün dönüştürün\", \"description\": \"14 gün ücretsiz, kredi kartı gerektirmez. İlk GTİP sorgunuzu dakikalar içinde çözün.\", \"primary_button\": \"Ücretsiz Başlayın\", \"secondary_button\": \"Demo Talep Edin →\"}', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `modules`
--

CREATE TABLE `modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(190) COLLATE utf8mb4_turkish_ci NOT NULL,
  `eyebrow` varchar(80) COLLATE utf8mb4_turkish_ci NOT NULL,
  `icon` varchar(12) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT '□',
  `accent` varchar(20) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT '#12c8bf',
  `summary` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `detail_content` mediumtext COLLATE utf8mb4_turkish_ci NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `modules`
--

INSERT INTO `modules` (`id`, `title`, `slug`, `eyebrow`, `icon`, `accent`, `summary`, `features`, `detail_content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'GTİP Tespit', 'gtip-tespit', 'MODÜL 01', '□', '#12c8bf', '7 farklı giriş yöntemiyle ürününüzü doğru GTİP koduna kavuşturun. AI + BTB emsal kararları ile doğrulama.', '[\"Ürün adı, görsel, barkod, link\", \"370K+ BTB emsal karar\", \"Çakışan kod risk analizi\", \"Ceza hesap aracı\"]', 'GTİP Tespit modülü ürün adı, teknik açıklama, barkod, ürün linki veya görsel ile çalışır. Sistem tarife cetveli, BTB kararları ve güncel mevzuat üzerinden aday kodları çıkarır; her sonuç için güven skoru, kaynak bağlantısı ve risk notu üretir.', 1, 1, '2026-04-12 15:16:21', NULL),
(2, 'Vergi & Maliyet Hesabı', 'vergi-maliyet-hesabi', 'MODÜL 02', '$', '#e5a019', 'GTİP, menşe ve değer bilgisiyle tam ithalat maliyeti hesaplayın. GV, KDV, ÖTV, İGV, damping — hepsi.', '[\"CIF tabanlı tam hesaplama\", \"STA / GTB anlaşma avantajı\", \"Dampinge karşı önlemler\", \"PDF rapor çıktısı\"]', 'Vergi & Maliyet Hesabı modülü GTİP, menşe ülke, CIF değer ve ek masraf alanları ile toplam ithalat maliyetini hesaplar. Gümrük vergisi, KDV, ÖTV, ilave gümrük vergisi ve dampinge karşı önlemler tek ekranda görünür.', 2, 1, '2026-04-12 15:16:21', NULL),
(3, 'Belge Kontrol', 'belge-kontrol', 'MODÜL 03', '▤', '#dd3f62', 'Fatura, konşimento, menşe belgesi ve diğer gümrük evraklarını yükleyin; AI tutarsızlıkları ve eksiklikleri tespit eder.', '[\"Otomatik çapraz kontrol\", \"OCR ile PDF/fatura okuma\", \"Eksik belge uyarısı\", \"TAREKS uygunluk kontrolü\"]', 'Belge Kontrol modülü yüklediğiniz ticari belgeleri OCR ile okur ve alanlar arasında tutarlılık denetimi yapar. Eksik menşe bilgisi, yanlış ürün tanımı, tarih uyuşmazlığı ve GTİP-belge uyumsuzlukları erken aşamada yakalanır.', 3, 1, '2026-04-12 15:16:21', NULL),
(4, 'Derin Mevzuat Araştırması', 'derin-mevzuat-arastirmasi', 'MODÜL 04', '⌕', '#05ad71', 'Gümrük Kanunu, tebliğler, BTB kararları ve AB mevzuatında semantik arama. DeJure.ai hukuki araştırma modeline eş değer güç.', '[\"Semantik mevzuat arama\", \"Kaynaklı cevaplar\", \"Karar ve tebliğ takibi\", \"Özet ve aksiyon listesi\"]', 'Derin Mevzuat Araştırması modülü sorularınızı doğal dille alır ve ilgili kanun, tebliğ, genelge ve kararları kaynaklarıyla birlikte sunar. Yanıtlar, doğrudan operasyon kararına dönüşebilecek özetler ve kontrol listeleri içerir.', 4, 1, '2026-04-12 15:16:21', NULL),
(5, 'Beyanname Asistanı', 'beyanname-asistani', 'MODÜL 05', '✎', '#743de8', 'Yüklediğiniz fatura ve belgelerden beyanname taslağı otomatik oluşturun. BİLGE uyumlu format.', '[\"Belge → beyanname otomasyonu\", \"Kalem bazlı kontrol\", \"BİLGE uyumlu taslak\", \"PDF ve veri çıktısı\"]', 'Beyanname Asistanı ticari fatura, çeki listesi ve konşimento gibi belgelerden beyanname taslağı üretir. Kalem bazlı alanları kontrol eder, eksikleri işaretler ve müşavir onayına hazır hale getirir.', 5, 1, '2026-04-12 15:16:21', NULL),
(6, 'Risk & Uyum Analizi', 'risk-uyum-analizi', 'MODÜL 06', '◇', '#2f6df6', 'Gümrük denetim geçmişinizi analiz edin, olası ceza risklerini önceden tespit edin, YYS uyumluluğunuzu takip edin.', '[\"Ceza riski simülasyonu\", \"YYS kontrol listesi\", \"Denetim izi\", \"Rol bazlı raporlar\"]', 'Risk & Uyum Analizi modülü geçmiş işlemlerden risk örüntülerini çıkarır. Yanlış GTİP, eksik belge, düşük kıymet, menşe ve TAREKS uyumsuzluklarını izler; ekipler için rol bazlı raporlar üretir.', 6, 1, '2026-04-12 15:16:21', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `seo_meta`
--

CREATE TABLE `seo_meta` (
  `id` int(10) UNSIGNED NOT NULL,
  `page` varchar(80) COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT '',
  `meta_title` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_turkish_ci NOT NULL,
  `meta_keywords` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `og_image` varchar(500) COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `seo_meta`
--

INSERT INTO `seo_meta` (`id`, `page`, `slug`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`) VALUES
(1, 'home', '', 'Pratik Gümrük | GTİP, Vergi Hesabı ve Beyanname Asistanı', 'Gümrük profesyonelleri için yapay zeka destekli GTİP tespiti, belge kontrolü, mevzuat araştırması ve beyanname hazırlama platformu.', 'GTİP, gümrük, beyanname, ithalat vergisi, mevzuat', ''),
(2, 'blog', '', 'Pratik Gümrük Blog | GTİP ve Gümrük Rehberleri', 'GTİP tespiti, ithalat maliyeti, belge kontrolü ve gümrük mevzuatı hakkında pratik rehberler.', 'gümrük blog, GTİP rehberi, ithalat', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(120) COLLATE utf8mb4_turkish_ci NOT NULL,
  `value` text COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `site_settings`
--

INSERT INTO `site_settings` (`id`, `key`, `value`) VALUES
(1, 'default_meta_title', 'Pratik Gümrük | Yapay Zeka Destekli Gümrük Platformu'),
(2, 'default_meta_description', 'GTİP tespiti, vergi hesabı, belge kontrolü, mevzuat araştırması ve beyanname hazırlığı için hızlı ve güvenli gümrük platformu.'),
(3, 'default_meta_keywords', 'gümrük, GTİP, vergi hesabı, beyanname, ithalat, ihracat');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_admin_username` (`username`);

--
-- Tablo için indeksler `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_blog_slug` (`slug`),
  ADD KEY `idx_blog_published` (`is_published`,`published_at`);

--
-- Tablo için indeksler `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_faq_question` (`question`),
  ADD KEY `idx_faqs_active_sort` (`is_active`,`sort_order`);

--
-- Tablo için indeksler `landing_blocks`
--
ALTER TABLE `landing_blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_landing_block_key` (`block_key`);

--
-- Tablo için indeksler `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_modules_slug` (`slug`),
  ADD KEY `idx_modules_active_sort` (`is_active`,`sort_order`);

--
-- Tablo için indeksler `seo_meta`
--
ALTER TABLE `seo_meta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_seo_page_slug` (`page`,`slug`);

--
-- Tablo için indeksler `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_site_settings_key` (`key`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `landing_blocks`
--
ALTER TABLE `landing_blocks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `seo_meta`
--
ALTER TABLE `seo_meta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
