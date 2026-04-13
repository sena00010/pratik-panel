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
        <time><?= e(date('d M Y', strtotime($post['published_at']))) ?></time>
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
