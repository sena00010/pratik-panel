<?php
declare(strict_types=1);

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Istanbul');

session_name('pg_admin_session');
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

spl_autoload_register(static function (string $class): void {
    $paths = [
        __DIR__ . '/Core/' . $class . '.php',
        __DIR__ . '/Controllers/' . $class . '.php',
        __DIR__ . '/Models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require $path;
            return;
        }
    }
});

require __DIR__ . '/helpers.php';
