<?php
/**
 * Migration: Create sitemap_log table to track daily sitemap regeneration.
 * Run this file once via CLI: php database/migrate-seo.php
 */
declare(strict_types=1);

$config = require __DIR__ . '/../config/app.php';
$db = $config['db'];

if (!empty($db['unix_socket'])) {
    $dsn = sprintf('mysql:unix_socket=%s;dbname=%s;charset=%s', $db['unix_socket'], $db['database'], $db['charset']);
} else {
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $db['host'], $db['port'], $db['database'], $db['charset']);
}
$pdo = new PDO($dsn, $db['username'], $db['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_turkish_ci',
]);

$sql = "
CREATE TABLE IF NOT EXISTS `sitemap_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `generated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sitemap_date` (`generated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
";

$pdo->exec($sql);
echo "✅ sitemap_log tablosu oluşturuldu / zaten mevcut.\n";

// Also add the sitemap_log table to the main schema.sql
echo "Migration tamamlandı.\n";
