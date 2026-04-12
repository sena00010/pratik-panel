<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$router = new Router();

$router->get('/', [PublicController::class, 'home']);
$router->get('/blog', [PublicController::class, 'blog']);
$router->get('/blog/{slug}', [PublicController::class, 'blogDetail']);
$router->get('/modul/{slug}', [PublicController::class, 'moduleDetail']);

$adminPath = config('app.admin_path');
$router->get($adminPath, [AdminController::class, 'dashboard']);
$router->post($adminPath . '/login', [AdminController::class, 'login']);
$router->post($adminPath . '/logout', [AdminController::class, 'logout']);
$router->post($adminPath . '/modules/save', [AdminController::class, 'saveModule']);
$router->post($adminPath . '/modules/delete', [AdminController::class, 'deleteModule']);
$router->post($adminPath . '/faqs/save', [AdminController::class, 'saveFaq']);
$router->post($adminPath . '/faqs/delete', [AdminController::class, 'deleteFaq']);
$router->post($adminPath . '/blogs/save', [AdminController::class, 'saveBlog']);
$router->post($adminPath . '/blogs/delete', [AdminController::class, 'deleteBlog']);
$router->post($adminPath . '/seo/save', [AdminController::class, 'saveSeo']);

$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
