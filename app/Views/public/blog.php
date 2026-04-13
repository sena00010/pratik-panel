<section class="page-hero dark-grid" style="color:#fff">
    <span class="eyebrow">BLOG</span>
    <h1>Gümrük gündemi</h1>
    <p>GTİP, vergi, mevzuat ve operasyon süreçleri için pratik notlar.</p>
</section>
<section class="section blog-section">
    <div class="blog-grid-modern">
        <?php foreach ($posts as $i => $post): ?>
            <article class="blog-card <?= $i === 0 ? 'blog-card--featured' : '' ?>">
                <?php if (!empty($post['cover_image'])): ?>
                    <div class="blog-card__img">
                        <img src="<?= e(strpos($post['cover_image'], 'http') === 0 ? $post['cover_image'] : asset($post['cover_image'])) ?>" alt="<?= e($post['title']) ?>" loading="lazy">
                    </div>
                <?php else: ?>
                    <div class="blog-card__img blog-card__img--placeholder">
                        <svg viewBox="0 0 24 24" width="48" height="48"><rect x="3" y="3" width="18" height="18" rx="2" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/><path d="m21 15-5-5L5 21" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                    </div>
                <?php endif; ?>
                <div class="blog-card__body">
                    <time><?= e(date('d M Y', strtotime($post['published_at']))) ?></time>
                    <h2><a href="<?= url('/blog/' . $post['slug']) ?>"><?= e($post['title']) ?></a></h2>
                    <p><?= e($post['summary']) ?></p>
                    <a class="blog-card__link" href="<?= url('/blog/' . $post['slug']) ?>">Yazıyı Oku <span>→</span></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
