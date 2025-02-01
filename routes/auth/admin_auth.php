<?php

use App\Http\Controllers\Auth\AdminAuthController;

$router->get('/admin', [AdminAuthController::class, 'create']);
$router->post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('admin');


$router->post('/admin', [AdminAuthController::class, 'store']);
$router->post('/admin/register', [AdminAuthController::class, 'register']);
