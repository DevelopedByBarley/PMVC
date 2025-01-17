

<?php
//$router->get('/', 'index.php');

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Controller;

$router->get('/', [Controller::class, 'render']);
$router->get('/test/{id}', [Controller::class, 'renderTest'])->only('auth');


// Auth Routes

$router->get('/admin', [AdminAuthController::class, 'create']);
$router->post('/admin', [AdminAuthController::class, 'store']);
