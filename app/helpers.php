<?php
declare(strict_types=1);

function config(string $key, $default = null)
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../config/app.php';
    }

    $segments = explode('.', $key);
    $value = $config;
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim(config('app.base_url', ''), '/');
    return $base . '/' . ltrim($path, '/');
}

function admin_url(string $path = ''): string
{
    return url(trim(config('app.admin_path'), '/') . '/' . ltrim($path, '/'));
}

function asset(string $path): string
{
    return url('public/assets/' . ltrim($path, '/'));
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function verify_csrf(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Oturum doğrulaması başarısız. Sayfayı yenileyip tekrar deneyin.');
    }
}

function slugify(string $text): string
{
    $map = ['ş' => 's', 'Ş' => 's', 'ı' => 'i', 'İ' => 'i', 'ğ' => 'g', 'Ğ' => 'g', 'ü' => 'u', 'Ü' => 'u', 'ö' => 'o', 'Ö' => 'o', 'ç' => 'c', 'Ç' => 'c'];
    $text = strtr($text, $map);
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^a-z0-9]+/u', '-', $text) ?: '';
    return trim($text, '-') ?: 'sayfa';
}

function excerpt(string $text, int $length = 160): string
{
    $plain = trim(strip_tags($text));
    if (mb_strlen($plain, 'UTF-8') <= $length) {
        return $plain;
    }

    return rtrim(mb_substr($plain, 0, $length, 'UTF-8')) . '...';
}

function module_icon_svg(string $slug): string
{
    $icons = [
        'gtip-tespit' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 4.5 7.2v9.2L12 21l7.5-4.6V7.2L12 3Z"/><path d="M4.8 7.4 12 11.7l7.2-4.3"/><path d="M12 11.7V21"/></svg>',
        'vergi-maliyet-hesabi' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v18"/><path d="M16.8 7.2H9.6a3.1 3.1 0 0 0 0 6.2h4.8a3.1 3.1 0 0 1 0 6.2H7.2"/></svg>',
        'belge-kontrol' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h7l4 4v14H7V3Z"/><path d="M14 3v5h5"/><path d="M10 12h6"/><path d="M10 16h6"/></svg>',
        'derin-mevzuat-arastirmasi' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="5.8"/><path d="m15 15 5 5"/></svg>',
        'beyanname-asistani' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19h4.2L19 9.2a2.7 2.7 0 0 0-3.8-3.8L5.4 15.2 5 19Z"/><path d="m14 6 4 4"/></svg>',
        'risk-uyum-analizi' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 19 6v5.2c0 4.5-2.8 7.9-7 9.8-4.2-1.9-7-5.3-7-9.8V6l7-3Z"/></svg>',
    ];

    return $icons[$slug] ?? '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 4.5 7.2v9.2L12 21l7.5-4.6V7.2L12 3Z"/></svg>';
}

function feature_icon_svg(string $name): string
{
    $icons = [
        'shield' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 19 6v5.2c0 4.4-2.8 7.9-7 9.8-4.2-1.9-7-5.4-7-9.8V6l7-3Z"/></svg>',
        'lock' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 11V8a5 5 0 0 1 10 0v3"/><path d="M5.5 11h13v9h-13z"/></svg>',
        'clock' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/></svg>',
        'hexagon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3.5 19.2 7.7v8.6L12 20.5l-7.2-4.2V7.7L12 3.5Z"/></svg>',
        'activity' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h4l2-5 4 10 2-5h4"/></svg>',
        'users' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.5 19c0-2.2-1.8-4-4.5-4s-4.5 1.8-4.5 4"/><circle cx="12" cy="8" r="3"/><path d="M19 18c0-1.7-1-3-2.6-3.6"/><path d="M17 6.2a2.5 2.5 0 0 1 0 4.6"/></svg>',
    ];

    return $icons[$name] ?? $icons['shield'];
}

function cache_remember(string $key, int $ttl, callable $callback)
{
    $file = __DIR__ . '/../storage/cache/' . preg_replace('/[^a-z0-9_.-]/i', '_', $key) . '.cache.php';
    if (is_file($file) && filemtime($file) + $ttl > time()) {
        return require $file;
    }

    $value = $callback();
    file_put_contents($file, '<?php return ' . var_export($value, true) . ';');
    return $value;
}

function cache_clear(): void
{
    foreach (glob(__DIR__ . '/../storage/cache/*.cache.php') ?: [] as $file) {
        @unlink($file);
    }
}
