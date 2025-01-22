

<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use Core\Language;
use Core\Navigator;
use Core\Request;

// Base Routes
$router->view('/',  function () { Language::set();
  echo view('components/layout', [
    'root' => view('welcome')
  ]);
});

$router->post('/lang', function() {
  Language::switch(Request::key('lang'));
  Navigator::redirectBack();
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


$router->get('/dashboard', [UserAuthController::class, 'index'])->only('auth');
$router->get('/profile', [UserAuthController::class, 'show'])->only('auth');

$router->post('/logout', [UserAuthController::class, 'logout'])->only('auth');
$router->post('/user', [UserAuthController::class, 'store']);
$router->post('/login', [UserAuthController::class, 'login']);
