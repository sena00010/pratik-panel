<?php
declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';

$router = new Router();

$router->get('/', [PublicController::class, 'home']);
$router->get('/blog', [PublicController::class, 'blog']);
$router->get('/blog/{slug}', [PublicController::class, 'blogDetail']);
$router->get('/modul/{slug}', [PublicController::class, 'moduleDetail']);

$adminPath = config('app.admin_path');
$router->get('/login', [AdminController::class, 'redirectToAdmin']);
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
$router->post($adminPath . '/landing/save', [AdminController::class, 'saveLanding']);
$router->post($adminPath . '/admins/save', [AdminController::class, 'saveAdmin']);
$router->post($adminPath . '/admins/delete', [AdminController::class, 'deleteAdmin']);
$router->post($adminPath . '/upload/image', [AdminController::class, 'uploadImage']);
$router->post($adminPath . '/integrations/save', [AdminController::class, 'saveIntegration']);
$router->post($adminPath . '/integrations/delete', [AdminController::class, 'deleteIntegration']);
$router->post($adminPath . '/audience/save', [AdminController::class, 'saveAudience']);
$router->post($adminPath . '/audience/delete', [AdminController::class, 'deleteAudience']);
$router->post($adminPath . '/testimonials/save', [AdminController::class, 'saveTestimonial']);
$router->post($adminPath . '/testimonials/delete', [AdminController::class, 'deleteTestimonial']);

$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
