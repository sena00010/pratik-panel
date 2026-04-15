<?php
$seo = $seo ?? [];
$settings = $settings ?? [];
$metaTitle = $seo['meta_title'] ?? ($settings['default_meta_title'] ?? config('app.name'));
$metaDescription = $seo['meta_description'] ?? ($settings['default_meta_description'] ?? '');
$metaKeywords = $seo['meta_keywords'] ?? ($settings['default_meta_keywords'] ?? '');
$ogImage = $seo['og_image'] ?? '';
$pageType = $pageType ?? 'home';
$breadcrumbs = $breadcrumbs ?? [];
$schemaJson = $schemaJson ?? '';

// Canonical URL
$canonicalPath = $_SERVER['REQUEST_URI'] ?? '/';
$canonicalUrl = SeoHelper::canonical(parse_url($canonicalPath, PHP_URL_PATH) ?: '/');
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="keywords" content="<?= e($metaKeywords) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:type" content="<?= $pageType === 'blog_detail' ? 'article' : 'website' ?>">
    <meta property="og:site_name" content="Pratik Gümrük">
    <meta property="og:locale" content="tr_TR">
    <?php if ($ogImage): ?><meta property="og:image" content="<?= e($ogImage) ?>"><?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($metaTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800;900&family=Inter+Tight:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/site.css') ?>?v=20260415c">
    <script src="<?= asset('js/site.js') ?>?v=20260415c" defer></script>
    <?= $schemaJson ?>
</head>
<body>
<header class="site-header" data-header>
    <a class="brand" href="<?= url('/') ?>" aria-label="Pratik Gümrük ana sayfa">
        <span>pratik</span><strong>gümrük</strong>
    </a>
    <button class="menu-toggle" data-menu-toggle aria-label="Menüyü aç">☰</button>
    <nav class="main-nav" data-menu>
        <a href="<?= url('/#moduller') ?>">Modüller</a>
        <a href="<?= url('/#nasil-calisir') ?>">Nasıl Çalışır</a>
        <a href="<?= url('/#fiyatlandirma') ?>">Fiyatlandırma</a>
        <a href="<?= url('/#sss') ?>">SSS</a>
        <a href="<?= url('/blog') ?>">Blog</a>
    </nav>
    <div class="header-actions">
        <a class="btn btn-ghost" href="<?= url(config('app.admin_path')) ?>">Giriş Yap</a>
        <a class="btn btn-primary" href="<?= url('/#fiyatlandirma') ?>">Ücretsiz Deneyin</a>
    </div>
</header>
<?php if (!empty($breadcrumbs) && count($breadcrumbs) > 1): ?>
<?= SeoHelper::renderBreadcrumbHtml($breadcrumbs) ?>
<?php endif; ?>
<main>
    <?= $content ?>
</main>

<!-- Mobile Bottom Navigation -->
<nav class="bottom-nav" aria-label="Mobil navigasyon">
    <a href="<?= url('/') ?>">
        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Ana Sayfa</span>
    </a>
    <a href="<?= url('/#moduller') ?>">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        <span>Modüller</span>
    </a>
    <a href="<?= url('/#fiyatlandirma') ?>">
        <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <span>Fiyat</span>
    </a>
    <a href="<?= url('/#sss') ?>">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span>SSS</span>
    </a>
    <a href="<?= url(config('app.admin_path')) ?>">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Giriş</span>
    </a>
</nav>

</body>
</html>
