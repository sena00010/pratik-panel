<main class="login-shell">
    <form class="login-card" method="post" action="<?= admin_url('login') ?>">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
        <h1>Giriş Yap</h1>

        <div class="login-toggle" id="roleToggle">
            <label class="active" onclick="setRole('admin')">
                <input type="radio" name="login_role" value="admin" checked>
                <span>🔐 Yönetim Paneli</span>
            </label>
            <label onclick="setRole('blogger')">
                <input type="radio" name="login_role" value="blogger">
                <span>✍️ Blog Yazarı</span>
            </label>
        </div>

        <?php if (!empty($_SESSION['flash'])): ?><p class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></p><?php endif; ?>
        <label>Kullanıcı adı<input name="username" autocomplete="username" required></label>
        <label>Şifre<input type="password" name="password" autocomplete="current-password" required></label>
        <button type="submit">Giriş Yap</button>
    </form>
</main>

<script>
function setRole(role) {
    document.querySelectorAll('#roleToggle label').forEach(l => l.classList.remove('active'));
    document.querySelectorAll('#roleToggle input[value="'+role+'"]').forEach(r => {
        r.checked = true;
        r.closest('label').classList.add('active');
    });
}
</script>
