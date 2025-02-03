<?php

use App\Http\Controllers\Auth\UserAuthController;

$router->get('/login', [UserAuthController::class, "loginPage"])->middleware('guest');
$router->get('/register', [UserAuthController::class, 'create'])->middleware('guest');

$router->post('/login', [UserAuthController::class, 'login'])->middleware('guest');
$router->post('/register', [UserAuthController::class, 'store'])->middleware('guest');
$router->post('/logout', [UserAuthController::class, 'logout'])->middleware('auth');


