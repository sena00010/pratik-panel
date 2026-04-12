<?php
$seo = $seo ?? [];
$settings = $settings ?? [];
$metaTitle = $seo['meta_title'] ?? ($settings['default_meta_title'] ?? config('app.name'));
$metaDescription = $seo['meta_description'] ?? ($settings['default_meta_description'] ?? '');
$metaKeywords = $seo['meta_keywords'] ?? ($settings['default_meta_keywords'] ?? '');
$ogImage = $seo['og_image'] ?? '';
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="keywords" content="<?= e($metaKeywords) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <?php if ($ogImage): ?><meta property="og:image" content="<?= e($ogImage) ?>"><?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800;900&family=Inter+Tight:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/site.css') ?>">
    <script src="<?= asset('js/site.js') ?>" defer></script>
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
        <a class="btn btn-ghost" href="#giris">Giriş Yap</a>
        <a class="btn btn-primary" href="<?= url('/#fiyatlandirma') ?>">Ücretsiz Deneyin</a>
    </div>
</header>
<main>
    <?= $content ?>
</main>
</body>
</html>
