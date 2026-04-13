<?php
function json_lines(?string $value): string {
    $items = json_decode($value ?: '[]', true) ?: [];
    return implode("\n", $items);
}
function pretty_json(string $value): string {
    $decoded = json_decode($value, true);
    return json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?: $value;
}
function hero_fields(array $landingRows): ?array {
    foreach ($landingRows as $row) {
        if ($row['block_key'] === 'hero') return $row;
    }
    return null;
}
$heroRow = hero_fields($landingRows);
$heroData = $heroRow ? json_decode($heroRow['payload'], true) : [];
// Trusted brands
function trusted_fields(array $landingRows): ?array {
    foreach ($landingRows as $row) {
        if ($row['block_key'] === 'trusted') return $row;
    }
    return null;
}
$trustedRow = trusted_fields($landingRows);
$trustedData = $trustedRow ? json_decode($trustedRow['payload'], true) : [];
if (!is_array($trustedData)) $trustedData = [];
?>
<header class="admin-header">
    <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
    <div class="header-right">
        <button class="theme-toggle" onclick="toggleTheme()" title="Tema değiştir" aria-label="Tema değiştir">
            <svg class="icon-sun" viewBox="0 0 24 24" width="20" height="20"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
            <svg class="icon-moon" viewBox="0 0 24 24" width="20" height="20"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
        <form method="post" action="<?= admin_url('logout') ?>"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><button class="btn-logout">Çıkış</button></form>
    </div>
