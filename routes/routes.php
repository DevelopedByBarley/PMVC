

<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;

// Base Routes
$router->view('/',  function () {
  echo view('components/layout', [
    'root' => view('welcome')
  ]);
});

$router->get('/test/{id}', function ($id) {
  echo view('components/layout', [
    'root' => view('welcome', ["id" => $id])
  ]);
});


// Admin Auth Routes

$router->get('/admin', [AdminAuthController::class, 'create']);
$router->get('/admin/dashboard', [AdminAuthController::class, 'index'])->only('admin');
$router->get('/admin/logout', [AdminAuthController::class, 'logout'])->only('admin');


$router->post('/admin', [AdminAuthController::class, 'store']);
$router->post('/admin/register', [AdminAuthController::class, 'register']);


// User Auth Routes

$router->get('/login', [UserAuthController::class, "loginPage"]);
$router->get('/register', [UserAuthController::class, 'create']);


$router->get('/user/dashboard', [UserAuthController::class, 'index'])->only('auth');
$router->get('/user/profile', [UserAuthController::class, 'show'])->only('auth');

$router->post('/logout', [UserAuthController::class, 'logout'])->only('auth');
$router->post('/user', [UserAuthController::class, 'store']);
$router->post('/login', [UserAuthController::class, 'login']);
