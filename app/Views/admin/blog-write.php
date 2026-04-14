<?php $adminPath = config('app.admin_path'); ?>

<header class="admin-header">
    <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
    <div class="header-right">
        <a href="<?= admin_url($role === 'blogger' ? '' : '#sec-blog-write') ?>" style="color:var(--text-muted);font-weight:700;font-size:14px;text-decoration:none;margin-right:16px;">← Listeye Dön</a>
        <button class="theme-toggle" onclick="toggleTheme()" title="Tema değiştir" aria-label="Tema değiştir">
            <svg class="icon-sun" viewBox="0 0 24 24" width="20" height="20"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
            <svg class="icon-moon" viewBox="0 0 24 24" width="20" height="20"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
    </div>
</header>

<main class="admin-main" style="max-width: 860px; margin: 0 auto; padding-top: 40px; padding-bottom: 80px;">
    <?php if (!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>

    <section class="panel">
        <h1 style="margin-bottom:24px;font-size:24px;"><?= $post ? '✏️ Yazıyı Düzenle' : '✍️ Yeni Blog Yazısı' ?></h1>

        <form method="post" action="<?= admin_url('blogs/save') ?>" enctype="multipart/form-data" id="blogForm">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= $post ? (int)$post['id'] : '' ?>">
            <input type="hidden" name="redirect_to" value="<?= admin_url($role === 'blogger' ? '' : '#sec-blog-write') ?>">

            <!-- Adım 1: Temel Bilgiler -->
            <div class="editor-step" id="step1">
                <div class="step-header">
                    <div class="step-badge active">1</div>
                    <span>Temel Bilgiler</span>
                </div>
                <div class="editor-fields">
                    <label class="wide">Başlık *<input name="title" required placeholder="Blog yazınızın başlığı" value="<?= e($post['title'] ?? '') ?>"></label>
                    <label>Slug (URL yolu)<input name="slug" placeholder="otomatik-olusturulur" value="<?= e($post['slug'] ?? '') ?>"></label>
                    <label>Yayın Tarihi<input type="datetime-local" name="published_at" value="<?= $post && $post['published_at'] ? str_replace(' ', 'T', $post['published_at']) : date('Y-m-d\TH:i') ?>"></label>
                </div>
            </div>

            <!-- Adım 2: İçerik -->
            <div class="editor-step" id="step2">
                <div class="step-header">
                    <div class="step-badge active">2</div>
                    <span>İçerik</span>
                </div>
                <div class="editor-fields">
                    <label class="wide">Özet<textarea name="summary" rows="2" placeholder="Yazınızın kısa özeti (listeleme ve SEO için)"><?= e($post['summary'] ?? '') ?></textarea></label>
                    <div class="wide">
                        <label>İçerik *</label>
                        <div id="quillEditor" style="min-height:300px;"></div>
                        <textarea name="content" id="f_content" style="display:none"><?= e($post['content'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Adım 3: Medya & SEO -->
            <div class="editor-step" id="step3">
                <div class="step-header">
                    <div class="step-badge active">3</div>
                    <span>Medya & SEO</span>
                </div>
                <div class="editor-fields">
                    <label>Kapak Görsel URL<input name="cover_image" id="f_cover" placeholder="https://..." value="<?= e($post['cover_image'] ?? '') ?>"></label>
                    <label>Kapak Görseli Yükle<input type="file" name="cover_image_file" accept="image/*"></label>
                    <div class="wide" id="coverPreview" style="display:<?= !empty($post['cover_image']) ? 'block' : 'none' ?>;padding-bottom:8px">
                        <img id="coverPreviewImg" src="<?= e($post['cover_image'] ?? '') ?>" style="max-height:140px;border-radius:10px;border:1px solid var(--border)">
                    </div>
                    <label>Meta Başlık (SEO)<input name="meta_title" placeholder="Arama sonuçlarında görünen başlık" maxlength="70" value="<?= e($post['meta_title'] ?? '') ?>"></label>
                    <label>Meta Açıklama (SEO)<input name="meta_description" placeholder="Arama sonuçlarında görünen açıklama" maxlength="160" value="<?= e($post['meta_description'] ?? '') ?>"></label>
                </div>
            </div>

            <!-- Kaydet -->
            <div class="editor-actions">
                <label class="check" style="margin-right:auto"><input type="checkbox" name="is_published" value="1" <?= (!$post || !empty($post['is_published'])) ? 'checked' : '' ?>> Yayınla <?= $role === 'admin' ? '(Otomatik Onaylı)' : '' ?></label>
                <button type="submit" class="btn-save">💾 Kaydet</button>
            </div>
        </form>
    </section>
</main>

<style>
/* Editor Steps */
.editor-step {
    border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px;
    overflow: hidden; transition: border-color .2s;
}
.editor-step:focus-within { border-color: rgba(18,200,191,.35); }
.step-header {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; background: var(--bg-input); font-weight: 700; font-size: 14px; color: var(--text-muted);
}
.step-badge {
    width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 900; background: var(--border); color: var(--text-muted);
}
.step-badge.active { background: var(--brand); color: #021018; }
.editor-fields {
    display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; padding: 18px;
}
.editor-fields .wide { grid-column: 1 / -1; }
.editor-fields label {
    display: grid; gap: 8px; color: var(--text-muted); font-weight: 600; font-size: 14px;
}
.editor-fields input, .editor-fields textarea {
    width: 100%; border: 1px solid var(--border); border-radius: 10px;
    background: var(--bg-input); color: var(--text); padding: 12px 14px;
    font: inherit; font-size: 15px; transition: border-color .2s, box-shadow .2s;
}
.editor-fields input:focus, .editor-fields textarea:focus {
    outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-glow);
}
.editor-fields textarea { min-height: 80px; resize: vertical; }
.editor-actions {
    display: flex; align-items: center; gap: 12px; padding: 18px 0; border-top: 1px solid var(--border);
    margin-top: 8px;
}
.editor-actions .check { display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--text-muted); font-size: 14px; }
.editor-actions .check input { width: auto; }
.btn-save {
    border: 0; border-radius: 10px; background: var(--brand); color: #021018;
    padding: 14px 32px; font-weight: 800; font-size: 15px; cursor: pointer;
    transition: transform .15s, box-shadow .15s;
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 20px var(--brand-glow); }

/* Quill Editor */
#quillEditor {
    border: 1px solid var(--border); border-radius: 0 0 10px 10px;
    min-height: 380px; background: var(--bg-input); color: var(--text);
}
.ql-toolbar.ql-snow {
    border: 1px solid var(--border) !important; border-radius: 10px 10px 0 0 !important;
    background: var(--bg-panel) !important;
}
.ql-container.ql-snow { border: none !important; }
.ql-editor { min-height: 360px; font-size: 15px; line-height: 1.7; color: var(--text); }
[data-theme="dark"] .ql-snow .ql-stroke { stroke: var(--text-muted) !important; }
[data-theme="dark"] .ql-snow .ql-fill { fill: var(--text-muted) !important; }
[data-theme="dark"] .ql-snow .ql-picker-label { color: var(--text-muted) !important; }
[data-theme="dark"] .ql-snow .ql-picker-options { background: var(--bg-panel) !important; border-color: var(--border) !important; }

@media (max-width: 760px) {
    .editor-fields { grid-template-columns: 1fr; }
}
</style>

<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
let quill;
document.addEventListener('DOMContentLoaded', function() {
    quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Yazınızı buraya yazın...',
        modules: { toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            [{ align: [] }],
            ['blockquote', 'link', 'image', 'video'],
            ['clean']
        ]}
    });

    const existingContent = document.getElementById('f_content').value;
    if(existingContent) {
        quill.root.innerHTML = existingContent;
    }

    document.getElementById('blogForm').addEventListener('submit', function() {
        document.getElementById('f_content').value = quill.root.innerHTML;
    });

    // Cover preview
    document.getElementById('f_cover').addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            document.getElementById('coverPreviewImg').src = url;
            document.getElementById('coverPreview').style.display = 'block';
        } else {
            document.getElementById('coverPreview').style.display = 'none';
        }
    });

    // Document title
    document.title = <?= json_encode($post ? 'Düzenle: ' . $post['title'] : 'Yeni Blog Yazısı', JSON_UNESCAPED_UNICODE) ?> + ' - Yönetim';
});
</script>
