<?php $seo = $seo ?? ['meta_title' => 'Yönetim']; ?>
<!doctype html>
<html lang="tr" data-theme="dark">
<head>
    <script>document.documentElement.setAttribute('data-theme', localStorage.getItem('admin-theme') || 'dark');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['meta_title'] ?? 'Yönetim') ?> · Pratik Gümrük</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800;900&family=Inter+Tight:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body>
    <?= $content ?>
</body>
</html>
