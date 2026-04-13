<?php $seo = $seo ?? ['meta_title' => 'Yönetim']; ?>
<!doctype html>
<html lang="tr" data-theme="dark">
<head>
    <script>document.documentElement.setAttribute('data-theme', localStorage.getItem('admin-theme') || 'dark');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['meta_title'] ?? 'Yönetim') ?> · Pratik Gümrük</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800;900&family=Inter+Tight:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>?v=20260413x">
</head>
<body>
    <?= $content ?>

    <!-- Modal for custom confirm -->
    <div id="pg-modal" class="pg-modal">
        <div class="pg-modal-content">
            <h3 id="pg-modal-title">Emin misiniz?</h3>
            <p id="pg-modal-text">Bu işlemi yapmak istediğinize emin misiniz?</p>
            <div class="pg-modal-actions">
                <button id="pg-modal-cancel" class="btn-cancel">İptal</button>
                <button id="pg-modal-confirm" class="btn-confirm">Tamam</button>
            </div>
        </div>
    </div>

    <script>
    function toggleTheme() {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const newTheme = isDark ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('admin-theme', newTheme);
    }

    function pgConfirm(text, onConfirm, title = 'Onay Gerekli') {
        const modal = document.getElementById('pg-modal');
        document.getElementById('pg-modal-title').textContent = title;
        document.getElementById('pg-modal-text').textContent = text;
        
        const btnCancel = document.getElementById('pg-modal-cancel');
        const btnConfirm = document.getElementById('pg-modal-confirm');
        
        const cleanup = () => {
            modal.classList.remove('active');
            btnCancel.onclick = null;
            btnConfirm.onclick = null;
        };

        btnCancel.onclick = cleanup;
        btnConfirm.onclick = () => {
            cleanup();
            onConfirm();
        };

        modal.classList.add('active');
    }
    </script>
</body>
</html>
