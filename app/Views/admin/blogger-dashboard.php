<?php $adminPath = config('app.admin_path'); ?>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.ql-container { min-height: 200px; background: #fff; border-radius: 0 0 8px 8px; }
.ql-toolbar { border-radius: 8px 8px 0 0; background: #f8fafc; }
</style>

<div class="admin-section">
    <h2>📝 Blog Yazılarım</h2>
    <p style="color:#8fa0b4;margin:0 0 24px;">Sadece size ait blog yazılarını görebilir ve düzenleyebilirsiniz.</p>
    <button type="button" class="admin-btn" onclick="openBlogForm()">+ Yeni Blog Yazısı</button>

    <div id="blogForm" style="display:none;margin-top:18px;padding:24px;border:1px solid #223144;border-radius:8px;background:#0d1c2b;">
        <form method="POST" action="<?= $adminPath ?>/blogs/save" enctype="multipart/form-data">
            <?= csrf() ?>
            <input type="hidden" name="id" id="blog_id">
            <div style="display:grid;gap:14px;">
                <div>
                    <label>Başlık</label>
                    <input name="title" id="blog_title" required style="width:100%;padding:10px 14px;border:1px solid #334256;border-radius:8px;background:#151f2d;color:#fff;font-size:15px;">
                </div>
                <div>
                    <label>Slug (opsiyonel)</label>
                    <input name="slug" id="blog_slug" style="width:100%;padding:10px 14px;border:1px solid #334256;border-radius:8px;background:#151f2d;color:#fff;font-size:15px;">
                </div>
                <div>
                    <label>Özet</label>
                    <textarea name="summary" id="blog_summary" rows="2" style="width:100%;padding:10px 14px;border:1px solid #334256;border-radius:8px;background:#151f2d;color:#fff;font-size:15px;resize:vertical;"></textarea>
                </div>
                <div>
                    <label>Kapak Görseli (URL veya dosya)</label>
                    <input name="cover_image" id="blog_cover" placeholder="https://..." style="width:100%;padding:10px 14px;border:1px solid #334256;border-radius:8px;background:#151f2d;color:#fff;font-size:15px;margin-bottom:8px;">
                    <input type="file" name="cover_image_file" accept="image/*" style="color:#8fa0b4;">
                </div>
                <div>
                    <label>İçerik</label>
                    <div id="quillEditor" style="border:1px solid #334256;border-radius:8px;background:#fff;min-height:200px;"></div>
                    <textarea name="content" id="blog_content" style="display:none;"></textarea>
                </div>
                <div style="display:flex;gap:12px;">
                    <div>
                        <label>Yayın Tarihi</label>
                        <input type="datetime-local" name="published_at" id="blog_pubdate" style="padding:10px 14px;border:1px solid #334256;border-radius:8px;background:#151f2d;color:#fff;font-size:15px;">
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;padding-top:22px;">
                        <input type="checkbox" name="is_published" id="blog_pub" value="1" checked>
                        <label for="blog_pub" style="margin:0;">Yayınla</label>
                    </div>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" class="admin-btn">Kaydet</button>
                    <button type="button" class="admin-btn" style="background:#334256;" onclick="document.getElementById('blogForm').style.display='none'">İptal</button>
                </div>
            </div>
        </form>
    </div>

    <table class="admin-table" style="margin-top:18px;">
        <thead><tr><th>ID</th><th>Başlık</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($posts as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= e($p['title']) ?></td>
                <td><?= $p['is_published'] ? '<span style="color:#05ad71">Yayında</span>' : '<span style="color:#e5a019">Taslak</span>' ?></td>
                <td><?= e($p['published_at']) ?></td>
                <td>
                    <button class="admin-btn admin-btn--sm" onclick='editBlog(<?= json_encode($p, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE) ?>)'>Düzenle</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
let quill;
document.addEventListener('DOMContentLoaded', function() {
    quill = new Quill('#quillEditor', {
        theme: 'snow',
        modules: { toolbar: [
            [{ header: [2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['blockquote', 'link', 'image'],
            ['clean']
        ]}
    });
    document.querySelector('#blogForm form').addEventListener('submit', function() {
        document.getElementById('blog_content').value = quill.root.innerHTML;
    });
});

function openBlogForm() {
    document.getElementById('blogForm').style.display = 'block';
    document.getElementById('blog_id').value = '';
    document.getElementById('blog_title').value = '';
    document.getElementById('blog_slug').value = '';
    document.getElementById('blog_summary').value = '';
    document.getElementById('blog_cover').value = '';
    document.getElementById('blog_pub').checked = true;
    quill.root.innerHTML = '';
}

function editBlog(p) {
    document.getElementById('blogForm').style.display = 'block';
    document.getElementById('blog_id').value = p.id;
    document.getElementById('blog_title').value = p.title;
    document.getElementById('blog_slug').value = p.slug;
    document.getElementById('blog_summary').value = p.summary;
    document.getElementById('blog_cover').value = p.cover_image || '';
    document.getElementById('blog_pub').checked = !!parseInt(p.is_published);
    document.getElementById('blog_pubdate').value = (p.published_at || '').replace(' ', 'T');
    quill.root.innerHTML = p.content || '';
}
</script>
