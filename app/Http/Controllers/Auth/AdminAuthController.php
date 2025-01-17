<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Navigator;

class AdminAuthController extends Controller
{

  public function register()
  {
    $this->model->insertIntoTable('admins', [
      'email' => "developedbybarley@gmail.com",
      'password' => password_hash('Csak1enter@test', PASSWORD_DEFAULT)
    ]);
  }

  public function show()
  {
    echo view('components/admin-layout', [
      'root' => view('admin/show', [])
    ]);
  }
  public function create()
  {
    session_start();


    echo view('components/admin-layout', [
      'root' => view('admin/create', [])
    ]);
  }

  public function store()
  {
    session_start();

    $email = filter_sanitize($_POST['email']) ?? null;
    $password = filter_sanitize($_POST['password']) ?? null;

    $authenticated = $this->auth->attempt($email, $password, 'admins');

    if (!$authenticated) {
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    $this->auth::login('admin', $email);
    
    return Navigator::redirect('/admin/dashboard');
  }
} 
