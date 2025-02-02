<?php

use App\Http\Controllers\UserController;

$router->get('/user/profile', [UserController::class, 'show'])->middleware(['auth', 'verify']);
$router->except(['show', 'create'])->resources('user', UserController::class, ['auth', 'verify']);