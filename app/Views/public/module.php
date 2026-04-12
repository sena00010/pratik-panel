<?php $features = json_decode($module['features'] ?: '[]', true) ?: []; ?>
<section class="page-hero dark-grid">
    <span class="pill"><?= e($module['eyebrow']) ?></span>
    <h1><?= e($module['title']) ?></h1>
    <p><?= e($module['summary']) ?></p>
</section>
<section class="section detail-page">
    <aside>
        <strong>Platform Modülleri</strong>
        <?php foreach ($modules as $item): ?><a class="<?= $item['slug'] === $module['slug'] ? 'active' : '' ?>" href="<?= url('/modul/' . $item['slug']) ?>"><?= e($item['title']) ?></a><?php endforeach; ?>
    </aside>
    <article>
        <div class="module-icon big" style="--accent: <?= e($module['accent']) ?>"><?= module_icon_svg($module['slug']) ?></div>
        <h2><?= e($module['title']) ?> ile neler yapabilirsiniz?</h2>
        <p><?= nl2br(e($module['detail_content'])) ?></p>
        <ul><?php foreach ($features as $feature): ?><li><?= e($feature) ?></li><?php endforeach; ?></ul>
    </article>
</section>
<?php require __DIR__ . '/footer.php'; ?>
