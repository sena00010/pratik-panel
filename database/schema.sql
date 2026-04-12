CREATE DATABASE IF NOT EXISTS `pratik_gumruk`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_turkish_ci;

USE `pratik_gumruk`;

SET NAMES utf8mb4 COLLATE utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(120) NOT NULL,
  `value` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_site_settings_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(80) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS `modules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(190) NOT NULL,
  `eyebrow` VARCHAR(80) NOT NULL,
  `icon` VARCHAR(12) NOT NULL DEFAULT '□',
  `accent` VARCHAR(20) NOT NULL DEFAULT '#12c8bf',
  `summary` TEXT NOT NULL,
  `features` JSON NULL,
  `detail_content` MEDIUMTEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_modules_slug` (`slug`),
  KEY `idx_modules_active_sort` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_faq_question` (`question`),
  KEY `idx_faqs_active_sort` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(220) NOT NULL,
  `slug` VARCHAR(220) NOT NULL,
  `summary` TEXT NOT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `cover_image` VARCHAR(500) NULL,
  `published_at` DATETIME NOT NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_blog_slug` (`slug`),
  KEY `idx_blog_published` (`is_published`, `published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS `seo_meta` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page` VARCHAR(80) NOT NULL,
  `slug` VARCHAR(220) NOT NULL DEFAULT '',
  `meta_title` VARCHAR(255) NOT NULL,
  `meta_description` VARCHAR(320) NOT NULL,
  `meta_keywords` VARCHAR(320) NULL,
  `og_image` VARCHAR(500) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_seo_page_slug` (`page`, `slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

INSERT INTO `site_settings` (`key`, `value`) VALUES
('default_meta_title', 'Pratik Gümrük | Yapay Zeka Destekli Gümrük Platformu'),
('default_meta_description', 'GTİP tespiti, vergi hesabı, belge kontrolü, mevzuat araştırması ve beyanname hazırlığı için hızlı ve güvenli gümrük platformu.'),
('default_meta_keywords', 'gümrük, GTİP, vergi hesabı, beyanname, ithalat, ihracat')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

INSERT INTO `admin_users` (`username`, `password_hash`) VALUES
('admin', '$2y$10$zDWXnPtJrr/3QQ8I/Y4EEOPnRsbgxAfX8Sdn86c42Sl.bTTfpfRE6')
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

INSERT INTO `modules` (`title`, `slug`, `eyebrow`, `icon`, `accent`, `summary`, `features`, `detail_content`, `sort_order`, `is_active`) VALUES
('GTİP Tespit', 'gtip-tespit', 'MODÜL 01', '□', '#12c8bf', '7 farklı giriş yöntemiyle ürününüzü doğru GTİP koduna kavuşturun. AI + BTB emsal kararları ile doğrulama.', JSON_ARRAY('Ürün adı, görsel, barkod, link', '370K+ BTB emsal karar', 'Çakışan kod risk analizi', 'Ceza hesap aracı'), 'GTİP Tespit modülü ürün adı, teknik açıklama, barkod, ürün linki veya görsel ile çalışır. Sistem tarife cetveli, BTB kararları ve güncel mevzuat üzerinden aday kodları çıkarır; her sonuç için güven skoru, kaynak bağlantısı ve risk notu üretir.', 1, 1),
('Vergi & Maliyet Hesabı', 'vergi-maliyet-hesabi', 'MODÜL 02', '$', '#e5a019', 'GTİP, menşe ve değer bilgisiyle tam ithalat maliyeti hesaplayın. GV, KDV, ÖTV, İGV, damping — hepsi.', JSON_ARRAY('CIF tabanlı tam hesaplama', 'STA / GTB anlaşma avantajı', 'Dampinge karşı önlemler', 'PDF rapor çıktısı'), 'Vergi & Maliyet Hesabı modülü GTİP, menşe ülke, CIF değer ve ek masraf alanları ile toplam ithalat maliyetini hesaplar. Gümrük vergisi, KDV, ÖTV, ilave gümrük vergisi ve dampinge karşı önlemler tek ekranda görünür.', 2, 1),
('Belge Kontrol', 'belge-kontrol', 'MODÜL 03', '▤', '#dd3f62', 'Fatura, konşimento, menşe belgesi ve diğer gümrük evraklarını yükleyin; AI tutarsızlıkları ve eksiklikleri tespit eder.', JSON_ARRAY('Otomatik çapraz kontrol', 'OCR ile PDF/fatura okuma', 'Eksik belge uyarısı', 'TAREKS uygunluk kontrolü'), 'Belge Kontrol modülü yüklediğiniz ticari belgeleri OCR ile okur ve alanlar arasında tutarlılık denetimi yapar. Eksik menşe bilgisi, yanlış ürün tanımı, tarih uyuşmazlığı ve GTİP-belge uyumsuzlukları erken aşamada yakalanır.', 3, 1),
('Derin Mevzuat Araştırması', 'derin-mevzuat-arastirmasi', 'MODÜL 04', '⌕', '#05ad71', 'Gümrük Kanunu, tebliğler, BTB kararları ve AB mevzuatında semantik arama. DeJure.ai hukuki araştırma modeline eş değer güç.', JSON_ARRAY('Semantik mevzuat arama', 'Kaynaklı cevaplar', 'Karar ve tebliğ takibi', 'Özet ve aksiyon listesi'), 'Derin Mevzuat Araştırması modülü sorularınızı doğal dille alır ve ilgili kanun, tebliğ, genelge ve kararları kaynaklarıyla birlikte sunar. Yanıtlar, doğrudan operasyon kararına dönüşebilecek özetler ve kontrol listeleri içerir.', 4, 1),
('Beyanname Asistanı', 'beyanname-asistani', 'MODÜL 05', '✎', '#743de8', 'Yüklediğiniz fatura ve belgelerden beyanname taslağı otomatik oluşturun. BİLGE uyumlu format.', JSON_ARRAY('Belge → beyanname otomasyonu', 'Kalem bazlı kontrol', 'BİLGE uyumlu taslak', 'PDF ve veri çıktısı'), 'Beyanname Asistanı ticari fatura, çeki listesi ve konşimento gibi belgelerden beyanname taslağı üretir. Kalem bazlı alanları kontrol eder, eksikleri işaretler ve müşavir onayına hazır hale getirir.', 5, 1),
('Risk & Uyum Analizi', 'risk-uyum-analizi', 'MODÜL 06', '◇', '#2f6df6', 'Gümrük denetim geçmişinizi analiz edin, olası ceza risklerini önceden tespit edin, YYS uyumluluğunuzu takip edin.', JSON_ARRAY('Ceza riski simülasyonu', 'YYS kontrol listesi', 'Denetim izi', 'Rol bazlı raporlar'), 'Risk & Uyum Analizi modülü geçmiş işlemlerden risk örüntülerini çıkarır. Yanlış GTİP, eksik belge, düşük kıymet, menşe ve TAREKS uyumsuzluklarını izler; ekipler için rol bazlı raporlar üretir.', 6, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title), summary = VALUES(summary), features = VALUES(features), detail_content = VALUES(detail_content), sort_order = VALUES(sort_order), is_active = VALUES(is_active);

INSERT INTO `faqs` (`question`, `answer`, `sort_order`, `is_active`) VALUES
('Sonuçların doğruluğunu nasıl garanti ediyorsunuz?', 'Her sonuç tarife cetveli, BTB kararları ve ilgili mevzuat kaynaklarıyla eşleştirilir. Kritik sonuçlarda uzman müşavir kontrolü eklenebilir.', 1, 1),
('Yanlış GTİP sonucu halinde sorumluluk kime ait?', 'Platform karar destek sistemi olarak çalışır. Nihai beyan sorumluluğu kullanıcıdadır; ancak kaynaklı rapor ve uzman kontrolü riskinizi azaltmak için tasarlanmıştır.', 2, 1),
('Mevzuat değişikliklerini ne sıklıkla güncelliyorsunuz?', 'Kaynaklar düzenli olarak takip edilir; kritik tarife ve mevzuat değişiklikleri sisteme öncelikli olarak işlenir.', 3, 1),
('Mevcut sistemlerimizle entegre olabiliyor mu?', 'Kurumsal planda API ve özel entegrasyon seçenekleri sunulur. ERP ve gümrük yazılımı akışlarına bağlanabilir.', 4, 1),
('Ticari bilgilerim güvende mi, üçüncü taraflarla paylaşılıyor mu?', 'Verileriniz şifrelenmiş altyapıda saklanır ve model eğitimi için kullanılmaz. Erişimler rol bazlı olarak sınırlandırılır.', 5, 1),
('Gümrük müşaviri değilim, platformu kullanabilir miyim?', 'Evet. Başlangıç planı ithalat yapan ekipler ve öğrenmek isteyen kullanıcılar için sadeleştirilmiş akışlar sunar.', 6, 1)
ON DUPLICATE KEY UPDATE answer = VALUES(answer), sort_order = VALUES(sort_order), is_active = VALUES(is_active);

INSERT INTO `blog_posts` (`title`, `slug`, `summary`, `content`, `cover_image`, `published_at`, `is_published`) VALUES
('GTİP tespitinde en sık yapılan 5 hata', 'gtip-tespitinde-en-sik-yapilan-5-hata', 'Yanlış ürün tanımı, eksik teknik bilgi ve emsal karar kontrolü yapılmaması ithalat maliyetini doğrudan etkiler.', 'GTİP tespitinde hata çoğu zaman ürün adının tek başına yeterli sanılmasından kaynaklanır. Teknik özellikler, kullanım amacı, malzeme bilgisi ve emsal BTB kararları birlikte değerlendirilmelidir.\n\nPratik Gümrük bu alanları tek akışta toplar ve sonuçları kaynakla birlikte sunar.', '', '2026-04-12 10:00:00', 1),
('Belge kontrolü neden beyanname öncesinde yapılmalı?', 'belge-kontrolu-neden-beyanname-oncesinde-yapilmali', 'Fatura, konşimento ve menşe belgesi arasındaki küçük uyumsuzluklar işlem süresini uzatabilir.', 'Beyanname hazırlanmadan önce belge kontrolü yapmak operasyon süresini kısaltır. Tutar, miktar, menşe ve ürün tanımı alanlarının birbiriyle uyumlu olması gerekir.\n\nOtomatik belge kontrolü, ekiplerin manuel kontrol yükünü azaltır ve eksikleri işlem başlamadan görünür hale getirir.', '', '2026-04-10 10:00:00', 1),
('İthalat maliyeti hesaplarken hangi kalemler unutuluyor?', 'ithalat-maliyeti-hesaplarken-hangi-kalemler-unutuluyor', 'GV ve KDV dışında ek mali yükümlülükler, damping ve lojistik masraflar toplam maliyeti değiştirebilir.', 'İthalat maliyeti hesabında yalnızca gümrük vergisi ve KDV’ye bakmak eksik sonuç verir. Menşe, ürün grubu, ek mali yükümlülük, damping ve gözetim uygulamaları kontrol edilmelidir.\n\nDoğru maliyet hesabı teklif aşamasında karlılığı korur.', '', '2026-04-08 10:00:00', 1)
ON DUPLICATE KEY UPDATE summary = VALUES(summary), content = VALUES(content), published_at = VALUES(published_at), is_published = VALUES(is_published);

INSERT INTO `seo_meta` (`page`, `slug`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`) VALUES
('home', '', 'Pratik Gümrük | GTİP, Vergi Hesabı ve Beyanname Asistanı', 'Gümrük profesyonelleri için yapay zeka destekli GTİP tespiti, belge kontrolü, mevzuat araştırması ve beyanname hazırlama platformu.', 'GTİP, gümrük, beyanname, ithalat vergisi, mevzuat', ''),
('blog', '', 'Pratik Gümrük Blog | GTİP ve Gümrük Rehberleri', 'GTİP tespiti, ithalat maliyeti, belge kontrolü ve gümrük mevzuatı hakkında pratik rehberler.', 'gümrük blog, GTİP rehberi, ithalat', '')
ON DUPLICATE KEY UPDATE meta_title = VALUES(meta_title), meta_description = VALUES(meta_description), meta_keywords = VALUES(meta_keywords);
