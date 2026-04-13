<?php
require __DIR__ . '/app/bootstrap.php';
$pdo = Database::pdo();
header('Content-Type: text/plain; charset=utf-8');
echo "=== Blog System v2: Profile Photo Migration ===\n\n";

try {
    $pdo->exec("ALTER TABLE admin_users ADD COLUMN profile_photo VARCHAR(255) DEFAULT NULL AFTER auto_approve");
    echo "[+] admin_users.profile_photo added\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "[=] admin_users.profile_photo already exists\n";
    } else {
        throw $e;
    }
}
echo "\n=== Migration complete! ===\n";
