<?php $adminPath = config('app.admin_path'); ?>

<header class="admin-header">
    <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
    <div class="header-right">
        <div style="display:flex;align-items:center;gap:10px">
            <?php if (!empty($_SESSION['admin_profile_photo'])): ?>
            <img src="<?= e($_SESSION['admin_profile_photo']) ?>" alt="Profil" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
            <?php else: ?>
            <div style="width:32px;height:32px;border-radius:50%;background:rgba(18,200,191,.15);color:#12c8bf;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:14px"><?= mb_strtoupper(mb_substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div>
            <?php endif; ?>
            <span style="color:var(--text-muted);font-size:14px;font-weight:700"><?= e($_SESSION['admin_username'] ?? '') ?></span>
        </div>
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

    <!-- Profil Bölümü -->
    <section class="panel" style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;padding:24px;margin-bottom:24px">
        <div style="position:relative;width:80px;height:80px;flex-shrink:0">
            <?php if (!empty($_SESSION['admin_profile_photo'])): ?>
            <img src="<?= e($_SESSION['admin_profile_photo']) ?>" id="profile-img-preview" alt="Profil" style="width:100%;height:100%;border-radius:50%;object-fit:cover;border:3px solid var(--border)">
            <?php else: ?>
            <div id="profile-img-preview-blank" style="width:100%;height:100%;border-radius:50%;background:rgba(18,200,191,.15);color:#12c8bf;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:32px"><?= mb_strtoupper(mb_substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div>
            <img src="" id="profile-img-preview" alt="Profil" style="display:none;width:100%;height:100%;border-radius:50%;object-fit:cover;border:3px solid var(--border)">
            <?php endif; ?>
        </div>
        <div style="flex:1;min-width:250px">
            <h2 style="font-size:18px;margin:0 0 6px 0;border:0;padding:0">Profiliniz</h2>
            <p style="margin:0 0 16px 0;font-size:13px;color:var(--text-muted);line-height:1.5">Blog sayfasında adınızla birlikte görünecek bir fotoğraf yükleyebilirsiniz (JPEG, PNG).</p>
            <form method="post" action="<?= admin_url('profile/save') ?>" id="profile-form" style="display:flex;gap:12px;align-items:center">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="profile_photo" id="profile_photo_val" value="<?= e($_SESSION['admin_profile_photo'] ?? '') ?>">
                <label style="cursor:pointer;background:var(--bg-input);padding:10px 16px;border-radius:8px;font-weight:700;font-size:13px;border:1px solid var(--border);transition:border-color .15s" onmouseover="this.style.borderColor='var(--brand)'" onmouseout="this.style.borderColor='var(--border)'">
                    📷 Fotoğraf Seç
                    <input type="file" style="display:none" accept="image/jpeg,image/png,image/webp" onchange="uploadProfilePhoto(this)">
                </label>
                <div id="profile-upload-loading" style="display:none;font-size:13px;font-weight:700;color:var(--brand)">⏳ Yükleniyor...</div>
                <button class="btn" style="min-width:0;padding:10px 24px;font-size:13px">Kaydet</button>
            </form>
        </div>
    </section>

    <!-- Yazılar Listesi -->
    <section class="panel" id="sec-posts">
        <h2>📋 Yazılarım</h2>
        <p class="help">Yazılarınızın durumunu buradan takip edebilirsiniz. <strong>Onay Bekliyor</strong> durumundaki yazılar admin onayından sonra yayınlanır.</p>

        <?php if (empty($posts)): ?>
            <div style="text-align:center;padding:40px 20px;color:var(--text-muted)">
                <p style="font-size:48px;margin-bottom:12px">✍️</p>
                <p style="font-size:16px;font-weight:700">Henüz yazınız yok</p>
                <p style="font-size:14px;margin-top:4px">Aşağıdan ilk blog yazınızı oluşturun.</p>
            </div>
        <?php else: ?>
            <div class="blog-posts-list">
                <?php foreach ($posts as $post):
                    $statusMap = [
                        'draft' => ['label' => 'Taslak', 'color' => '#94a3b8', 'bg' => 'rgba(148,163,184,.12)'],
                        'pending' => ['label' => 'Onay Bekliyor', 'color' => '#e5a019', 'bg' => 'rgba(229,160,25,.12)'],
                        'approved' => ['label' => 'Yayında', 'color' => '#05ad71', 'bg' => 'rgba(5,173,113,.12)'],
                        'rejected' => ['label' => 'Reddedildi', 'color' => '#ff5d6c', 'bg' => 'rgba(255,93,108,.12)'],
                    ];
                    $s = $statusMap[$post['status'] ?? 'draft'] ?? $statusMap['draft'];
                ?>
                <div class="blog-post-item">
                    <div class="blog-post-info">
                        <div class="blog-post-title">
                            <strong><?= e($post['title']) ?></strong>
                            <span class="blog-status-badge" style="background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>"><?= $s['label'] ?></span>
                        </div>
                        <div class="blog-post-meta">
                            <span>/blog/<?= e($post['slug']) ?></span>
                            <span>·</span>
                            <span><?= e($post['published_at'] ?? $post['created_at']) ?></span>
                        </div>
                    </div>
                    <div class="blog-post-actions">
                        <a href="<?= admin_url('blog-write?id=' . $post['id']) ?>" class="btn-edit" style="text-decoration:none;display:inline-block">Düzenle</a>
                        <form method="post" action="<?= admin_url('blogs/delete') ?>" onsubmit="event.preventDefault(); pgConfirm('Bu yazıyı silmek istediğinize emin misiniz?', () => this.submit(), 'Yazıyı Sil')" style="display:inline">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
                            <button class="btn-delete">Sil</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <a href="<?= admin_url('blog-write') ?>" class="btn-new-post" style="text-align:center;text-decoration:none;display:block">+ Yeni Blog Yazısı</a>
    </section>
</main>

<style>
/* --- Blogger Dashboard Specific --- */
.blog-posts-list { display: grid; gap: 8px; margin-bottom: 16px; }
.blog-post-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 18px; border: 1px solid var(--border); border-radius: 10px;
    background: var(--bg-input); transition: border-color .2s;
}
.blog-post-item:hover { border-color: rgba(18,200,191,.3); }
.blog-post-info { flex: 1; min-width: 0; }
.blog-post-title { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.blog-post-title strong { font-size: 15px; }
.blog-status-badge {
    display: inline-block; padding: 3px 10px; border-radius: 6px;
    font-size: 11px; font-weight: 800; letter-spacing: .02em;
}
.blog-post-meta { margin-top: 4px; font-size: 13px; color: var(--text-muted); display: flex; gap: 6px; }
.blog-post-actions { display: flex; gap: 8px; flex-shrink: 0; margin-left: 12px; }
.btn-edit {
    border: 1px solid var(--brand); background: transparent; color: var(--brand);
    padding: 7px 16px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;
    transition: background .18s, color .18s;
}
.btn-edit:hover { background: var(--brand); color: #021018; }
.btn-delete {
    border: 1px solid var(--danger); background: transparent; color: var(--danger);
    padding: 7px 16px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;
    transition: background .18s, color .18s;
}
.btn-delete:hover { background: var(--danger); color: #fff; }
.btn-new-post {
    display: block; width: 100%; padding: 14px; border: 2px dashed rgba(18,200,191,.4);
    border-radius: 10px; background: transparent; color: var(--brand); font-weight: 800;
    font-size: 15px; cursor: pointer; transition: background .18s, border-color .18s;
}
.btn-new-post:hover { background: var(--brand-glow); border-color: var(--brand); }
.btn-back {
    border: 1px solid var(--border); background: transparent; color: var(--text-muted);
    padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;
    transition: border-color .18s, color .18s;
}
.btn-back:hover { border-color: var(--brand); color: var(--brand); }

@media (max-width: 760px) {
    .blog-post-item { flex-direction: column; align-items: flex-start; gap: 10px; }
    .blog-post-actions { margin-left: 0; }
}
</style>


async function uploadProfilePhoto(input) {
    if(!input.files || input.files.length === 0) return;
    const file = input.files[0];
    const fd = new FormData();
    fd.append('image', file);
    fd.append('_csrf', '<?= e(csrf_token()) ?>');

    document.getElementById('profile-upload-loading').style.display = 'block';
    
    try {
        const res = await fetch('<?= admin_url('upload/image') ?>', { method: 'POST', body: fd});
        const data = await res.json();
        
        if(data.success && data.url) {
            document.getElementById('profile_photo_val').value = data.url;
            document.getElementById('profile-img-preview').src = data.url;
            document.getElementById('profile-img-preview').style.display = 'block';
            let blank = document.getElementById('profile-img-preview-blank');
            if(blank) blank.style.display = 'none';
        } else {
            alert('Yükleme hatası: ' + (data.error || 'Bilinmiyor'));
        }
    } catch(err) {
        alert('Sunucu hatası oluştu!');
    } finally {
        document.getElementById('profile-upload-loading').style.display = 'none';
    }
}
</script>
