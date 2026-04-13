<?php
$statusLabels = [
    'draft' => ['Taslak', 'rgba(100,116,139,.2)', '#94a3b8'],
    'pending' => ['Onay Bekliyor', 'rgba(229,160,25,.15)', '#e5a019'],
    'approved' => ['Yayında', 'rgba(5,173,113,.15)', '#05ad71'],
    'rejected' => ['Reddedildi', 'rgba(239,68,68,.15)', '#ef4444'],
];
?>
<header class="admin-header">
    <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
    <div class="header-right">
        <button class="theme-toggle" onclick="toggleTheme()" title="Tema değiştir" aria-label="Tema değiştir">
            <svg class="icon-sun" viewBox="0 0 24 24" width="20" height="20"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
            <svg class="icon-moon" viewBox="0 0 24 24" width="20" height="20"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
        <a href="<?= admin_url('') ?>" style="color:var(--text-muted);text-decoration:none;font-size:13px;font-weight:700;padding:8px 16px;border-radius:8px;background:var(--bg-panel);border:1px solid var(--border)">← Panele Dön</a>
        <form method="post" action="<?= admin_url('logout') ?>"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><button class="btn-logout">Çıkış</button></form>
    </div>
</header>

<main class="admin-main" style="max-width:1100px;margin:0 auto;padding:32px 24px">
    <?php if (!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>

    <h1 style="margin-bottom:8px">✍️ Blog Onay Kuyruğu</h1>
    <p style="color:var(--text-muted);margin-bottom:32px;font-size:14px">Blog yazarlarının gönderdiği yazıları buradan onaylayabilir veya reddedebilirsiniz.</p>

    <!-- Pending posts -->
    <?php if (empty($pendingPosts)): ?>
        <div style="text-align:center;padding:48px 24px;background:var(--bg-panel);border-radius:16px;border:1px solid var(--border)">
            <div style="font-size:48px;margin-bottom:12px">✅</div>
            <p style="font-size:16px;font-weight:700;color:var(--text)">Onay bekleyen yazı yok</p>
            <p style="font-size:13px;color:var(--text-muted);margin-top:6px">Tüm yazılar onaylanmış durumda.</p>
        </div>
    <?php else: ?>
        <div style="display:grid;gap:16px;margin-bottom:40px">
            <?php foreach ($pendingPosts as $pp): ?>
            <article style="background:var(--bg-panel); border:1px solid var(--border); border-radius:18px; padding:24px; box-shadow:0 8px 30px rgba(0,0,0,0.04); display:flex; flex-direction:column; gap:20px; transition:transform .2s">
                <!-- Header / Meta -->
                <div style="display:flex; justify-content:space-between; align-items:flex-start">
                    <div style="display:flex; align-items:center; gap:12px">
                        <?php if (!empty($pp['author_photo'])): ?>
                            <img src="<?= e($pp['author_photo']) ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid rgba(18,200,191,0.2)">
                        <?php else: ?>
                            <div style="width:40px; height:40px; border-radius:50%; background:rgba(18,200,191,.15); color:#12c8bf; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px"><?= mb_strtoupper(mb_substr($pp['author_name'] ?: $pp['author_username'] ?: 'A', 0, 1)) ?></div>
                        <?php endif; ?>
                        <div>
                            <div style="font-weight:700; color:var(--text); font-size:14px"><?= e($pp['author_name'] ?: $pp['author_username'] ?: 'Bilinmiyor') ?></div>
                            <div style="color:var(--text-muted); font-size:12px; margin-top:2px">
                                <?php if (!empty($pp['created_at'])) echo date('d M Y, H:i', strtotime($pp['created_at'])) . ' · '; ?>
                                <span style="color:#e5a019; font-weight:700">Onay Bekliyor</span>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($pp['meta_title']) || !empty($pp['meta_description'])): ?>
                    <div style="background:rgba(59,130,246,.08); padding:8px 12px; border-radius:8px; font-size:11px; max-width:300px">
                        <strong style="color:var(--brand); display:block; margin-bottom:4px">SEO Verisi</strong>
                        <?php if (!empty($pp['meta_title'])): ?><div style="color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis"><strong>T:</strong> <?= e($pp['meta_title']) ?></div><?php endif; ?>
                        <?php if (!empty($pp['meta_description'])): ?><div style="color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis"><strong>D:</strong> <?= e($pp['meta_description']) ?></div><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Preview Card Layout -->
                <div style="display:grid; grid-template-columns: 240px 1fr; gap:24px; align-items:start">
                    <?php if (!empty($pp['cover_image'])): ?>
                        <div style="width:100%; aspect-ratio:16/10; border-radius:12px; overflow:hidden; background:rgba(0,0,0,0.03); border:1px solid var(--border)">
                            <img src="<?= e($pp['cover_image']) ?>" style="width:100%; height:100%; object-fit:contain" alt="">
                        </div>
                    <?php else: ?>
                        <div style="width:100%; aspect-ratio:16/10; border-radius:12px; background:linear-gradient(135deg, rgba(18,200,191,0.1), rgba(10,22,36,0.05)); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; font-size:40px">
                            📝
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <h3 style="margin:0 0 12px 0; font-size:22px; font-weight:800; color:var(--text); line-height:1.3"><?= e($pp['title']) ?></h3>
                        <p style="margin:0 0 16px 0; font-size:14px; color:var(--text-muted); line-height:1.6; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden"><?= e($pp['summary'] ?: strip_tags($pp['content'])) ?></p>
                        
                        <div style="max-height:140px; overflow-y:auto; padding:12px; background:rgba(0,0,0,0.02); border-radius:8px; border:1px dashed var(--border); font-size:12px; color:var(--text-muted); line-height:1.6">
                            <strong style="display:block; margin-bottom:6px; color:var(--text)">İçerik Önizleme (Ham Temsil):</strong>
                            <?= e(mb_substr(strip_tags($pp['content']), 0, 400)) ?>...
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:8px; padding-top:20px; border-top:1px solid var(--border)">
                    <form method="post" action="<?= admin_url('blogs/reject') ?>"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$pp['id'] ?>"><button type="button" style="border:0; border-radius:8px; background:rgba(255,93,108,0.1); color:var(--danger); padding:10px 20px; font-weight:700; font-size:13px; cursor:pointer; transition:all .15s" onmouseover="this.style.background='var(--danger)'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,93,108,0.1)'; this.style.color='var(--danger)'" onclick="pgConfirm('Bu yazıyı reddetmek istediğinize emin misiniz? Blogger, panelinde bunu görecektir.', () => this.form.submit(), 'Blog Yazısını Reddet')">❌ Reddet</button></form>
                    <form method="post" action="<?= admin_url('blogs/approve') ?>"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$pp['id'] ?>"><button type="submit" style="border:0; border-radius:8px; background:#05ad71; color:#fff; padding:10px 24px; font-weight:800; font-size:13px; cursor:pointer; transition:transform .15s,box-shadow .15s" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(5,173,113,.4)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">✅ Onayla & Yayınla</button></form>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- All posts list -->
    <h2 style="margin-bottom:16px;font-size:18px">📋 Tüm Blog Yazıları</h2>
    <div style="background:var(--bg-panel);border:1px solid var(--border);border-radius:14px;overflow:hidden">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr style="border-bottom:2px solid var(--border)">
                    <th style="text-align:left;padding:14px 16px;font-weight:800;color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.05em">Başlık</th>
                    <th style="text-align:left;padding:14px 16px;font-weight:800;color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.05em">Yazar</th>
                    <th style="text-align:center;padding:14px 16px;font-weight:800;color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.05em">Durum</th>
                    <th style="text-align:right;padding:14px 16px;font-weight:800;color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.05em">Tarih</th>
                    <th style="text-align:right;padding:14px 16px;font-weight:800;color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.05em">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allPosts as $ap): ?>
                <?php $s = $statusLabels[$ap['status'] ?? 'draft'] ?? $statusLabels['draft']; ?>
                <tr style="border-bottom:1px solid var(--border);transition:background .15s" onmouseover="this.style.background='rgba(18,200,191,.03)'" onmouseout="this.style.background=''">
                    <td style="padding:12px 16px;font-weight:700;color:var(--text);max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($ap['title']) ?></td>
                    <td style="padding:12px 16px;color:var(--text-muted)"><?= e($ap['author_name'] ?: $ap['author_username'] ?: '-') ?></td>
                    <td style="padding:12px 16px;text-align:center"><span style="padding:3px 12px;border-radius:6px;font-size:11px;font-weight:800;background:<?= $s[1] ?>;color:<?= $s[2] ?>"><?= $s[0] ?></span></td>
                    <td style="padding:12px 16px;text-align:right;color:var(--text-muted);font-size:12px"><?= !empty($ap['created_at']) ? date('d.m.Y', strtotime($ap['created_at'])) : '-' ?></td>
                    <td style="padding:12px 16px;text-align:right">
                        <?php if (($ap['status'] ?? '') === 'approved' || ($ap['status'] ?? '') === 'pending'): ?>
                        <form method="post" action="<?= admin_url('blogs/reject') ?>" style="display:inline-block"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$ap['id'] ?>"><button type="button" style="border:0;background:rgba(239,68,68,.1);color:#ef4444;border-radius:6px;padding:6px 12px;font-size:11px;font-weight:800;cursor:pointer" onclick="pgConfirm('Bu yazıyı yayından kaldırmak / reddetmek istediğinize emin misiniz?', () => this.form.submit(), 'Yayından Kaldır / Reddet')">❌ Kaldır / Reddet</button></form>
                        <?php endif; ?>
                        <?php if (($ap['status'] ?? '') === 'rejected' || ($ap['status'] ?? '') === 'draft'): ?>
                        <form method="post" action="<?= admin_url('blogs/approve') ?>" style="display:inline-block"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$ap['id'] ?>"><button type="submit" style="border:0;background:rgba(5,173,113,.1);color:#05ad71;border-radius:6px;padding:6px 12px;font-size:11px;font-weight:800;cursor:pointer">✅ Onayla & Yayınla</button></form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($allPosts)): ?>
                <tr><td colspan="5" style="padding:24px;text-align:center;color:var(--text-muted)">Henüz blog yazısı yok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
