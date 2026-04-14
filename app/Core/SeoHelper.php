<?php
declare(strict_types=1);

/**
 * SeoHelper — breadcrumb, JSON-LD structured data, TOC, canonical URL utilities.
 */
final class SeoHelper
{
    // ───────────────────── Canonical URL ─────────────────────
    /**
     * Return the canonical URL for the current page (no trailing slash except root).
     */
    public static function canonical(string $path = ''): string
    {
        $base = rtrim(config('app.base_url', ''), '/');
        if ($path === '' || $path === '/') {
            return $base . '/';
        }
        return $base . '/' . trim($path, '/');
    }

    // ───────────────────── Breadcrumb ─────────────────────
    /**
     * Build breadcrumb items array: [{name, url}]
     */
    public static function breadcrumbs(string $pageType, array $context = []): array
    {
        $home = ['name' => 'Ana Sayfa', 'url' => self::canonical('/')];
        $crumbs = [$home];

        switch ($pageType) {
            case 'blog':
                $crumbs[] = ['name' => 'Blog', 'url' => self::canonical('/blog')];
                break;

            case 'blog_detail':
                $crumbs[] = ['name' => 'Blog', 'url' => self::canonical('/blog')];
                $crumbs[] = [
                    'name' => $context['title'] ?? 'Yazı',
                    'url'  => self::canonical('/blog/' . ($context['slug'] ?? '')),
                ];
                break;

            case 'module':
                $crumbs[] = ['name' => 'Modüller', 'url' => self::canonical('/#moduller')];
                $crumbs[] = [
                    'name' => $context['title'] ?? 'Modül',
                    'url'  => self::canonical('/modul/' . ($context['slug'] ?? '')),
                ];
                break;

            default:
                // homepage — no extra crumbs
                break;
        }

        return $crumbs;
    }

    /**
     * Render breadcrumb HTML (no-JS semantic nav).
     */
    public static function renderBreadcrumbHtml(array $crumbs): string
    {
        if (count($crumbs) <= 1) return '';

        $html  = '<nav class="breadcrumb" aria-label="Breadcrumb">';
        $html .= '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';
        foreach ($crumbs as $i => $crumb) {
            $pos = $i + 1;
            $isLast = ($i === count($crumbs) - 1);
            $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            if (!$isLast) {
                $html .= '<a itemprop="item" href="' . e($crumb['url']) . '">';
                $html .= '<span itemprop="name">' . e($crumb['name']) . '</span>';
                $html .= '</a>';
                $html .= '<meta itemprop="position" content="' . $pos . '">';
                $html .= '<span class="breadcrumb__sep" aria-hidden="true">/</span>';
            } else {
                $html .= '<span itemprop="name" aria-current="page">' . e($crumb['name']) . '</span>';
                $html .= '<meta itemprop="position" content="' . $pos . '">';
            }
            $html .= '</li>';
        }
        $html .= '</ol></nav>';
        return $html;
    }

    // ───────────────────── JSON-LD Schema ─────────────────────
    /**
     * Generate JSON-LD for the homepage (Organization + WebSite + FAQPage).
     */
    public static function homeSchema(array $settings, array $faqs = []): string
    {
        $base = rtrim(config('app.base_url', ''), '/');
        $name = $settings['default_meta_title'] ?? config('app.name');
        $desc = $settings['default_meta_description'] ?? '';

        $schemas = [];

        // Organization
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => 'Pratik Gümrük',
            'url'      => $base . '/',
            'logo'     => $base . '/assets/img/logo.png',
            'description' => $desc,
            'sameAs'   => [],
        ];

        // WebSite with SearchAction
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => 'Pratik Gümrük',
            'url'      => $base . '/',
            'potentialAction' => [
                '@type'  => 'SearchAction',
                'target' => $base . '/blog?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];

        // FAQPage
        if (!empty($faqs)) {
            $faqItems = [];
            foreach ($faqs as $faq) {
                $faqItems[] = [
                    '@type'          => 'Question',
                    'name'           => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => strip_tags($faq['answer']),
                    ],
                ];
            }
            $schemas[] = [
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => $faqItems,
            ];
        }

