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

    public function landingBlocks(): array
    {
        return cache_remember('landing_blocks', 300, function (): array {
            $rows = Database::pdo()->query('SELECT block_key, title, payload FROM landing_blocks ORDER BY id ASC')->fetchAll();
            $blocks = [];
            foreach ($rows as $row) {
                $decoded = json_decode((string) $row['payload'], true);
                $blocks[$row['block_key']] = is_array($decoded) ? $decoded : [];
            }
            return $blocks;
        });
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

    public function adminUserById(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM admin_users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function integrations(bool $activeOnly = true): array
    {
        return cache_remember('integrations_' . (int) $activeOnly, 300, function () use ($activeOnly): array {
            $sql = 'SELECT * FROM integrations' . ($activeOnly ? ' WHERE is_active = 1' : '') . ' ORDER BY sort_order ASC, id ASC';
            return Database::pdo()->query($sql)->fetchAll();
        });
    }

    public function audienceCards(bool $activeOnly = true): array
    {
        return cache_remember('audience_' . (int) $activeOnly, 300, function () use ($activeOnly): array {
            $sql = 'SELECT * FROM audience_cards' . ($activeOnly ? ' WHERE is_active = 1' : '') . ' ORDER BY sort_order ASC, id ASC';
            return Database::pdo()->query($sql)->fetchAll();
        });
    }

    public function blogPostsByAuthor(int $authorId): array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM blog_posts WHERE author_id = ? ORDER BY published_at DESC, id DESC');
        $stmt->execute([$authorId]);
        return $stmt->fetchAll();
    }

    public function testimonials(bool $activeOnly = true): array
    {
        return cache_remember('testimonials_' . (int) $activeOnly, 300, function () use ($activeOnly): array {
            $sql = 'SELECT * FROM testimonials' . ($activeOnly ? ' WHERE is_active = 1' : '') . ' ORDER BY sort_order ASC, id ASC';
            return Database::pdo()->query($sql)->fetchAll();
        });
    }
}
