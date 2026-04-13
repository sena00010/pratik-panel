<?php
// Render content with proper paragraph separation
// Content uses \n\n for paragraph breaks
function render_blog_content(string $content): string {
    $paragraphs = preg_split('/\n{2,}/', $content);
    $html = '';
    foreach ($paragraphs as $p) {
        $p = trim($p);
        if ($p === '') continue;
        // Check if it's an image tag (already HTML)
        if (strpos($p, '<img') === 0 || strpos($p, '<figure') === 0) {
            $html .= $p;
        } else {
            $html .= '<p>' . nl2br(e($p)) . '</p>';
        }
    }
    return $html;
}
?>
<?php if (!empty($post['cover_image'])): ?>
<div class="blog-hero-image">
    <img src="<?= e(strpos($post['cover_image'], 'http') === 0 ? $post['cover_image'] : asset($post['cover_image'])) ?>" alt="<?= e($post['title']) ?>">
</div>
<?php endif; ?>
<section class="blog-detail-header">
    <div class="blog-detail-header__inner">
        <div style="display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:24px;color:rgba(255,255,255,0.8)">
            <?php if (!empty($post['author_photo'])): ?>
            <img src="<?= e($post['author_photo']) ?>" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.1)">
            <?php else: ?>
            <div style="width:36px;height:36px;border-radius:50%;background:rgba(18,200,191,.15);color:#12c8bf;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px"><?= mb_strtoupper(mb_substr($post['author_name'] ?: $post['author_username'] ?: 'A', 0, 1)) ?></div>
            <?php endif; ?>
            <span style="font-weight:700"><?= e($post['author_name'] ?: $post['author_username'] ?: 'Admin') ?></span>
            <span style="color:rgba(255,255,255,0.3)">·</span>
            <time style="font-weight:500"><?= e(date('d M Y', strtotime($post['published_at']))) ?></time>
        </div>
        <h1><?= e($post['title']) ?></h1>
        <p class="blog-detail-lead"><?= e($post['summary']) ?></p>
    </div>
</section>
<article class="blog-detail-body">
    <div class="blog-detail-content">
        <?= render_blog_content($post['content']) ?>
    </div>
</article>
<section class="blog-detail-footer">
    <a href="<?= url('/blog') ?>" class="blog-back-link">← Tüm Yazılara Dön</a>
</section>
<?php require __DIR__ . '/footer.php'; ?>