</header>
<main class="admin-main">
    <?php if (!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
    <h1>İçerik Yönetimi</h1>

    <!-- ========== LANDING - HERO (Structured Inputs) ========== -->
    <?php if ($heroRow): ?>
    <section class="panel">
        <h2>🎯 Hero Alanı</h2>
        <form method="post" action="<?= admin_url('landing/save') ?>" class="grid-form">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <label>Üst etiket (pill)
                <input name="landing_fields[<?= (int)$heroRow['id'] ?>][pill]" value="<?= e($heroData['pill'] ?? '') ?>">
            </label>
            <label>Başlık (öncesi)
                <input name="landing_fields[<?= (int)$heroRow['id'] ?>][title_before]" value="<?= e($heroData['title_before'] ?? '') ?>">
            </label>
            <label>Başlık (vurgulu)
                <input name="landing_fields[<?= (int)$heroRow['id'] ?>][title_accent]" value="<?= e($heroData['title_accent'] ?? '') ?>">
            </label>
            <label>Başlık (sonrası)
                <input name="landing_fields[<?= (int)$heroRow['id'] ?>][title_after]" value="<?= e($heroData['title_after'] ?? '') ?>">
            </label>
            <label class="wide">Açıklama
                <textarea name="landing_fields[<?= (int)$heroRow['id'] ?>][description]" rows="3"><?= e($heroData['description'] ?? '') ?></textarea>
            </label>
            <label>Birincil buton metni
                <input name="landing_fields[<?= (int)$heroRow['id'] ?>][primary_button]" value="<?= e($heroData['primary_button'] ?? '') ?>">
            </label>
            <label>İkincil buton metni
                <input name="landing_fields[<?= (int)$heroRow['id'] ?>][secondary_button]" value="<?= e($heroData['secondary_button'] ?? '') ?>">
            </label>
            <div class="wide"><button>Hero alanını kaydet</button></div>
        </form>
    </section>
    <?php endif; ?>

    <!-- ========== KULLANANLAR ========== -->
    <?php if ($trustedRow): ?>
    <section class="panel">
        <h2>🏢 Kullananlar</h2>
        <p class="help">Ana sayfada "KULLANANLAR" bandında gösterilen şirket isimleri. Ekle/çıkar yaparak yönetebilirsiniz.</p>
        <form method="post" action="<?= admin_url('landing/save') ?>" id="trusted-form">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="payload[<?= (int)$trustedRow['id'] ?>]" id="trusted-json" value="">
            <div style="padding:0 18px 18px">
                <div id="trusted-pills" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px">
                    <?php foreach ($trustedData as $brand): ?>
                        <span class="trusted-pill" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:var(--brand-glow);color:var(--brand);font-weight:700;font-size:14px;border:1px solid rgba(18,200,191,.25)">
                            <?= e($brand) ?>
                            <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:16px;padding:0 2px;font-weight:900">×</button>
                        </span>
                    <?php endforeach; ?>
                </div>
                <div style="display:flex;gap:8px">
                    <input type="text" id="trusted-input" placeholder="Yeni şirket adı..." style="flex:1;padding:12px 14px;border:1px solid var(--border);border-radius:10px;background:var(--bg-input);color:var(--text);font:inherit;font-size:14px">
                    <button type="button" onclick="addTrusted()" style="padding:12px 20px;border:0;border-radius:10px;background:var(--brand);color:#021018;font-weight:800;font-size:14px;cursor:pointer">Ekle</button>
                </div>
            </div>
            <div style="padding:0 18px 18px"><button type="submit" style="padding:14px 20px;border:0;border-radius:10px;background:var(--brand);color:#021018;font-weight:800;font-size:15px;cursor:pointer;width:100%">Kullananları Kaydet</button></div>
        </form>
        <script>
        function addTrusted() {
            const input = document.getElementById('trusted-input');
            const name = input.value.trim();
            if (!name) return;
            const pill = document.createElement('span');
            pill.className = 'trusted-pill';
            pill.style.cssText = 'display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:var(--brand-glow);color:var(--brand);font-weight:700;font-size:14px;border:1px solid rgba(18,200,191,.25)';
            pill.innerHTML = name + ' <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:16px;padding:0 2px;font-weight:900">×</button>';
            document.getElementById('trusted-pills').appendChild(pill);
            input.value = '';
            input.focus();
        }
        document.getElementById('trusted-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); addTrusted(); }
        });
        document.getElementById('trusted-form').addEventListener('submit', function() {
            const pills = document.querySelectorAll('#trusted-pills .trusted-pill');
            const brands = Array.from(pills).map(p => p.firstChild.textContent.trim());
            document.getElementById('trusted-json').value = JSON.stringify(brands);
        });
        </script>
    </section>
    <?php endif; ?>

    <!-- ========== LANDING - Diğer bloklar ========== -->
    <section class="panel">
        <h2>📄 Landing Sayfası Blokları</h2>
        <p class="help">Hero dışındaki alanları buradan düzenleyebilirsiniz. JSON formatı bozulursa kayıt yapılmaz.</p>
        <form method="post" action="<?= admin_url('landing/save') ?>" class="landing-form">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <?php foreach ($landingRows as $row): ?>
                <?php if ($row['block_key'] === 'hero' || $row['block_key'] === 'trusted') continue; ?>
                <details class="edit-row">
                    <summary><?= e($row['title']) ?> <small><?= e($row['block_key']) ?></small></summary>
                    <label><?= e($row['title']) ?><textarea name="payload[<?= (int) $row['id'] ?>]" rows="14" spellcheck="false"><?= e(pretty_json($row['payload'])) ?></textarea></label>
                </details>
            <?php endforeach; ?>
            <button>Landing bloklarını kaydet</button>
        </form>
    </section>

    <!-- ========== MODÜLLER ========== -->
    <section class="panel">
        <h2>📦 Modüller</h2>
        <?php foreach ($modules as $module): ?>
            <details class="edit-row">
                <summary><?= e($module['title']) ?> <small><?= e($module['slug']) ?></small></summary>
                <form method="post" action="<?= admin_url('modules/save') ?>" class="grid-form">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $module['id'] ?>">
                    <label>Başlık<input name="title" value="<?= e($module['title']) ?>" required></label>
                    <label>Slug<input name="slug" value="<?= e($module['slug']) ?>"></label>
                    <label>Etiket<input name="eyebrow" value="<?= e($module['eyebrow']) ?>"></label>
                    <label>İkon<input name="icon" value="<?= e($module['icon']) ?>"></label>
                    <label>Renk<input type="color" name="accent" value="<?= e($module['accent']) ?>"></label>
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
        <details class="edit-row create"><summary>+ Yeni modül ekle</summary><form method="post" action="<?= admin_url('modules/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><label>Başlık<input name="title" required></label><label>Slug<input name="slug"></label><label>Etiket<input name="eyebrow" value="MODÜL"></label><label>İkon<input name="icon" value="□"></label><label>Renk<input type="color" name="accent" value="#12c8bf"></label><label>Sıra<input type="number" name="sort_order" value="99"></label><label class="wide">Özet<textarea name="summary"></textarea></label><label class="wide">Özellikler<textarea name="features"></textarea></label><label class="wide">Detay<textarea name="detail_content" rows="7"></textarea></label><label class="check"><input type="checkbox" name="is_active" value="1" checked> Yayında</label><button>Kaydet</button></form></details>
    </section>

    <!-- ========== SSS ========== -->
    <section class="panel">
        <h2>❓ SSS</h2>
        <?php foreach ($faqs as $faq): ?>
            <details class="edit-row"><summary><?= e($faq['question']) ?></summary><form method="post" action="<?= admin_url('faqs/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $faq['id'] ?>"><label class="wide">Soru<input name="question" value="<?= e($faq['question']) ?>" required></label><label class="wide">Cevap<textarea name="answer"><?= e($faq['answer']) ?></textarea></label><label>Sıra<input type="number" name="sort_order" value="<?= (int) $faq['sort_order'] ?>"></label><label class="check"><input type="checkbox" name="is_active" value="1" <?= $faq['is_active'] ? 'checked' : '' ?>> Yayında</label><button>Kaydet</button></form><form method="post" action="<?= admin_url('faqs/delete') ?>" onsubmit="return confirm('Silinsin mi?')"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $faq['id'] ?>"><button class="danger">Sil</button></form></details>
        <?php endforeach; ?>
        <details class="edit-row create"><summary>+ Yeni SSS ekle</summary><form method="post" action="<?= admin_url('faqs/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><label class="wide">Soru<input name="question" required></label><label class="wide">Cevap<textarea name="answer"></textarea></label><label>Sıra<input type="number" name="sort_order" value="99"></label><label class="check"><input type="checkbox" name="is_active" value="1" checked> Yayında</label><button>Kaydet</button></form></details>
    </section>

    <!-- ========== BLOG ========== -->
    <section class="panel">
        <h2>✍️ Blog</h2>
        <p class="help">İçerik alanında paragraflar arasında boş satır bırakın. Her boş satır yeni paragraf oluşturur.</p>
        <?php foreach ($posts as $post): ?>
            <details class="edit-row">
                <summary><?= e($post['title']) ?> <small><?= e($post['slug']) ?></small></summary>
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
                    <label class="wide">İçerik <small style="color:var(--text-muted);font-weight:500">(Paragraflar arası boş satır bırakın)</small><textarea name="content" rows="14"><?= e($post['content']) ?></textarea></label>
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
                <label class="wide">İçerik <small style="color:var(--text-muted);font-weight:500">(Paragraflar arası boş satır bırakın)</small><textarea name="content" rows="14" placeholder="Birinci paragraf buraya...&#10;&#10;İkinci paragraf buraya...&#10;&#10;Üçüncü paragraf buraya..."></textarea></label>
                <label class="check"><input type="checkbox" name="is_published" value="1" checked> Yayında</label>
                <button>Kaydet</button>
            </form>
        </details>
    </section>

    <!-- ========== SEO ========== -->
    <section class="panel">
        <h2>🔍 SEO</h2>
        <?php foreach ($seoRows as $row): ?>
            <details class="edit-row"><summary><?= e($row['page']) ?> <?= $row['slug'] ? '· ' . e($row['slug']) : '' ?></summary><form method="post" action="<?= admin_url('seo/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><label>Sayfa kodu<input name="page" value="<?= e($row['page']) ?>" required></label><label>Slug<input name="slug" value="<?= e($row['slug']) ?>"></label><label class="wide">Meta başlık<input name="meta_title" value="<?= e($row['meta_title']) ?>"></label><label class="wide">Meta açıklama<textarea name="meta_description"><?= e($row['meta_description']) ?></textarea></label><label class="wide">Anahtar kelimeler<input name="meta_keywords" value="<?= e($row['meta_keywords']) ?>"></label><label class="wide">OG görsel<input name="og_image" value="<?= e($row['og_image']) ?>"></label><button>Kaydet</button></form></details>
        <?php endforeach; ?>
        <details class="edit-row create"><summary>+ Yeni SEO kaydı ekle</summary><form method="post" action="<?= admin_url('seo/save') ?>" class="grid-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><label>Sayfa kodu<input name="page" placeholder="home, blog, module, blog_detail" required></label><label>Slug<input name="slug"></label><label class="wide">Meta başlık<input name="meta_title"></label><label class="wide">Meta açıklama<textarea name="meta_description"></textarea></label><label class="wide">Anahtar kelimeler<input name="meta_keywords"></label><label class="wide">OG görsel<input name="og_image"></label><button>Kaydet</button></form></details>
    </section>

    <!-- ========== ADMIN KULLANICILARI ========== -->
    <section class="panel">
        <h2>👥 Admin Kullanıcıları</h2>
        <div class="admin-users-list">
            <?php foreach ($adminUsers as $u): ?>
                <div class="admin-user-row">
                    <div class="admin-user-info">
                        <span class="admin-user-avatar"><?= strtoupper(mb_substr($u['username'], 0, 1, 'UTF-8')) ?></span>
                        <div>
                            <strong><?= e($u['username']) ?></strong>
                            <small><?= e($u['created_at']) ?></small>
                        </div>
                    </div>
                    <?php if ((int)$u['id'] !== (int)($_SESSION['admin_id'] ?? 0)): ?>
                    <form method="post" action="<?= admin_url('admins/delete') ?>" onsubmit="return confirm('Bu admini silmek istediğinize emin misiniz?')">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                        <button class="danger-sm">Sil</button>
                    </form>
                    <?php else: ?>
                    <span class="badge-you">Siz</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <details class="edit-row create">
            <summary>+ Yeni admin ekle</summary>
            <form method="post" action="<?= admin_url('admins/save') ?>" class="grid-form">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <label>Kullanıcı adı<input name="username" required autocomplete="off"></label>
                <label>Şifre<input type="password" name="password" required autocomplete="new-password"></label>
                <button>Admin oluştur</button>
            </form>
        </details>
    </section>
</main>

<script>
function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    const next = current === 'light' ? 'dark' : 'light';
    html.setAttribute('data-theme', next);
    localStorage.setItem('admin-theme', next);
}
(function() {
    const saved = localStorage.getItem('admin-theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
})();
</script>
