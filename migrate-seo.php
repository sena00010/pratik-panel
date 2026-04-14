<?php
/**
 * Remote SEO migration — run on the live server to create sitemap_log table.
 * Access: https://pratikgumruk.com/migrate-seo.php?key=pg_seo_2026
 */
declare(strict_types=1);

if (($_GET['key'] ?? '') !== 'pg_seo_2026') {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

require __DIR__ . '/app/bootstrap.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `sitemap_log` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `generated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_sitemap_date` (`generated_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
    ";
    Database::pdo()->exec($sql);
    echo "✅ sitemap_log tablosu oluşturuldu.\n";
} catch (Throwable $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
