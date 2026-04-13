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
        View::render('public/home', [
            'settings' => $this->repo->siteSettings(),
            'modules' => $this->repo->modules(),
            'faqs' => $this->repo->faqs(),
            'posts' => array_slice($this->repo->blogPosts(), 0, 3),
            'landingBlocks' => $this->repo->landingBlocks(),
            'integrations' => $this->repo->integrations(),
            'audienceCards' => $this->repo->audienceCards(),
            'testimonials' => $this->repo->testimonials(),
            'seo' => $this->repo->seo('home'),
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

        View::render('public/module', [
            'settings' => $this->repo->siteSettings(),
            'module' => $module,
            'modules' => $this->repo->modules(),
            'seo' => $this->repo->seo('module', $slug),
        ]);
    }

    public function blog(): void
    {
        View::render('public/blog', [
            'settings' => $this->repo->siteSettings(),
            'posts' => $this->repo->blogPosts(),
            'seo' => $this->repo->seo('blog'),
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

        View::render('public/blog-detail', [
            'settings' => $this->repo->siteSettings(),
            'post' => $post,
            'seo' => $seo,
        ]);
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
