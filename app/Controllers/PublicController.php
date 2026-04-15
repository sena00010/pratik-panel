<?php
declare(strict_types=1);

final class PublicController
{
    private ContentRepository $repo;

    public function __construct()
    {
        $this->repo = new ContentRepository();
    }

    public function home(): void
    {
        $faqs = $this->repo->faqs();
        View::render('public/home', [
            'settings'      => $this->repo->siteSettings(),
            'modules'       => $this->repo->modules(),
            'faqs'          => $faqs,
            'posts'         => array_slice($this->repo->blogPosts(), 0, 3),
            'landingBlocks' => $this->repo->landingBlocks(),
            'integrations'  => $this->repo->integrations(),
            'audienceCards' => $this->repo->audienceCards(),
            'testimonials'  => $this->repo->testimonials(),
            'seo'           => $this->repo->seo('home'),
            'pageType'      => 'home',
            'breadcrumbs'   => SeoHelper::breadcrumbs('home'),
            'schemaJson'    => SeoHelper::homeSchema($this->repo->siteSettings(), $faqs),
        ]);
    }

    public function moduleDetail(string $slug): void
    {
        $module = $this->repo->module($slug);
        if (!$module) {
            http_response_code(404);
            View::render('public/404', ['seo' => $this->repo->seo('404')]);
            return;
        }

        $crumbs = SeoHelper::breadcrumbs('module', ['title' => $module['title'], 'slug' => $module['slug']]);

        View::render('public/module', [
            'settings'    => $this->repo->siteSettings(),
            'module'      => $module,
            'modules'     => $this->repo->modules(),
            'seo'         => $this->repo->seo('module', $slug),
            'pageType'    => 'module',
            'breadcrumbs' => $crumbs,
            'schemaJson'  => SeoHelper::moduleSchema($module) . SeoHelper::breadcrumbSchema($crumbs),
        ]);
    }

    public function blog(): void
    {
        $posts = $this->repo->blogPosts();
        $crumbs = SeoHelper::breadcrumbs('blog');

        View::render('public/blog', [
            'settings'    => $this->repo->siteSettings(),
            'modules'     => $this->repo->modules(),
            'posts'       => $posts,
            'seo'         => $this->repo->seo('blog'),
            'pageType'    => 'blog',
            'breadcrumbs' => $crumbs,
            'schemaJson'  => SeoHelper::blogListSchema($posts) . SeoHelper::breadcrumbSchema($crumbs),
        ]);
    }

    public function blogDetail(string $slug): void
    {
        $post = $this->repo->blogPost($slug);
        if (!$post) {
            http_response_code(404);
            View::render('public/404', ['seo' => $this->repo->seo('404')]);
            return;
        }

        $seo = $this->repo->seo('blog_detail', $slug);
        // Override with post-level SEO if available
        if (!empty($post['meta_title'])) $seo['meta_title'] = $post['meta_title'];
        if (!empty($post['meta_description'])) $seo['meta_description'] = $post['meta_description'];
        // Fallback: use post title as meta_title if nothing set
        if (empty($seo['meta_title'])) $seo['meta_title'] = $post['title'] . ' | Pratik Gümrük Blog';
        if (empty($seo['meta_description']) && !empty($post['summary'])) $seo['meta_description'] = mb_substr(strip_tags($post['summary']), 0, 160);

        $crumbs = SeoHelper::breadcrumbs('blog_detail', ['title' => $post['title'], 'slug' => $post['slug']]);
        $relatedPosts = $this->repo->relatedPosts($slug, 4);

        View::render('public/blog-detail', [
            'settings'     => $this->repo->siteSettings(),
            'modules'      => $this->repo->modules(),
            'post'         => $post,
            'seo'          => $seo,
            'pageType'     => 'blog_detail',
            'breadcrumbs'  => $crumbs,
            'relatedPosts' => $relatedPosts,
            'schemaJson'   => SeoHelper::blogDetailSchema($post) . SeoHelper::breadcrumbSchema($crumbs),
            'blogAuthor'   => [
                'name'     => $post['author_name'] ?: $post['author_username'] ?: 'Pratik Gümrük',
                'photo'    => $post['author_photo'] ?? '',
                'role'     => $post['author_role'] ?? 'admin',
                'date'     => date('d M Y', strtotime($post['published_at'])),
                'dateIso'  => date('Y-m-d', strtotime($post['published_at'])),
                'readTime' => max(1, (int)(mb_strlen($post['content'] ?? '') / 800)),
            ],
        ]);
    }

    /**
     * Serve sitemap.xml — regenerated daily, cached in storage.
     */
    public function sitemap(): void
    {
        $cacheFile = __DIR__ . '/../../storage/cache/sitemap.xml';

        // Regenerate if file doesn't exist or is older than 24 hours
        if (!is_file($cacheFile) || filemtime($cacheFile) + 86400 < time()) {
            $urls = $this->repo->sitemapUrls();
            $xml  = SeoHelper::generateSitemapXml($urls);
            file_put_contents($cacheFile, $xml);
            $this->repo->recordSitemapGeneration();
        }

        header('Content-Type: application/xml; charset=UTF-8');
        readfile($cacheFile);
        exit;
    }

    /**
     * Serve robots.txt
     */
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');
        echo SeoHelper::generateRobotsTxt();
        exit;
    }

    public function deployTest(): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'ok',
            'message' => 'Deploy basarili!',
            'time' => date('Y-m-d H:i:s'),
            'server' => gethostname()
        ]);
        exit;
    }
}
