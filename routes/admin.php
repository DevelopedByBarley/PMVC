<?php

use App\Http\Controllers\Admin\AdminController;

$router->get('/admin/dashboard', [AdminController::class, 'index'])->middleware(['admin']);
