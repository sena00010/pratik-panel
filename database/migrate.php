<?php
declare(strict_types=1);

$config = require __DIR__ . '/../config/app.php';
$db = $config['db'];
$sql = file_get_contents(__DIR__ . '/schema.sql');

if ($sql === false) {
    fwrite(STDERR, "schema.sql okunamadı.\n");
    exit(1);
}

if (!empty($db['unix_socket'])) {
    $dsn = sprintf('mysql:unix_socket=%s;charset=%s', $db['unix_socket'], $db['charset']);
} else {
    $dsn = sprintf('mysql:host=%s;port=%d;charset=%s', $db['host'], $db['port'], $db['charset']);
}
$pdo = new PDO($dsn, $db['username'], $db['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_turkish_ci',
]);

$pdo->exec($sql);
echo "Veritabanı ve tablolar hazır: {$db['database']}\n";
