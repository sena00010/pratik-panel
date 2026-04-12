<?php
$featureList = [
    ['BTB emsal kararı entegrasyonu', 'ok', 'no'],
    ['Çakışan GTİP sınıflandırma sorusu', 'ok', 'no'],
    ['Ceza risk hesaplama aracı', 'ok', 'no'],
    ['Belge → beyanname otomasyonu', 'ok', 'warn'],
    ["Türkiye'ye özel 12 haneli GTİP", 'ok', 'no'],
    ['Uzman müşavir gözden geçirme', 'ok', 'no'],
    ['TAREKS / BİLGE uyumluluk kontrolü', 'ok', 'no'],
];
$security = [
    ['shield', 'KVKK & GDPR Uyumlu', 'Kişisel ve ticari verileriniz Türk hukuku ve AB veri koruma mevzuatı kapsamında işlenir.'],
    ['lock', 'Uçtan Uca Şifreleme', 'Tüm veriler AES-256 ile şifrelenmiş sunucularda saklanır. Transfer sırasında TLS 1.3 kullanılır.'],
    ['clock', 'Denetim & İzlenebilirlik', 'Tüm kritik işlemler zaman damgalı, değiştirilemez kayıtlarla izlenir.'],
    ['hexagon', 'Model Eğitimi Yok', 'Yüklediğiniz belgeler veya sorularınız AI modellerini eğitmek için kullanılmaz.'],
    ['activity', '%99.9 Uptime SLA', 'Kritik gümrük süreçleriniz kesintisiz çalışır. Bakım pencereleri önceden bildirilir.'],
    ['users', 'Rol Bazlı Erişim', 'Ekip üyelerinize modül ve veri bazında farklı erişim seviyeleri tanımlayın.'],
];
?>
<section class="hero dark-grid" id="ust">
    <div class="hero-inner">
        <span class="pill">Gümrük profesyonelleri için yapay zeka platformu</span>
        <h1>Gümrük işlemlerinizi <span>doğru, hızlı ve güvenli</span> yönetin</h1>
        <p>GTİP tespitinden beyanname hazırlamaya, mevzuat araştırmasından ceza risk analizine kadar tüm gümrük süreçlerini tek platformda yönetin. Resmi kaynak garantisi ile.</p>
        <div class="hero-actions">
            <a class="btn btn-primary btn-large" href="#fiyatlandirma">Ücretsiz Başlayın</a>
            <a class="btn btn-dark-outline btn-large" href="#nasil-calisir">Demo İzleyin →</a>
        </div>
        <div class="terminal-card" aria-label="GTİP asistanı önizleme">
            <div class="window-dots"><span></span><span></span><span></span></div>
            <small>pratikgümrük · GTİP Asistanı</small>
            <div class="search-line">Kompresörsüz evaporatif hava soğutucu, fan + pompa</div>
            <div class="chips"><span>8479.89.97.00.19</span><span>Fasıl 84</span><span>GV: %3.7</span><span>KDV: %20</span><span>İGV: %20</span></div>
            <div class="result-strip">
                <div><b>8479.89.97.00.19</b><span>Güven skoru: %87</span></div>
                <div><b>%43.7</b><span>CIF üzerinden</span></div>
                <div><b>4 karar</b><span>Bakanlık onayı</span></div>
            </div>
        </div>
    </div>
</section>

<section class="trusted">
    <strong>KULLANANLAR</strong>
    <span>ZORLU HOLDİNG</span><span>ÜLKER</span><span>ARÇELİK</span><span>YILDIRIM GROUP</span><span>EKOL LOJİSTİK</span><span>ATA TAŞIMACILIK</span>
</section>

