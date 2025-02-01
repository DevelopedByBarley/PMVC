<?php

use App\Http\Controllers\UserController;

$router->get('/user/profile', [UserController::class, 'show'])->middleware(['auth']);
$router->except(['show'])->resources('user', UserController::class, ['auth']);