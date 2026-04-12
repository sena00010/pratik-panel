<section class="page-hero simple">
    <span class="eyebrow">BLOG</span>
    <h1>Gümrük gündemi</h1>
    <p>GTİP, vergi, mevzuat ve operasyon süreçleri için pratik notlar.</p>
</section>
<section class="section">
    <div class="blog-grid list">
        <?php foreach ($posts as $post): ?>
            <article>
                <time><?= e(date('d.m.Y', strtotime($post['published_at']))) ?></time>
                <h2><?= e($post['title']) ?></h2>
                <p><?= e($post['summary']) ?></p>
                <a href="<?= url('/blog/' . $post['slug']) ?>">Yazıyı Oku →</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