        return self::jsonLdBlock($schemas);
    }

    /**
     * Generate JSON-LD for a blog listing page (CollectionPage).
     */
    public static function blogListSchema(array $posts): string
    {
        $base = rtrim(config('app.base_url', ''), '/');
        $items = [];
        foreach ($posts as $i => $post) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'url'      => $base . '/blog/' . $post['slug'],
            ];
        }

        $schema = [
            '@context'        => 'https://schema.org',
            '@type'           => 'CollectionPage',
            'name'            => 'Pratik Gümrük Blog',
            'url'             => $base . '/blog',
            'mainEntity'      => [
                '@type'           => 'ItemList',
                'itemListElement' => $items,
            ],
        ];

        return self::jsonLdBlock([$schema]);
    }

    /**
     * Generate JSON-LD for a blog article (Article schema).
     */
    public static function blogDetailSchema(array $post): string
    {
        $base = rtrim(config('app.base_url', ''), '/');
        $authorName = $post['author_name'] ?: ($post['author_username'] ?? 'Pratik Gümrük');
        $published  = date('c', strtotime($post['published_at']));
        $modified   = !empty($post['updated_at']) ? date('c', strtotime($post['updated_at'])) : $published;
        $imageUrl   = '';
        if (!empty($post['cover_image'])) {
            $imageUrl = (strpos($post['cover_image'], 'http') === 0)
                ? $post['cover_image']
                : $base . '/assets/' . ltrim($post['cover_image'], '/');
        }

        $schema = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => $post['title'],
            'description'   => mb_substr(strip_tags($post['summary'] ?? ''), 0, 160),
            'url'           => $base . '/blog/' . $post['slug'],
            'datePublished' => $published,
            'dateModified'  => $modified,
            'author'        => [
                '@type' => 'Person',
                'name'  => $authorName,
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => 'Pratik Gümrük',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => $base . '/assets/img/logo.png',
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id'   => $base . '/blog/' . $post['slug'],
            ],
        ];

        if ($imageUrl) {
            $schema['image'] = $imageUrl;
        }

        return self::jsonLdBlock([$schema]);
    }

    /**
     * Generate JSON-LD for a module/service page (Service schema).
     */
    public static function moduleSchema(array $module): string
    {
        $base = rtrim(config('app.base_url', ''), '/');

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'name'        => $module['title'],
            'description' => $module['summary'],
            'url'         => $base . '/modul/' . $module['slug'],
            'provider'    => [
                '@type' => 'Organization',
                'name'  => 'Pratik Gümrük',
                'url'   => $base . '/',
            ],
        ];

        return self::jsonLdBlock([$schema]);
    }

    /**
     * Generate BreadcrumbList JSON-LD from crumbs array.
     */
    public static function breadcrumbSchema(array $crumbs): string
    {
        if (count($crumbs) <= 1) return '';

        $items = [];
        foreach ($crumbs as $i => $crumb) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $crumb['name'],
                'item'     => $crumb['url'],
            ];
        }

        $schema = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];

        return self::jsonLdBlock([$schema]);
    }

    // ───────────────────── Table of Contents ─────────────────────
    /**
     * Parse HTML content headings (h2, h3) and generate TOC + inject IDs.
     * Returns ['toc' => string HTML, 'content' => string HTML with IDs].
     */
    public static function generateToc(string $htmlContent): array
    {
        // Match h2 and h3 tags
        $headings = [];
        $pattern = '/<(h[23])[^>]*>(.*?)<\/\1>/si';
        preg_match_all($pattern, $htmlContent, $matches, PREG_SET_ORDER);

        if (count($matches) < 2) {
            // Not enough headings for a TOC
            return ['toc' => '', 'content' => $htmlContent];
        }

        $tocItems = [];
        $usedIds  = [];

        foreach ($matches as $match) {
            $tag   = strtolower($match[1]);
            $text  = strip_tags($match[2]);
            $id    = self::slugifyHeading($text);

            // Ensure unique ID
            $origId = $id;
            $counter = 1;
            while (in_array($id, $usedIds, true)) {
                $id = $origId . '-' . $counter++;
            }
            $usedIds[] = $id;

            $tocItems[] = ['tag' => $tag, 'text' => $text, 'id' => $id];

            // Inject id into the heading tag in content
            $replacement = '<' . $match[1] . ' id="' . $id . '">' . $match[2] . '</' . $match[1] . '>';
            $htmlContent = str_replace($match[0], $replacement, $htmlContent);
        }

        // Build TOC HTML
        $toc  = '<nav class="toc" aria-label="İçindekiler">';
        $toc .= '<div class="toc__title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h10M4 18h14"/></svg> İçindekiler</div>';
        $toc .= '<ol class="toc__list">';
        foreach ($tocItems as $item) {
            $indent = $item['tag'] === 'h3' ? ' class="toc__sub"' : '';
            $toc .= '<li' . $indent . '><a href="#' . e($item['id']) . '">' . e($item['text']) . '</a></li>';
        }
        $toc .= '</ol></nav>';

        return ['toc' => $toc, 'content' => $htmlContent];
    }

    // ───────────────────── Sitemap / Robots ─────────────────────
    /**
     * Generate XML sitemap content from URLs.
     * @param array $urls [{loc, lastmod, changefreq, priority}]
     */
    public static function generateSitemapXml(array $urls): string
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc']) . '</loc>' . "\n";
            if (!empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '    <changefreq>' . ($u['changefreq'] ?? 'weekly') . '</changefreq>' . "\n";
            $xml .= '    <priority>' . ($u['priority'] ?? '0.5') . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>';
        return $xml;
    }

    /**
     * Generate robots.txt content.
     */
    public static function generateRobotsTxt(): string
    {
        $base = rtrim(config('app.base_url', ''), '/');
        $adminPath = config('app.admin_path', '/admin');

        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: ' . $adminPath,
            'Disallow: ' . $adminPath . '/',
            'Disallow: /storage/',
            'Disallow: /config/',
            'Disallow: /database/',
            '',
            'Sitemap: ' . $base . '/sitemap.xml',
        ];
        return implode("\n", $lines) . "\n";
    }

    // ───────────────────── Helpers ─────────────────────
    private static function jsonLdBlock(array $schemas): string
    {
        $html = '';
        foreach ($schemas as $schema) {
            $html .= '<script type="application/ld+json">'
                . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
                . '</script>' . "\n";
        }
        return $html;
    }

    private static function slugifyHeading(string $text): string
    {
        $map = [
            'ş'=>'s','Ş'=>'s','ı'=>'i','İ'=>'i','ğ'=>'g','Ğ'=>'g',
            'ü'=>'u','Ü'=>'u','ö'=>'o','Ö'=>'o','ç'=>'c','Ç'=>'c',
        ];
        $text = strtr($text, $map);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text) ?: '';
        return trim($text, '-') ?: 'baslik';
    }
}
