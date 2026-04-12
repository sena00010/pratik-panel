<?php
$blocks = $landingBlocks ?? [];
$hero = $blocks['hero'] ?? [
    'pill' => 'Gümrük profesyonelleri için yapay zeka platformu',
    'title_before' => 'Gümrük işlemlerinizi',
    'title_accent' => 'doğru, hızlı ve güvenli',
    'title_after' => 'yönetin',
    'description' => 'GTİP tespitinden beyanname hazırlamaya, mevzuat araştırmasından ceza risk analizine kadar tüm gümrük süreçlerini tek platformda yönetin. Resmi kaynak garantisi ile.',
    'primary_button' => 'Ücretsiz Başlayın',
    'secondary_button' => 'Demo İzleyin →',
];
$trusted = $blocks['trusted'] ?? ['ZORLU HOLDİNG', 'ÜLKER', 'ARÇELİK', 'YILDIRIM GROUP', 'EKOL LOJİSTİK', 'ATA TAŞIMACILIK'];
$moduleCopy = $blocks['module_copy'] ?? [
    'eyebrow' => 'PLATFORM MODÜLLERİ',
    'title' => 'Gümrük sürecinizin her adımı için araç',
    'description' => 'Her modül bağımsız çalışır; dilediğiniz modülden başlayın, ihtiyacınıza göre genişletin.',
];
$processCopy = $blocks['process_copy'] ?? [
    'eyebrow' => 'NASIL ÇALIŞIR',
    'title' => 'AI + uzman bilgisi = doğru sonuç',
    'description' => 'Pratik Gümrük, ham AI tahminini değil, resmi kaynaklarla doğrulanmış, uzman onaylı sonuçları sunar.',
];
$processSteps = $blocks['process_steps'] ?? [
    [
        'label' => 'ADIM 01 · GİRİŞ',
        'title' => 'Ürününüzü veya sorunuzu girin',
        'text' => 'Ürün adı, görsel, barkod, link veya doğrudan sorunuzu yazın.',
        'panel_html' => '<div class="assistant-card"><strong>GTİP Asistanı</strong><em>Aktif</em><p>Ürün adını, görselini, barkodunu veya ürün sayfası linkini girin. Yabancı dil desteği ile tedarikçi faturasındaki tanımı da doğrudan yapıştırabilirsiniz.</p></div><div class="input-card"><strong>DESTEKLENEN GİRİŞLER</strong><p><span>Türkçe / İngilizce</span><span>Görsel / Foto</span><span>Barkod / GTİN</span><span>URL / Link</span><span>GTİP No</span><span>Teknik Tanım</span><span>Çince / Almanca</span></p></div>',
    ],
    [
        'label' => 'ADIM 02 · AI ANALİZ',
        'title' => 'AI analiz ve sınıflandırma',
        'text' => 'Motor; tarife cetveli, BTB kararı ve güncel mevzuatla çapraz doğrulama yapar.',
        'panel_html' => '<div class="input-card process-note"><strong>MOTOR ÇALIŞIYOR...</strong><p>97.000+ GTİP pozisyonu taranıyor → 370.000+ BTB emsal kararıyla çapraz doğrulama → Fasıl 84 öncelikli → 3 olası pozisyon tespit edildi → güven skoru hesaplanıyor...</p></div><div class="assistant-card result-card"><strong>Tespit edilen pozisyonlar</strong><p><b>8479.89.97.00.19</b><span>%87 güven</span></p><p><b>8415.82.00.00.11</b><span>%52 güven</span></p><p><b>8414.51.00.00.11</b><span>%18 güven</span></p></div>',
    ],
    [
        'label' => 'ADIM 03 · SONUÇ & RİSK',
        'title' => 'Kaynaklı sonuç ve risk analizi',
        'text' => 'Her sonuç tıklanabilir resmi kaynakla desteklenir. Yanlış beyan ceza riski anlık hesaplanır.',
        'panel_html' => '<div class="assistant-card code-card"><strong>Önerilen GTİP</strong><em>BTB Onaylı</em><p><b>8479.89.97.00.19</b></p><p><span>GV: %3.7</span><span>KDV: %20</span><span>İGV: %20 (Çin)</span></p></div><div class="input-card risk-card"><strong>RİSK ANALİZİ</strong><p>Bu ürünü 8414.51 koduyla beyan ederseniz: vergi farkı %5’i aşacak → <b>eksik verginin 3 katı idari ceza</b> riski. Tahmini risk: ₺340.000</p></div>',
    ],
    [
        'label' => 'ADIM 04 · UZMAN ONAYI',
        'title' => 'Uzman onayı ve raporlama',
        'text' => 'İsterseniz sertifikalı gümrük müşaviri sonucu doğrular.',
        'panel_html' => '<div class="chat-card"><p><b>PG</b><span>GTİP tespiti tamamlandı. 8479.89.97.00.19 kodunu onaylıyorum. Evaporatif soğutucu sınıflandırması için 3 BTB emsal kararı görmek ister misiniz?</span></p><p class="user"><span>Evet lütfen, ayrıca beyanname taslağını da hazırlayabilir misiniz?</span><b>S</b></p><p><b>PG</b><span>BTB kararları eklendi. Beyanname taslağı hazır, faturadaki değerleri otomatik doldurdum.</span></p></div>',
    ],
];
$featureList = $blocks['comparison_features'] ?? [
    ['text' => 'BTB emsal kararı entegrasyonu', 'pratik' => 'ok', 'others' => 'no'],
    ['text' => 'Çakışan GTİP sınıflandırma sorusu', 'pratik' => 'ok', 'others' => 'no'],
    ['text' => 'Ceza risk hesaplama aracı', 'pratik' => 'ok', 'others' => 'no'],
    ['text' => 'Belge → beyanname otomasyonu', 'pratik' => 'ok', 'others' => 'warn'],
    ['text' => "Türkiye'ye özel 12 haneli GTİP", 'pratik' => 'ok', 'others' => 'no'],
    ['text' => 'Uzman müşavir gözden geçirme', 'pratik' => 'ok', 'others' => 'no'],
    ['text' => 'TAREKS / BİLGE uyumluluk kontrolü', 'pratik' => 'ok', 'others' => 'no'],
];
$comparisonMetrics = $blocks['comparison_metrics'] ?? [
    ['value' => '₺1.2', 'suffix' => 'M+', 'text' => 'Bu ay kullanıcıların önlediği toplam ceza riski. Doğru GTİP = doğru vergi = cezasız gümrük.'],
    ['value' => '370', 'suffix' => 'K+', 'text' => 'Bakanlık onaylı Bağlayıcı Tarife Bilgisi kararı. Her sonuç tıklanabilir resmi kaynaklarla desteklenir.'],
    ['value' => '%98', 'suffix' => '.2', 'text' => 'Gümrük müşaviri onay oranı. AI önerisi + uzman doğrulaması birlikte çalışır.'],
];
$security = $blocks['security_items'] ?? [
    ['icon' => 'shield', 'title' => 'KVKK & GDPR Uyumlu', 'text' => 'Kişisel ve ticari verileriniz Türk hukuku ve AB veri koruma mevzuatı kapsamında işlenir.'],
    ['icon' => 'lock', 'title' => 'Uçtan Uca Şifreleme', 'text' => 'Tüm veriler AES-256 ile şifrelenmiş sunucularda saklanır. Transfer sırasında TLS 1.3 kullanılır.'],
    ['icon' => 'clock', 'title' => 'Denetim & İzlenebilirlik', 'text' => 'Tüm kritik işlemler zaman damgalı, değiştirilemez kayıtlarla izlenir.'],
    ['icon' => 'hexagon', 'title' => 'Model Eğitimi Yok', 'text' => 'Yüklediğiniz belgeler veya sorularınız AI modellerini eğitmek için kullanılmaz.'],
    ['icon' => 'activity', 'title' => '%99.9 Uptime SLA', 'text' => 'Kritik gümrük süreçleriniz kesintisiz çalışır. Bakım pencereleri önceden bildirilir.'],
    ['icon' => 'users', 'title' => 'Rol Bazlı Erişim', 'text' => 'Ekip üyelerinize modül ve veri bazında farklı erişim seviyeleri tanımlayın.'],
];
$pricingPlans = $blocks['pricing_plans'] ?? [
    ['badge' => 'BAŞLANGIÇ', 'name' => 'Ücretsiz', 'price_suffix' => '', 'description' => 'Küçük ithalatçılar ve öğrenmek isteyenler için', 'features' => ['Aylık 20 GTİP sorgusu', 'Temel vergi hesaplama', 'Mevzuat arama sınırlı', 'Ceza risk hesabı'], 'button' => 'Ücretsiz Başlayın', 'featured' => false, 'recommended' => ''],
    ['badge' => 'PROFESYONEL', 'name' => '₺2.900', 'price_suffix' => '/ay', 'description' => 'Gümrük müşavirleri ve ithalat-ihracat departmanları için', 'features' => ['Sınırsız GTİP sorgulama', 'Belge kontrol 50/ay', 'Derin mevzuat araştırması', 'Beyanname taslağı 20/ay', 'PDF raporlama'], 'button' => '14 Gün Ücretsiz Dene', 'featured' => true, 'recommended' => 'Önerilen'],
    ['badge' => 'KURUMSAL', 'name' => 'Özel', 'price_suffix' => '', 'description' => 'Büyük hacimli ithalatçılar, lojistik şirketleri ve müşavirlik büroları için', 'features' => ['Sınırsız her modül', 'API erişimi + entegrasyon', 'Müşavir öncelikli erişim', 'Özel eğitim & onboarding', '7/24 destek'], 'button' => 'Demo Talep Edin', 'featured' => false, 'recommended' => ''],
];
$finalCta = $blocks['final_cta'] ?? [
    'title' => 'Gümrük süreçlerinizi bugün dönüştürün',
    'description' => '14 gün ücretsiz, kredi kartı gerektirmez. İlk GTİP sorgunuzu dakikalar içinde çözün.',
    'primary_button' => 'Ücretsiz Başlayın',
    'secondary_button' => 'Demo Talep Edin →',
];
?>
<section class="hero dark-grid" id="ust">
    <div class="hero-inner">
        <span class="pill"><?= e($hero['pill'] ?? '') ?></span>
        <h1><?= e($hero['title_before'] ?? '') ?> <span><?= e($hero['title_accent'] ?? '') ?></span> <?= e($hero['title_after'] ?? '') ?></h1>
        <p><?= e($hero['description'] ?? '') ?></p>
        <div class="hero-actions">
            <a class="btn btn-primary btn-large" href="#fiyatlandirma"><?= e($hero['primary_button'] ?? 'Ücretsiz Başlayın') ?></a>
            <a class="btn btn-dark-outline btn-large" href="#nasil-calisir"><?= e($hero['secondary_button'] ?? 'Demo İzleyin →') ?></a>
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
    <?php foreach ($trusted as $brand): ?><span><?= e((string) $brand) ?></span><?php endforeach; ?>
