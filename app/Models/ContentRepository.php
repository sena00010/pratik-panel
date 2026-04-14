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
            $sql = 'SELECT bp.*, au.display_name as author_name, au.username as author_username, au.profile_photo as author_photo ' .
                   'FROM blog_posts bp LEFT JOIN admin_users au ON bp.author_id = au.id' . 
                   ($activeOnly ? " WHERE bp.status = 'approved'" : '') . 
                   ' ORDER BY bp.published_at DESC, bp.id DESC';
            return Database::pdo()->query($sql)->fetchAll();
        });
    }

    public function blogPost(string $slug): ?array
    {
        $sql = "SELECT bp.*, au.display_name as author_name, au.username as author_username, au.profile_photo as author_photo 
                FROM blog_posts bp LEFT JOIN admin_users au ON bp.author_id = au.id 
                WHERE bp.slug = ? AND bp.status = 'approved' LIMIT 1";
        $stmt = Database::pdo()->prepare($sql);
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

    /**
     * Get related blog posts (exclude $currentSlug, return max $limit recent posts).
     */
    public function relatedPosts(string $currentSlug, int $limit = 4): array
    {
        $sql = "SELECT bp.*, au.display_name as author_name, au.username as author_username, au.profile_photo as author_photo
                FROM blog_posts bp LEFT JOIN admin_users au ON bp.author_id = au.id
                WHERE bp.status = 'approved' AND bp.slug != ?
                ORDER BY bp.published_at DESC, bp.id DESC
                LIMIT " . (int) $limit;
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([$currentSlug]);
        return $stmt->fetchAll();
    }

    /**
     * Collect all public URLs for sitemap generation.
     * Returns array of ['loc' => url, 'lastmod' => Y-m-d, 'changefreq' => ..., 'priority' => ...]
     */
    public function sitemapUrls(): array
    {
        $base = rtrim(config('app.base_url', ''), '/');
        $urls = [];

        // Homepage
        $urls[] = [
            'loc'        => $base . '/',
            'lastmod'    => date('Y-m-d'),
            'changefreq' => 'daily',
            'priority'   => '1.0',
        ];

        // Blog listing
        $urls[] = [
            'loc'        => $base . '/blog',
            'lastmod'    => date('Y-m-d'),
            'changefreq' => 'daily',
            'priority'   => '0.8',
        ];

        // Blog posts
        $posts = Database::pdo()->query("SELECT slug, updated_at, published_at FROM blog_posts WHERE status = 'approved' ORDER BY published_at DESC")->fetchAll();
        foreach ($posts as $post) {
            $mod = !empty($post['updated_at']) ? date('Y-m-d', strtotime($post['updated_at'])) : date('Y-m-d', strtotime($post['published_at']));
            $urls[] = [
                'loc'        => $base . '/blog/' . $post['slug'],
                'lastmod'    => $mod,
                'changefreq' => 'weekly',
                'priority'   => '0.7',
            ];
        }

        // Modules
        $modules = Database::pdo()->query("SELECT slug, updated_at, created_at FROM modules WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();
        foreach ($modules as $mod) {
            $lastmod = !empty($mod['updated_at']) ? date('Y-m-d', strtotime($mod['updated_at'])) : date('Y-m-d', strtotime($mod['created_at']));
            $urls[] = [
                'loc'        => $base . '/modul/' . $mod['slug'],
                'lastmod'    => $lastmod,
                'changefreq' => 'monthly',
                'priority'   => '0.6',
            ];
        }

        return $urls;
    }

    /**
     * Record sitemap generation timestamp.
     */
    public function recordSitemapGeneration(): void
    {
        try {
            Database::pdo()->exec("INSERT INTO sitemap_log (generated_at) VALUES (NOW())");
        } catch (\Throwable $e) {
            // Table may not exist yet; silently ignore
        }
    }

    /**
     * Check if sitemap was generated today.
     */
    public function sitemapGeneratedToday(): bool
    {
        try {
            $stmt = Database::pdo()->query("SELECT COUNT(*) as cnt FROM sitemap_log WHERE DATE(generated_at) = CURDATE()");
            $row = $stmt->fetch();
            return (int)($row['cnt'] ?? 0) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
