<?php
/**
 * Migration: Add integrations, audience_cards, blogger role, author_id
 * Run locally: php migrate-blog-v2.php
 */
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$pdo = Database::pdo();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES utf8mb4");

echo "=== Blog v2 Migration ===\n\n";

// 1. Add role column to admin_users
try {
    $cols = $pdo->query("SHOW COLUMNS FROM admin_users LIKE 'role'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE admin_users ADD COLUMN role ENUM('admin','blogger') NOT NULL DEFAULT 'admin' AFTER password_hash");
        echo "[+] admin_users.role column added\n";
    } else {
        echo "[=] admin_users.role already exists\n";
    }
} catch (Exception $e) {
    echo "[!] admin_users.role: {$e->getMessage()}\n";
}

// 2. Add display_name to admin_users for blogger names
try {
    $cols = $pdo->query("SHOW COLUMNS FROM admin_users LIKE 'display_name'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE admin_users ADD COLUMN display_name VARCHAR(120) DEFAULT NULL AFTER role");
        echo "[+] admin_users.display_name column added\n";
    } else {
        echo "[=] admin_users.display_name already exists\n";
    }
} catch (Exception $e) {
    echo "[!] admin_users.display_name: {$e->getMessage()}\n";
}

// 3. Add author_id to blog_posts
try {
    $cols = $pdo->query("SHOW COLUMNS FROM blog_posts LIKE 'author_id'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE blog_posts ADD COLUMN author_id INT UNSIGNED DEFAULT NULL AFTER is_published");
        echo "[+] blog_posts.author_id column added\n";
    } else {
        echo "[=] blog_posts.author_id already exists\n";
    }
} catch (Exception $e) {
    echo "[!] blog_posts.author_id: {$e->getMessage()}\n";
}

// 4. Create integrations table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS integrations (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(120) NOT NULL,
        description VARCHAR(255) NOT NULL DEFAULT '',
        icon_svg TEXT DEFAULT NULL,
        accent VARCHAR(20) NOT NULL DEFAULT '#12c8bf',
        status ENUM('canli','yakinda','kurumsal') NOT NULL DEFAULT 'canli',
        sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci");
    echo "[+] integrations table ready\n";
} catch (Exception $e) {
    echo "[!] integrations: {$e->getMessage()}\n";
}

// 5. Create audience_cards table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS audience_cards (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(120) NOT NULL,
        description VARCHAR(255) NOT NULL DEFAULT '',
        icon_svg TEXT DEFAULT NULL,
        accent VARCHAR(20) NOT NULL DEFAULT '#12c8bf',
        features TEXT DEFAULT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci");
    echo "[+] audience_cards table ready\n";
} catch (Exception $e) {
    echo "[!] audience_cards: {$e->getMessage()}\n";
}

// 6. Seed integrations data
$intCount = (int) $pdo->query("SELECT COUNT(*) FROM integrations")->fetchColumn();
if ($intCount === 0) {
    $integrations = [
        ['BİLGE Sistemi', 'Beyanname uyumlu format çıktısı', '#2f6df6', 'canli', 1],
        ['TAREKS', 'ÜGD uygunluk & kapsam kontrolü', '#05ad71', 'canli', 2],
        ['BTB / ticaret.gov.tr', '370K+ Bağlayıcı Tarife Bilgisi', '#12c8bf', 'canli', 3],
        ['BeyanameAPI', 'Hat, durum ve muayene takibi', '#743de8', 'canli', 4],
        ['Banka Web Servisleri', 'Hesap hareketleri & gümrük ödeme', '#e5a019', 'yakinda', 5],
        ['NCTS', 'Ortak transit beyan & teminat takibi', '#12c8bf', 'yakinda', 6],
        ['YKTS', 'Yükümlü kayıt ve takip sistemi', '#dd3f62', 'yakinda', 7],
        ['REST API', 'ERP, lojistik yazılımı entegrasyonu', '#2f6df6', 'kurumsal', 8],
    ];
    $stmt = $pdo->prepare("INSERT INTO integrations (title, description, accent, status, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($integrations as $row) {
        $stmt->execute($row);
    }
    echo "[+] 8 integrations seeded\n";
} else {
    echo "[=] integrations already has data ($intCount rows)\n";
}

// 7. Seed audience_cards data
$audCount = (int) $pdo->query("SELECT COUNT(*) FROM audience_cards")->fetchColumn();
if ($audCount === 0) {
    $audiences = [
        [
            'Gümrük Müşavirleri',
            'Beyanname yazımı, dosya takibi ve müşteri raporlamasını otomatize edin.',
            '#12c8bf',
            "Beyanname takip & otomasyon\nCeza & risk takibi\nMüşteri portal erişimi"
        ],
        [
            'İthalatçı / İhracatçı',
            'Kendi ürünlerinizin GTİP ve vergi yükünü öğrenin, ceza riskini sıfırlayın.',
            '#e5a019',
            "GTİP doğrulama\nİthalat maliyet hesabı\nMenşe & İGV analizi"
        ],
        [
            'Lojistik Firmaları',
            'Müşteri dosyalarını toplu yönetin, antrepo ve transit takibi yapın.',
            '#05ad71',
            "Toplu beyanname yazımı\nAntrepo stok takibi\nNCTS transit beyan"
        ],
        [
            'E-Ticaret & KOBİ',
            'Yurtdışından ürün alırken ya da satarken gümrük süreçlerinde yanınızda.',
            '#2f6df6',
            "ETGB rehberi\nVergi hesaplama\nGümrük chat asistanı"
        ],
    ];
    $stmt = $pdo->prepare("INSERT INTO audience_cards (title, description, accent, features, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($audiences as $i => $row) {
        $stmt->execute([$row[0], $row[1], $row[2], $row[3], $i + 1]);
    }
    echo "[+] 4 audience cards seeded\n";
} else {
    echo "[=] audience_cards already has data ($audCount rows)\n";
}

// 8. Add integrations_copy and audience_copy to landing_blocks
$existingKeys = $pdo->query("SELECT block_key FROM landing_blocks")->fetchAll(PDO::FETCH_COLUMN);

if (!in_array('integrations_copy', $existingKeys)) {
    $payload = json_encode([
        'eyebrow' => 'ENTEGRASYONLAR',
        'title' => "Türkiye'nin gümrük altyapısıyla doğrudan bağlı",
        'description' => '8 resmi sistem entegrasyonuyla verileri manuel girmek zorunda kalmayın.',
    ], JSON_UNESCAPED_UNICODE);
    $pdo->prepare("INSERT INTO landing_blocks (block_key, title, payload) VALUES (?, ?, ?)")
        ->execute(['integrations_copy', 'Entegrasyonlar başlık metinleri', $payload]);
    echo "[+] integrations_copy landing block added\n";
} else {
    echo "[=] integrations_copy already exists\n";
}

if (!in_array('audience_copy', $existingKeys)) {
    $payload = json_encode([
        'eyebrow' => 'KİMLER KULLANIR',
        'title' => 'Her gümrük profesyoneli için tasarlandı',
    ], JSON_UNESCAPED_UNICODE);
    $pdo->prepare("INSERT INTO landing_blocks (block_key, title, payload) VALUES (?, ?, ?)")
        ->execute(['audience_copy', 'Kimler Kullanır başlık metinleri', $payload]);
    echo "[+] audience_copy landing block added\n";
} else {
    echo "[=] audience_copy already exists\n";
}

echo "\n=== Migration complete! ===\n";