<section class="section light-section" id="moduller">
    <div class="section-head">
        <span class="eyebrow">PLATFORM MODÜLLERİ</span>
        <h2>Gümrük sürecinizin her adımı için araç</h2>
        <p>Her modül bağımsız çalışır; dilediğiniz modülden başlayın, ihtiyacınıza göre genişletin.</p>
    </div>
    <div class="module-grid">
        <?php foreach ($modules as $module): ?>
            <?php $features = json_decode($module['features'] ?: '[]', true) ?: []; ?>
            <article class="module-card" style="--accent: <?= e($module['accent']) ?>">
                <div class="module-icon"><?= module_icon_svg($module['slug']) ?></div>
                <span><?= e($module['eyebrow']) ?></span>
                <h3><?= e($module['title']) ?></h3>
                <p><?= e($module['summary']) ?></p>
                <ul>
                    <?php foreach (array_slice($features, 0, 4) as $feature): ?><li><?= e($feature) ?></li><?php endforeach; ?>
                </ul>
                <a href="<?= url('/modul/' . $module['slug']) ?>">Modülü Keşfet →</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section dark-section" id="nasil-calisir">
    <div class="split">
        <div>
            <span class="eyebrow">NASIL ÇALIŞIR</span>
            <h2>AI + uzman bilgisi = doğru sonuç</h2>
            <p>Pratik Gümrük, ham AI tahminini değil, resmi kaynaklarla doğrulanmış, uzman onaylı sonuçları sunar.</p>
            <div class="steps">
                <article class="active"><b>01</b><div><h3>Ürününüzü veya sorunuzu girin</h3><p>Ürün adı, görsel, barkod, link veya doğrudan sorununuzu yazın.</p></div></article>
                <article><b>02</b><div><h3>AI analiz ve sınıflandırma</h3><p>Motor; tarife cetveli, BTB kararı ve güncel mevzuatla çapraz doğrulama yapar.</p></div></article>
                <article><b>03</b><div><h3>Kaynaklı sonuç ve risk analizi</h3><p>Her sonuç tıklanabilir resmi kaynakla desteklenir. Yanlış beyan ceza riski anlık hesaplanır.</p></div></article>
                <article><b>04</b><div><h3>Uzman onayı ve raporlama</h3><p>İsterseniz sertifikalı gümrük müşaviri sonucu doğrular.</p></div></article>
            </div>
        </div>
        <div class="process-panel">
            <span>ADIM 01 · GİRİŞ</span>
            <div class="assistant-card">
                <strong>GTİP Asistanı</strong><em>Aktif</em>
                <p>Ürün adını, görselini, barkodunu veya ürün sayfası linkini girin. Yabancı dil desteği ile tedarikçi faturasındaki tanımı da doğrudan yapıştırabilirsiniz.</p>
            </div>
            <div class="input-card">
                <strong>DESTEKLENEN GİRİŞLER</strong>
                <p>Türkçe / İngilizce · Görsel / Foto · Barkod / GTİN · URL / Link · GTİP No · Teknik Tanım · Çince / Almanca</p>
            </div>
        </div>
    </div>
</section>

<section class="section contrast" id="rakipler">
    <span class="eyebrow">NEDEN PRATİK GÜMRÜK</span>
    <h2>Rakiplerden farkımız</h2>
    <div class="compare-grid">
        <div class="compare-table">
            <div class="compare-row head"><span>ÖZELLİK</span><span>PRATİK GÜMRÜK</span><span>DİĞERLERİ</span></div>
            <?php foreach ($featureList as $feature): ?>
                <div class="compare-row"><span><?= e($feature[0]) ?></span><b class="<?= $feature[1] ?>">✓</b><b class="<?= $feature[2] ?>"><?= $feature[2] === 'warn' ? '~' : '×' ?></b></div>
            <?php endforeach; ?>
        </div>
        <div class="metric-list">
            <article><h3>₺1.2<span>M+</span></h3><p>Bu ay kullanıcıların önlediği toplam ceza riski. Doğru GTİP = doğru vergi = cezasız gümrük.</p></article>
            <article><h3>370<span>K+</span></h3><p>Bakanlık onaylı Bağlayıcı Tarife Bilgisi kararı. Her sonuç tıklanabilir resmi kaynaklarla desteklenir.</p></article>
            <article><h3>%98<span>.2</span></h3><p>Gümrük müşaviri onay oranı. AI önerisi + uzman doğrulaması birlikte çalışır.</p></article>
        </div>
    </div>