</section>

<section class="section light-section" id="moduller">
    <div class="section-head">
        <span class="eyebrow"><?= e($moduleCopy['eyebrow'] ?? '') ?></span>
        <h2><?= e($moduleCopy['title'] ?? '') ?></h2>
        <p><?= e($moduleCopy['description'] ?? '') ?></p>
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
            <span class="eyebrow"><?= e($processCopy['eyebrow'] ?? '') ?></span>
            <h2><?= e($processCopy['title'] ?? '') ?></h2>
            <p><?= e($processCopy['description'] ?? '') ?></p>
            <div class="steps" data-process-steps>
                <?php foreach ($processSteps as $index => $step): ?>
                    <article class="<?= $index === 0 ? 'active' : '' ?>" tabindex="0" role="button" data-step="<?= $index ?>" data-label="<?= e($step['label'] ?? '') ?>" data-panel="<?= e($step['panel_html'] ?? '') ?>">
                        <b><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></b>
                        <div><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="process-panel" data-process-panel>
            <span data-process-label><?= e($processSteps[0]['label'] ?? '') ?></span>
            <div data-process-content><?= $processSteps[0]['panel_html'] ?? '' ?></div>
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
                <div class="compare-row"><span><?= e($feature['text'] ?? '') ?></span><b class="<?= e($feature['pratik'] ?? 'ok') ?>">✓</b><b class="<?= e($feature['others'] ?? 'no') ?>"><?= ($feature['others'] ?? 'no') === 'warn' ? '~' : '×' ?></b></div>
            <?php endforeach; ?>
        </div>
        <div class="metric-list">
            <?php foreach ($comparisonMetrics as $metric): ?>
                <article><h3><?= e($metric['value'] ?? '') ?><span><?= e($metric['suffix'] ?? '') ?></span></h3><p><?= e($metric['text'] ?? '') ?></p></article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section dark-section security">
    <span class="eyebrow">GÜVENLİK & GİZLİLİK</span>
    <h2>Verileriniz uluslararası standartlarda korunuyor</h2>
    <div class="security-grid">
        <?php foreach ($security as $item): ?>
            <article><span><?= feature_icon_svg($item['icon'] ?? 'shield') ?></span><h3><?= e($item['title'] ?? '') ?></h3><p><?= e($item['text'] ?? '') ?></p></article>
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
        <?php foreach ($pricingPlans as $plan): ?>
            <article class="<?= !empty($plan['featured']) ? 'featured' : '' ?>">
                <?php if (!empty($plan['recommended'])): ?><em><?= e($plan['recommended']) ?></em><?php endif; ?>
                <span><?= e($plan['badge'] ?? '') ?></span>
                <h3><?= e($plan['name'] ?? '') ?><?php if (!empty($plan['price_suffix'])): ?> <small><?= e($plan['price_suffix']) ?></small><?php endif; ?></h3>
                <p><?= e($plan['description'] ?? '') ?></p>
                <ul><?php foreach (($plan['features'] ?? []) as $feature): ?><li><?= e((string) $feature) ?></li><?php endforeach; ?></ul>
                <a class="btn <?= !empty($plan['featured']) ? 'btn-primary' : 'btn-outline' ?>" href="#"><?= e($plan['button'] ?? '') ?></a>
            </article>
        <?php endforeach; ?>
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
    <h2><?= e($finalCta['title'] ?? '') ?></h2>
    <p><?= e($finalCta['description'] ?? '') ?></p>
    <div><a class="btn btn-primary btn-large" href="#fiyatlandirma"><?= e($finalCta['primary_button'] ?? '') ?></a><a class="btn btn-dark-outline btn-large" href="#"><?= e($finalCta['secondary_button'] ?? '') ?></a></div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
