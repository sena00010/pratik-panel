<section class="page-hero dark-grid" style="color:#fff">
    <span class="eyebrow">BLOG</span>
    <h1>Gümrük gündemi</h1>
    <p>GTİP, vergi, mevzuat ve operasyon süreçleri için pratik notlar.</p>
</section>
<section class="section blog-section">
    <div class="blog-grid-modern">
        <?php foreach ($posts as $i => $post): ?>
            <article class="blog-card <?= $i === 0 ? 'blog-card--featured' : '' ?>" style="--delay: <?= $i * 0.1 ?>s">
                <a href="<?= url('/blog/' . $post['slug']) ?>" class="blog-card__inner">
                    <?php if (!empty($post['cover_image'])): ?>
                        <div class="blog-card__img">
                            <img src="<?= e(strpos($post['cover_image'], 'http') === 0 ? $post['cover_image'] : asset($post['cover_image'])) ?>" alt="<?= e($post['title']) ?>" loading="lazy">
                        </div>
                    <?php else: ?>
                        <div class="blog-card__img blog-card__img--gradient">
                            <div class="blog-card__icon">
                                <?php
                                    $icons = ['📋', '📦', '🔍', '⚖️', '📊', '🛃'];
                                    echo $icons[$i % count($icons)];
                                ?>
                            </div>
                            <div class="blog-card__pattern"></div>
                        </div>
                    <?php endif; ?>
                    <div class="blog-card__body">
                        <div class="blog-card__meta">
                            <div style="display:flex;align-items:center;gap:6px">
                                <?php if (!empty($post['author_photo'])): ?>
                                <img src="<?= e($post['author_photo']) ?>" style="width:20px;height:20px;border-radius:50%;object-fit:cover">
                                <?php else: ?>
                                <div style="width:20px;height:20px;border-radius:50%;background:rgba(18,200,191,.15);color:#12c8bf;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:10px"><?= mb_strtoupper(mb_substr($post['author_name'] ?: $post['author_username'] ?: 'A', 0, 1)) ?></div>
                                <?php endif; ?>
                                <time style="font-size:12px"><?= e(date('d M Y', strtotime($post['published_at']))) ?></time>
                            </div>
                            <span class="blog-card__read"><?= max(1, (int)(mb_strlen($post['content'] ?? '') / 800)) ?> dk okuma</span>
                        </div>
                        <h2><?= e($post['title']) ?></h2>
                        <p><?= e($post['summary']) ?></p>
                        <span class="blog-card__cta">Devamını Oku <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
