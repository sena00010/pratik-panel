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
            <article style="background:var(--bg-panel);border:1px solid var(--border);border-radius:14px;padding:0;overflow:hidden">
                <div style="display:flex;align-items:center;gap:12px;padding:18px 22px;border-bottom:1px solid var(--border)">
                    <div style="flex:1;min-width:0">
                        <h3 style="margin:0;font-size:16px;font-weight:800;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($pp['title']) ?></h3>
                        <div style="display:flex;align-items:center;gap:8px;margin-top:4px">
                            <span style="font-size:12px;color:var(--text-muted)">by <?= e($pp['author_name'] ?: $pp['author_username'] ?: 'Bilinmiyor') ?></span>
                            <span style="font-size:11px;padding:2px 10px;border-radius:6px;font-weight:700;background:rgba(229,160,25,.15);color:#e5a019">Onay Bekliyor</span>
                            <?php if (!empty($pp['created_at'])): ?>
                            <span style="font-size:11px;color:var(--text-muted)"><?= date('d.m.Y H:i', strtotime($pp['created_at'])) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($pp['cover_image'])): ?>
                    <img src="<?= e($pp['cover_image']) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:10px;border:1px solid var(--border);flex-shrink:0" alt="">
                    <?php endif; ?>
                </div>

                <?php if (!empty($pp['meta_title']) || !empty($pp['meta_description'])): ?>
                <div style="padding:12px 22px;background:rgba(59,130,246,.05);border-bottom:1px solid var(--border);font-size:12px">
                    <strong style="color:var(--brand)">SEO:</strong>
                    <?php if (!empty($pp['meta_title'])): ?><span style="color:var(--text-muted);margin-left:6px">Title: <?= e($pp['meta_title']) ?></span><?php endif; ?>
                    <?php if (!empty($pp['meta_description'])): ?><span style="color:var(--text-muted);margin-left:12px">Desc: <?= e(mb_substr($pp['meta_description'], 0, 80)) ?>...</span><?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($pp['summary'])): ?>
                <div style="padding:12px 22px;border-bottom:1px solid var(--border)">
                    <p style="margin:0;font-size:13px;color:var(--text-muted)"><strong>Özet:</strong> <?= e($pp['summary']) ?></p>
                </div>
                <?php endif; ?>

                <div style="padding:16px 22px;max-height:350px;overflow-y:auto;font-size:14px;line-height:1.7;color:var(--text)">
                    <?= $pp['content'] ?>
                </div>

                <div style="display:flex;gap:10px;padding:16px 22px;border-top:1px solid var(--border);background:rgba(0,0,0,.02)">
                    <form method="post" action="<?= admin_url('blogs/approve') ?>"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$pp['id'] ?>"><button style="border:0;border-radius:10px;background:#05ad71;color:#fff;padding:12px 28px;font-weight:800;font-size:13px;cursor:pointer;transition:transform .15s,box-shadow .15s" onmouseover="this.style.transform='scale(1.04)';this.style.boxShadow='0 4px 16px rgba(5,173,113,.3)'" onmouseout="this.style.transform='';this.style.boxShadow=''">✅ Onayla & Yayınla</button></form>
                    <form method="post" action="<?= admin_url('blogs/reject') ?>"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$pp['id'] ?>"><button style="border:0;border-radius:10px;background:var(--danger);color:#fff;padding:12px 28px;font-weight:800;font-size:13px;cursor:pointer;transition:transform .15s" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform=''">❌ Reddet</button></form>
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
                </tr>
                <?php endforeach; ?>
                <?php if (empty($allPosts)): ?>
                <tr><td colspan="4" style="padding:24px;text-align:center;color:var(--text-muted)">Henüz blog yazısı yok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
