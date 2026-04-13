<?php $adminPath = config('app.admin_path'); ?>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<header class="admin-header">
    <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
    <div class="header-right">
        <span style="color:var(--text-muted);font-size:14px;font-weight:700">✍️ <?= e($_SESSION['admin_username'] ?? '') ?></span>
        <button class="theme-toggle" onclick="toggleTheme()" title="Tema değiştir" aria-label="Tema değiştir">
            <svg class="icon-sun" viewBox="0 0 24 24" width="20" height="20"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
            <svg class="icon-moon" viewBox="0 0 24 24" width="20" height="20"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
        <form method="post" action="<?= admin_url('logout') ?>"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><button class="btn-logout">Çıkış</button></form>
    </div>
</header>

<main class="admin-main">
    <?php if (!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
    <h1>Blog Yazılarım</h1>

    <section class="panel">
        <h2>✍️ Yazılarım</h2>
        <p class="help">Sadece size ait blog yazılarını görebilir ve düzenleyebilirsiniz.</p>

        <?php foreach ($posts as $post): ?>
            <details class="edit-row">
                <summary><?= e($post['title']) ?> <small><?= $post['is_published'] ? '● Yayında' : '○ Taslak' ?></small></summary>
                <form method="post" action="<?= admin_url('blogs/save') ?>" class="grid-form" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
                    <label>Başlık<input name="title" value="<?= e($post['title']) ?>" required></label>
                    <label>Slug<input name="slug" value="<?= e($post['slug']) ?>"></label>
                    <label>Yayın tarihi<input name="published_at" value="<?= e($post['published_at']) ?>"></label>
                    <label>Kapak görsel URL<input name="cover_image" value="<?= e($post['cover_image']) ?>" placeholder="URL veya aşağıdan dosya yükleyin"></label>
                    <label class="wide">Kapak görseli yükle<input type="file" name="cover_image_file" accept="image/*"></label>
                    <?php if (!empty($post['cover_image'])): ?>
                        <div class="wide" style="padding:0 18px 8px">
                            <img src="<?= e(strpos($post['cover_image'], 'http') === 0 ? $post['cover_image'] : asset($post['cover_image'])) ?>" style="max-height:120px;border-radius:8px;border:1px solid var(--border)">
                        </div>
                    <?php endif; ?>
                    <label class="wide">Özet<textarea name="summary"><?= e($post['summary']) ?></textarea></label>
                    <label class="wide">İçerik
                        <div id="quillEditor-<?= $post['id'] ?>" class="quill-box"><?= $post['content'] ?></div>
                        <textarea name="content" class="quill-content" style="display:none"><?= e($post['content']) ?></textarea>
                    </label>
                    <label class="check"><input type="checkbox" name="is_published" value="1" <?= $post['is_published'] ? 'checked' : '' ?>> Yayında</label>
                    <button>Kaydet</button>
                </form>
                <form method="post" action="<?= admin_url('blogs/delete') ?>" onsubmit="return confirm('Silinsin mi?')"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $post['id'] ?>"><button class="danger">Sil</button></form>
            </details>
        <?php endforeach; ?>

        <details class="edit-row create">
            <summary>+ Yeni blog yazısı ekle</summary>
            <form method="post" action="<?= admin_url('blogs/save') ?>" class="grid-form" enctype="multipart/form-data">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <label>Başlık<input name="title" required></label>
                <label>Slug<input name="slug"></label>
                <label>Yayın tarihi<input name="published_at" value="<?= e(date('Y-m-d H:i:s')) ?>"></label>
                <label>Kapak görsel URL<input name="cover_image" placeholder="URL veya aşağıdan dosya yükleyin"></label>
                <label class="wide">Kapak görseli yükle<input type="file" name="cover_image_file" accept="image/*"></label>
                <label class="wide">Özet<textarea name="summary"></textarea></label>
                <label class="wide">İçerik
                    <div id="quillEditor-new" class="quill-box"></div>
                    <textarea name="content" class="quill-content" style="display:none"></textarea>
                </label>
                <label class="check"><input type="checkbox" name="is_published" value="1" checked> Yayında</label>
                <button>Kaydet</button>
            </form>
        </details>
    </section>
</main>

<style>
.quill-box {
    border: 1px solid var(--border);
    border-radius: 0 0 10px 10px;
    min-height: 200px;
    background: var(--bg-input);
    color: var(--text);
}
.ql-toolbar.ql-snow {
    border: 1px solid var(--border);
    border-radius: 10px 10px 0 0;
    background: var(--bg-panel);
}
.ql-container.ql-snow { border: none; }
.ql-editor { min-height: 180px; font-size: 15px; line-height: 1.6; }
[data-theme="dark"] .ql-snow .ql-stroke { stroke: var(--text-muted); }
[data-theme="dark"] .ql-snow .ql-fill { fill: var(--text-muted); }
[data-theme="dark"] .ql-snow .ql-picker-label { color: var(--text-muted); }
</style>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toolbarOpts = [
        [{ header: [2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['blockquote', 'link', 'image'],
        ['clean']
    ];

    document.querySelectorAll('.quill-box').forEach(el => {
        const quill = new Quill(el, { theme: 'snow', modules: { toolbar: toolbarOpts } });
        const form = el.closest('form');
        const hidden = form.querySelector('.quill-content');
        // Load existing content
        if (hidden.value) quill.root.innerHTML = hidden.value;
        form.addEventListener('submit', () => { hidden.value = quill.root.innerHTML; });
    });
});
</script>
