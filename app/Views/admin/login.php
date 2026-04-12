<main class="login-shell">
    <form class="login-card" method="post" action="<?= admin_url('login') ?>">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
        <h1>Yönetim Paneli</h1>
        <?php if (!empty($_SESSION['flash'])): ?><p class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></p><?php endif; ?>
        <label>Kullanıcı adı<input name="username" autocomplete="username" required></label>
        <label>Şifre<input type="password" name="password" autocomplete="current-password" required></label>
        <button type="submit">Giriş Yap</button>
    </form>
</main>
