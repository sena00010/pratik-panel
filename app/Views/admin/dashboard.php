<?php
function json_lines(?string $value): string {
    $items = json_decode($value ?: '[]', true) ?: [];
    return implode("\n", $items);
}
function pretty_json(string $value): string {
    $decoded = json_decode($value, true);
    return json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?: $value;
}
?>
<header class="admin-header">
    <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
    <form method="post" action="<?= admin_url('logout') ?>"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><button>Çıkış</button></form>
</header>
<main class="admin-main">
    <?php if (!empty($_SESSION['flash'])): ?><p class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></p><?php endif; ?>
    <h1>İçerik Yönetimi</h1>

    <section class="panel">
        <h2>Landing Sayfası Blokları</h2>
        <p class="help">Hero, kullananlar, nasıl çalışır adımları, rakip karşılaştırması, güvenlik ve fiyatlandırma gibi ana alanları buradan değiştirebilirsiniz. Türkçe karakterler korunur. JSON formatı bozulursa kayıt yapılmaz.</p>
        <form method="post" action="<?= admin_url('landing/save') ?>" class="landing-form">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <?php foreach ($landingRows as $row): ?>
                <details class="edit-row">
                    <summary><?= e($row['title']) ?> <small><?= e($row['block_key']) ?></small></summary>
                    <label><?= e($row['title']) ?><textarea name="payload[<?= (int) $row['id'] ?>]" rows="14" spellcheck="false"><?= e(pretty_json($row['payload'])) ?></textarea></label>
                </details>
            <?php endforeach; ?>
            <button>Landing bloklarını kaydet</button>
        </form>
    </section>

    <section class="panel">
        <h2>Modüller</h2>
        <?php foreach ($modules as $module): ?>
            <details class="edit-row">
                <summary><?= e($module['title']) ?> <small><?= e($module['slug']) ?></small></summary>
                <form method="post" action="<?= admin_url('modules/save') ?>" class="grid-form">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $module['id'] ?>">
                    <label>Başlık<input name="title" value="<?= e($module['title']) ?>" required></label>
                    <label>Slug<input name="slug" value="<?= e($module['slug']) ?>"></label>
                    <label>Etiket<input name="eyebrow" value="<?= e($module['eyebrow']) ?>"></label>
                    <label>İkon<input name="icon" value="<?= e($module['icon']) ?>"></label>
                    <label>Renk<input name="accent" value="<?= e($module['accent']) ?>"></label>
                    <label>Sıra<input type="number" name="sort_order" value="<?= (int) $module['sort_order'] ?>"></label>
                    <label class="wide">Özet<textarea name="summary"><?= e($module['summary']) ?></textarea></label>
                    <label class="wide">Özellikler - her satır bir madde<textarea name="features"><?= e(json_lines($module['features'])) ?></textarea></label>
                    <label class="wide">Detay sayfası metni<textarea name="detail_content" rows="7"><?= e($module['detail_content']) ?></textarea></label>
                    <label class="check"><input type="checkbox" name="is_active" value="1" <?= $module['is_active'] ? 'checked' : '' ?>> Yayında</label>
                    <button>Kaydet</button>
                </form>
                <form method="post" action="<?= admin_url('modules/delete') ?>" onsubmit="return confirm('Silinsin mi?')"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $module['id'] ?>"><button class="danger">Sil</button></form>
            </details>
        <?php endforeach; ?>
        <details class="edit-row create"><summary>Yeni modül ekle</summary><form method="post" action="<?= admin_url('modules/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><label>Başlık<input name="title" required></label><label>Slug<input name="slug"></label><label>Etiket<input name="eyebrow" value="MODÜL"></label><label>İkon<input name="icon" value="□"></label><label>Renk<input name="accent" value="#12c8bf"></label><label>Sıra<input type="number" name="sort_order" value="99"></label><label class="wide">Özet<textarea name="summary"></textarea></label><label class="wide">Özellikler<textarea name="features"></textarea></label><label class="wide">Detay<textarea name="detail_content" rows="7"></textarea></label><label class="check"><input type="checkbox" name="is_active" value="1" checked> Yayında</label><button>Kaydet</button></form></details>
    </section>

    <section class="panel">
        <h2>SSS</h2>
        <?php foreach ($faqs as $faq): ?>
            <details class="edit-row"><summary><?= e($faq['question']) ?></summary><form method="post" action="<?= admin_url('faqs/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $faq['id'] ?>"><label class="wide">Soru<input name="question" value="<?= e($faq['question']) ?>" required></label><label class="wide">Cevap<textarea name="answer"><?= e($faq['answer']) ?></textarea></label><label>Sıra<input type="number" name="sort_order" value="<?= (int) $faq['sort_order'] ?>"></label><label class="check"><input type="checkbox" name="is_active" value="1" <?= $faq['is_active'] ? 'checked' : '' ?>> Yayında</label><button>Kaydet</button></form><form method="post" action="<?= admin_url('faqs/delete') ?>" onsubmit="return confirm('Silinsin mi?')"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $faq['id'] ?>"><button class="danger">Sil</button></form></details>
        <?php endforeach; ?>
        <details class="edit-row create"><summary>Yeni SSS ekle</summary><form method="post" action="<?= admin_url('faqs/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><label class="wide">Soru<input name="question" required></label><label class="wide">Cevap<textarea name="answer"></textarea></label><label>Sıra<input type="number" name="sort_order" value="99"></label><label class="check"><input type="checkbox" name="is_active" value="1" checked> Yayında</label><button>Kaydet</button></form></details>
    </section>

    <section class="panel">
        <h2>Blog</h2>
        <?php foreach ($posts as $post): ?>
            <details class="edit-row"><summary><?= e($post['title']) ?> <small><?= e($post['slug']) ?></small></summary><form method="post" action="<?= admin_url('blogs/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $post['id'] ?>"><label>Başlık<input name="title" value="<?= e($post['title']) ?>" required></label><label>Slug<input name="slug" value="<?= e($post['slug']) ?>"></label><label>Yayın tarihi<input name="published_at" value="<?= e($post['published_at']) ?>"></label><label>Kapak görsel URL<input name="cover_image" value="<?= e($post['cover_image']) ?>"></label><label class="wide">Özet<textarea name="summary"><?= e($post['summary']) ?></textarea></label><label class="wide">İçerik<textarea name="content" rows="9"><?= e($post['content']) ?></textarea></label><label class="check"><input type="checkbox" name="is_published" value="1" <?= $post['is_published'] ? 'checked' : '' ?>> Yayında</label><button>Kaydet</button></form><form method="post" action="<?= admin_url('blogs/delete') ?>" onsubmit="return confirm('Silinsin mi?')"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $post['id'] ?>"><button class="danger">Sil</button></form></details>
        <?php endforeach; ?>
        <details class="edit-row create"><summary>Yeni blog yazısı ekle</summary><form method="post" action="<?= admin_url('blogs/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><label>Başlık<input name="title" required></label><label>Slug<input name="slug"></label><label>Yayın tarihi<input name="published_at" value="<?= e(date('Y-m-d H:i:s')) ?>"></label><label>Kapak görsel URL<input name="cover_image"></label><label class="wide">Özet<textarea name="summary"></textarea></label><label class="wide">İçerik<textarea name="content" rows="9"></textarea></label><label class="check"><input type="checkbox" name="is_published" value="1" checked> Yayında</label><button>Kaydet</button></form></details>
    </section>

    <section class="panel">
        <h2>SEO</h2>
        <?php foreach ($seoRows as $row): ?>
            <details class="edit-row"><summary><?= e($row['page']) ?> <?= $row['slug'] ? '· ' . e($row['slug']) : '' ?></summary><form method="post" action="<?= admin_url('seo/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><label>Sayfa kodu<input name="page" value="<?= e($row['page']) ?>" required></label><label>Slug<input name="slug" value="<?= e($row['slug']) ?>"></label><label class="wide">Meta başlık<input name="meta_title" value="<?= e($row['meta_title']) ?>"></label><label class="wide">Meta açıklama<textarea name="meta_description"><?= e($row['meta_description']) ?></textarea></label><label class="wide">Anahtar kelimeler<input name="meta_keywords" value="<?= e($row['meta_keywords']) ?>"></label><label class="wide">OG görsel<input name="og_image" value="<?= e($row['og_image']) ?>"></label><button>Kaydet</button></form></details>
        <?php endforeach; ?>
        <details class="edit-row create"><summary>Yeni SEO kaydı ekle</summary><form method="post" action="<?= admin_url('seo/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><label>Sayfa kodu<input name="page" placeholder="home, blog, module, blog_detail" required></label><label>Slug<input name="slug"></label><label class="wide">Meta başlık<input name="meta_title"></label><label class="wide">Meta açıklama<textarea name="meta_description"></textarea></label><label class="wide">Anahtar kelimeler<input name="meta_keywords"></label><label class="wide">OG görsel<input name="og_image"></label><button>Kaydet</button></form></details>
    </section>
</main>
