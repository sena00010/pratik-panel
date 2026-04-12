<footer class="footer">
    <div>
        <a class="brand" href="<?= url('/') ?>"><span>pratik</span><strong>gümrük</strong></a>
        <p>Gümrük profesyonelleri için yapay zeka destekli platform. GTİP tespitinden beyanname hazırlamaya kadar tüm süreçler.</p>
        <div class="badges"><span>KVKK Uyumlu</span><span>AES-256</span><span>TR Sunucu</span></div>
    </div>
    <nav><strong>MODÜLLER</strong><?php foreach (array_slice($modules ?? [], 0, 6) as $module): ?><a href="<?= url('/modul/' . $module['slug']) ?>"><?= e($module['title']) ?></a><?php endforeach; ?></nav>
    <nav><strong>KAYNAKLAR</strong><a href="#">Gümrük Kanunu</a><a href="#">Tarife Cetveli</a><a href="#">BTB Sorgula</a><a href="#">Mevzuat Takibi</a><a href="<?= url('/blog') ?>">Blog</a><a href="#sss">Yardım Merkezi</a></nav>
    <nav><strong>ŞİRKET</strong><a href="#">Hakkımızda</a><a href="#fiyatlandirma">Fiyatlandırma</a><a href="#">Demo Talep</a><a href="#">İletişim</a><a href="#">Gizlilik Politikası</a><a href="#">Kullanım Koşulları</a></nav>
    <small>© <?= date('Y') ?> Pratik Gümrük. Tüm hakları saklıdır.</small>
</footer>
