<?php

use App\Http\Controllers\Auth\UserAuthController;

$router->get('/login', [UserAuthController::class, "loginPage"]);
$router->get('/register', [UserAuthController::class, 'create']);

$router->post('/login', [UserAuthController::class, 'login']);
$router->post('/register', [UserAuthController::class, 'store']);
$router->post('/logout', [UserAuthController::class, 'logout'])->middleware('auth');


