<?php
/**
 * Migration: Add testimonials table + social proof data
 */
require __DIR__ . '/app/bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');
echo "=== Testimonials Migration ===\n";

$pdo = Database::pdo();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES utf8mb4");

// 1) Create testimonials table
$pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_name VARCHAR(100) NOT NULL,
    author_title VARCHAR(150) DEFAULT NULL,
    author_location VARCHAR(100) DEFAULT NULL,
    author_initials VARCHAR(10) DEFAULT NULL,
    quote TEXT NOT NULL,
    rating TINYINT DEFAULT 5,
    plan_badge VARCHAR(50) DEFAULT NULL,
    badge_color VARCHAR(20) DEFAULT '#12c8bf',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci");
echo "[+] testimonials table ready\n";

// 2) Seed testimonials data
$count = (int) $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
if ($count === 0) {
    $stmt = $pdo->prepare("INSERT INTO testimonials (author_name, author_title, author_location, author_initials, quote, rating, plan_badge, badge_color, sort_order) VALUES (?,?,?,?,?,?,?,?,?)");

    $testimonials = [
        ['Selin Erdoğan', 'Gümrük Müşaviri', 'İstanbul', 'SE', 'BTB emsal kararları anında geliyor. Daha önce saatler süren araştırmayı 2 dakikaya indirdik. Bir müşterimizin €340.000 ceza riskini tespit edip önledik.', 5, 'Uzman Öncelik', '#e5a019', 1],
        ['Ahmet Kaya', 'Dış Ticaret Müdürü', 'Ankara', 'AK', 'İthalat öncesi maliyet hesabı artık dakikalar içinde. STA avantajlarını kaçırmıyoruz, İGV hesaplaması hata payını sıfırladı.', 5, 'Pro Plan', '#12c8bf', 2],
        ['Mehmet Korkmaz', 'Lojistik Direktörü', 'İzmir', 'MK', 'BeyanameAPI entegrasyonu oyun değiştirici. Hat değişimlerini otomasyon kuralları takip ediyor. Müşteri portalı memnuniyetimizi %40 artırdı.', 5, 'Kurumsal', '#743de8', 3],
    ];

    foreach ($testimonials as $t) {
        $stmt->execute($t);
    }
    echo "[+] 3 testimonials seeded\n";
} else {
    echo "[=] testimonials already has data ($count rows)\n";
}

// 3) Add social_proof landing block
$existing = $pdo->query("SELECT COUNT(*) FROM landing_blocks WHERE block_key = 'social_proof'")->fetchColumn();
if ((int)$existing === 0) {
    $payload = json_encode([
        'eyebrow' => 'SOSYAL KANIT',
        'title' => 'Türkiye genelinde 2.400+ aktif profesyonel',
        'stats' => [
            ['label' => '500+ kullanıcı', 'color' => '#12c8bf'],
            ['label' => '100–500', 'color' => '#3b82f6'],
            ['label' => '50–100', 'color' => '#64748b'],
        ],
        'cities' => 'İstanbul, Ankara, İzmir öncü',
    ], JSON_UNESCAPED_UNICODE);
    $pdo->prepare("INSERT INTO landing_blocks (block_key, title, payload) VALUES (?, ?, ?)")
        ->execute(['social_proof', 'Sosyal Kanıt', $payload]);
    echo "[+] social_proof landing block added\n";
} else {
    echo "[=] social_proof already exists\n";
}

echo "\n=== Migration complete! ===\n";
