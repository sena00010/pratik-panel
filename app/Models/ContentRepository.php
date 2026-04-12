<?php
declare(strict_types=1);

final class ContentRepository
{
    public function siteSettings(): array
    {
        return cache_remember('site_settings', 300, function (): array {
            $rows = Database::pdo()->query('SELECT `key`, `value` FROM site_settings')->fetchAll();
            return array_column($rows, 'value', 'key');
        });
    }

    public function seo(string $page, ?string $slug = null): array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM seo_meta WHERE page = ? AND slug = ? LIMIT 1');
        $stmt->execute([$page, $slug ?? '']);
        $seo = $stmt->fetch();

        if ($seo) {
            return $seo;
        }

        $settings = $this->siteSettings();
        return [
            'meta_title' => $settings['default_meta_title'] ?? config('app.name'),
            'meta_description' => $settings['default_meta_description'] ?? '',
            'meta_keywords' => $settings['default_meta_keywords'] ?? '',
        ];
    }

    public function modules(bool $activeOnly = true): array
    {
        return cache_remember('modules_' . (int) $activeOnly, 300, function () use ($activeOnly): array {
            $sql = 'SELECT * FROM modules' . ($activeOnly ? ' WHERE is_active = 1' : '') . ' ORDER BY sort_order ASC, id ASC';
            return Database::pdo()->query($sql)->fetchAll();
        });
    }

    public function module(string $slug): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM modules WHERE slug = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$slug]);
        $module = $stmt->fetch();
        return $module ?: null;
    }

    public function faqs(bool $activeOnly = true): array
    {
        return cache_remember('faqs_' . (int) $activeOnly, 300, function () use ($activeOnly): array {
            $sql = 'SELECT * FROM faqs' . ($activeOnly ? ' WHERE is_active = 1' : '') . ' ORDER BY sort_order ASC, id ASC';
            return Database::pdo()->query($sql)->fetchAll();
        });
    }

    public function blogPosts(bool $activeOnly = true): array
    {
        return cache_remember('blog_' . (int) $activeOnly, 300, function () use ($activeOnly): array {
            $sql = 'SELECT * FROM blog_posts' . ($activeOnly ? ' WHERE is_published = 1' : '') . ' ORDER BY published_at DESC, id DESC';
            return Database::pdo()->query($sql)->fetchAll();
        });
    }

    public function blogPost(string $slug): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM blog_posts WHERE slug = ? AND is_published = 1 LIMIT 1');
        $stmt->execute([$slug]);
        $post = $stmt->fetch();
        return $post ?: null;
    }

    public function adminUser(string $username): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}
