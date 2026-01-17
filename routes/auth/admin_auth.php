<?php

use App\Http\Controllers\Auth\AdminAuthController;

$router->get('/admin/login', [AdminAuthController::class, 'loginPage']);
$router->post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('admin');


$router->post('/admin/login', [AdminAuthController::class, 'store']);
$router->post('/admin/register', [AdminAuthController::class, 'register']);

$router->patch('/admin/password-reset', [AdminAuthController::class, 'resetPassword']);