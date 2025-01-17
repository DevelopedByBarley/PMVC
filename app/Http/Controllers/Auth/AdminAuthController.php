<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class AdminAuthController extends Controller
{
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

    $email = filter_sanitize( $_POST['email']) ?? null;
    $password = filter_sanitize( $_POST['password']) ?? null;

    $authenticated = $this->auth->attempt($email, $password, 'admins');

    if (!$authenticated) {
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    $this->auth::login('admin');
  }
}
