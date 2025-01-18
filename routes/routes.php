

<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Controller;

$router->get('/', [Controller::class, 'render']);
$router->get('/test/{id}', [Controller::class, 'renderTest']);


// Admin Auth Routes

$router->get('/admin', [AdminAuthController::class, 'create']);
$router->get('/admin/dashboard', [AdminAuthController::class, 'show'])->only('admin');


$router->post('/admin', [AdminAuthController::class, 'store']);
$router->post('/admin/register', [AdminAuthController::class, 'register']);


// User Auth Routes

$router->get('/login', [UserAuthController::class, 'login']);
$router->get('/register', [UserAuthController::class, 'create']);
