<section class="page-hero simple">
    <time><?= e(date('d.m.Y', strtotime($post['published_at']))) ?></time>
    <h1><?= e($post['title']) ?></h1>
    <p><?= e($post['summary']) ?></p>
</section>
<article class="section article-body">
    <?= nl2br(e($post['content'])) ?>
</article>
<?php require __DIR__ . '/footer.php'; ?>
