<?php
declare(strict_types=1);

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'layouts/public'): void
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require __DIR__ . '/../Views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/' . $layout . '.php';
    }
}
