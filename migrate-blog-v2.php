<?php
/**
 * Blog System v2 Migration
 * - blog_posts: add meta_title, meta_description, status (draft/pending/approved/rejected)
 * - admin_users: add auto_approve column
 */
require __DIR__ . '/app/bootstrap.php';
$pdo = Database::pdo();

header('Content-Type: text/plain; charset=utf-8');
echo "=== Blog System v2 Migration ===\n\n";

// 1. blog_posts — add meta_title
try {
    $pdo->exec("ALTER TABLE blog_posts ADD COLUMN meta_title VARCHAR(220) DEFAULT NULL AFTER cover_image");
    echo "[+] blog_posts.meta_title added\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) echo "[=] blog_posts.meta_title already exists\n";
    else throw $e;
}

// 2. blog_posts — add meta_description
try {
    $pdo->exec("ALTER TABLE blog_posts ADD COLUMN meta_description VARCHAR(320) DEFAULT NULL AFTER meta_title");
    echo "[+] blog_posts.meta_description added\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) echo "[=] blog_posts.meta_description already exists\n";
    else throw $e;
}

// 3. blog_posts — add status column (draft/pending/approved/rejected)
try {
    $pdo->exec("ALTER TABLE blog_posts ADD COLUMN status ENUM('draft','pending','approved','rejected') DEFAULT 'draft' AFTER is_published");
    echo "[+] blog_posts.status added\n";
    // Migrate existing data: is_published=1 → approved, is_published=0 → draft
    $pdo->exec("UPDATE blog_posts SET status = 'approved' WHERE is_published = 1");
    $pdo->exec("UPDATE blog_posts SET status = 'draft' WHERE is_published = 0");
    echo "[+] Existing posts migrated to new status system\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) echo "[=] blog_posts.status already exists\n";
    else throw $e;
}

// 4. admin_users — add auto_approve column
try {
    $pdo->exec("ALTER TABLE admin_users ADD COLUMN auto_approve TINYINT(1) DEFAULT 0 AFTER display_name");
    echo "[+] admin_users.auto_approve added\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) echo "[=] admin_users.auto_approve already exists\n";
    else throw $e;
}

echo "\n=== Migration complete! ===\n";