</section>

<section class="section dark-section security">
    <span class="eyebrow">GÜVENLİK & GİZLİLİK</span>
    <h2>Verileriniz uluslararası standartlarda korunuyor</h2>
    <div class="security-grid">
        <?php foreach ($security as $item): ?>
            <article><span><?= feature_icon_svg($item[0]) ?></span><h3><?= e($item[1]) ?></h3><p><?= e($item[2]) ?></p></article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section pricing" id="fiyatlandirma">
    <div class="section-head centered">
        <span class="eyebrow">FİYATLANDIRMA</span>
        <h2>İhtiyacınıza göre plan seçin</h2>
        <p>Tüm planlar 14 gün ücretsiz deneme içerir. Kredi kartı gerekmez.</p>
    </div>
    <div class="pricing-grid">
        <article><span>BAŞLANGIÇ</span><h3>Ücretsiz</h3><p>Küçük ithalatçılar ve öğrenmek isteyenler için</p><ul><li>Aylık 20 GTİP sorgusu</li><li>Temel vergi hesaplama</li><li>Mevzuat arama sınırlı</li><li>Ceza risk hesabı</li></ul><a class="btn btn-outline" href="#">Ücretsiz Başlayın</a></article>
        <article class="featured"><em>Önerilen</em><span>PROFESYONEL</span><h3>₺2.900 <small>/ay</small></h3><p>Gümrük müşavirleri ve ithalat-ihracat departmanları için</p><ul><li>Sınırsız GTİP sorgulama</li><li>Belge kontrol 50/ay</li><li>Derin mevzuat araştırması</li><li>Beyanname taslağı 20/ay</li><li>PDF raporlama</li></ul><a class="btn btn-primary" href="#">14 Gün Ücretsiz Dene</a></article>
        <article><span>KURUMSAL</span><h3>Özel</h3><p>Büyük hacimli ithalatçılar, lojistik şirketleri ve müşavirlik büroları için</p><ul><li>Sınırsız her modül</li><li>API erişimi + entegrasyon</li><li>Müşavir öncelikli erişim</li><li>Özel eğitim & onboarding</li><li>7/24 destek</li></ul><a class="btn btn-outline" href="#">Demo Talep Edin</a></article>
    </div>
</section>

<section class="section faq" id="sss">
    <div class="section-head centered">
        <span class="eyebrow">SSS</span>
        <h2>Sık sorulan sorular</h2>
    </div>
    <div class="faq-grid">
        <?php foreach ($faqs as $faq): ?>
            <details>
                <summary><?= e($faq['question']) ?><span>+</span></summary>
                <p><?= e($faq['answer']) ?></p>
            </details>
        <?php endforeach; ?>
    </div>
</section>

<?php if (!empty($posts)): ?>
<section class="section blog-preview">
    <div class="section-head">
        <span class="eyebrow">BLOG</span>
        <h2>Gümrük gündemi</h2>
    </div>
    <div class="blog-grid">
        <?php foreach ($posts as $post): ?>
            <article><time><?= e(date('d.m.Y', strtotime($post['published_at']))) ?></time><h3><?= e($post['title']) ?></h3><p><?= e($post['summary']) ?></p><a href="<?= url('/blog/' . $post['slug']) ?>">Oku →</a></article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="final-cta">
    <h2>Gümrük süreçlerinizi bugün dönüştürün</h2>
    <p>14 gün ücretsiz, kredi kartı gerektirmez. İlk GTİP sorgunuzu dakikalar içinde çözün.</p>
    <div><a class="btn btn-primary btn-large" href="#fiyatlandirma">Ücretsiz Başlayın</a><a class="btn btn-dark-outline btn-large" href="#">Demo Talep Edin →</a></div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
