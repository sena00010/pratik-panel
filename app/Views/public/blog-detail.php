<?php
// Render content: detect if it's Quill HTML or plain text
function render_blog_content(string $content): string {
    // If content contains HTML tags (from Quill editor), render as HTML
    if (preg_match('/<[a-z][\s\S]*>/i', $content)) {
        // Strip dangerous tags but allow safe formatting
        $allowed = '<p><br><strong><b><em><i><u><s><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><img><figure><figcaption><span><div><table><thead><tbody><tr><td><th><hr><sub><sup>';
        $html = strip_tags($content, $allowed);
        // Ensure images have alt attributes
        $html = preg_replace_callback('/<img(?![^>]*alt=)[^>]*>/i', function($m) {
            return str_replace('<img', '<img alt="Blog görseli"', $m[0]);
        }, $html);
        return $html;
    }

    // Plain text: split by double newlines into paragraphs
    $paragraphs = preg_split('/\n{2,}/', $content);
    $html = '';
    foreach ($paragraphs as $p) {
        $p = trim($p);
        if ($p === '') continue;
        // Check if it looks like a markdown heading (## or ###)
        if (preg_match('/^(#{2,3})\s+(.+)$/', $p, $hMatch)) {
            $level = strlen($hMatch[1]) === 2 ? 'h2' : 'h3';
            $html .= '<' . $level . '>' . e($hMatch[2]) . '</' . $level . '>';
        } else {
            $html .= '<p>' . nl2br(e($p)) . '</p>';
        }
    }
    return $html;
}

$renderedContent = render_blog_content($post['content']);
$tocData = SeoHelper::generateToc($renderedContent);
$tocHtml = $tocData['toc'];
$articleHtml = $tocData['content'];
$relatedPosts = $relatedPosts ?? [];
?>
<?php if (!empty($post['cover_image'])):
    $coverUrl = strpos($post['cover_image'], 'http') === 0 ? $post['cover_image'] : asset($post['cover_image']);
?>
<div class="blog-hero-image">
    <div class="blog-hero-image__blur" style="background-image:url('<?= e($coverUrl) ?>')"></div>
    <img src="<?= e($coverUrl) ?>" alt="<?= e($post['title']) ?> - kapak görseli">
</div>
<?php endif; ?>
<section class="blog-detail-header">
    <div class="blog-detail-header__inner">
        <h1><?= e($post['title']) ?></h1>
        <p class="blog-detail-lead"><?= e($post['summary']) ?></p>
        <div class="blog-detail-meta">
            <?php if (!empty($post['author_photo'])): ?>
            <img class="blog-detail-meta__avatar" src="<?= e($post['author_photo']) ?>" alt="<?= e($post['author_name'] ?: $post['author_username'] ?: 'Yazar') ?> profil fotoğrafı">
            <?php else: ?>
            <div class="blog-detail-meta__avatar blog-detail-meta__avatar--letter"><?= mb_strtoupper(mb_substr($post['author_name'] ?: $post['author_username'] ?: 'P', 0, 1)) ?></div>
            <?php endif; ?>
            <div class="blog-detail-meta__info">
                <div class="blog-detail-meta__name-row">
                    <span class="blog-detail-meta__author"><?= e($post['author_name'] ?: $post['author_username'] ?: 'Pratik Gümrük') ?></span>
                    <?php $role = $post['author_role'] ?? 'admin'; ?>
                    <span class="blog-detail-meta__badge blog-detail-meta__badge--<?= $role === 'blogger' ? 'blogger' : 'admin' ?>"><?= $role === 'blogger' ? 'Blogger' : 'Admin' ?></span>
                </div>
                <div class="blog-detail-meta__details">
                    <time datetime="<?= e(date('Y-m-d', strtotime($post['published_at']))) ?>"><?= e(date('d M Y', strtotime($post['published_at']))) ?></time>
                    <span class="blog-detail-meta__dot">·</span>
                    <span><?= max(1, (int)(mb_strlen($post['content'] ?? '') / 800)) ?> dk okuma</span>
                </div>
            </div>
        </div>
    </div>
</section>
<article class="blog-detail-body">
    <div class="blog-detail-content">
        <?php if ($tocHtml): ?>
        <?= $tocHtml ?>
        <?php endif; ?>
        <?= $articleHtml ?>
    </div>
</article>

<?php if (!empty($relatedPosts)): ?>
<section class="section related-posts">
    <div class="section-head centered">
        <span class="eyebrow">BENZer YAZILAR</span>
        <h2>Diğer yazılarımız</h2>
    </div>
    <div class="related-posts-grid">
        <?php foreach ($relatedPosts as $i => $rp): ?>
        <article class="blog-card" style="--delay: <?= $i * 0.08 ?>s">
            <a href="<?= url('/blog/' . $rp['slug']) ?>" class="blog-card__inner">
                <?php if (!empty($rp['cover_image'])): ?>
                <div class="blog-card__img">
                    <img src="<?= e(strpos($rp['cover_image'], 'http') === 0 ? $rp['cover_image'] : asset($rp['cover_image'])) ?>" alt="<?= e($rp['title']) ?> - kapak görseli" loading="lazy">
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
                            <?php if (!empty($rp['author_photo'])): ?>
                            <img src="<?= e($rp['author_photo']) ?>" alt="<?= e($rp['author_name'] ?: 'Yazar') ?>" style="width:20px;height:20px;border-radius:50%;object-fit:cover">
                            <?php else: ?>
                            <div style="width:20px;height:20px;border-radius:50%;background:rgba(18,200,191,.15);color:#12c8bf;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:10px"><?= mb_strtoupper(mb_substr($rp['author_name'] ?: $rp['author_username'] ?: 'A', 0, 1)) ?></div>
                            <?php endif; ?>
                            <time style="font-size:12px"><?= e(date('d M Y', strtotime($rp['published_at']))) ?></time>
                        </div>
                        <span class="blog-card__read"><?= max(1, (int)(mb_strlen($rp['content'] ?? '') / 800)) ?> dk okuma</span>
                    </div>
                    <h3><?= e($rp['title']) ?></h3>
                    <p><?= e(mb_substr($rp['summary'], 0, 120)) ?>...</p>
                    <span class="blog-card__cta">Devamını Oku <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </div>
            </a>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="blog-detail-footer">
    <a href="<?= url('/blog') ?>" class="blog-back-link">← Tüm Yazılara Dön</a>
</section>
<?php require __DIR__ . '/footer.php'; ?>
