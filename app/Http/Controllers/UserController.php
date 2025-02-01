<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Core\Session;

class UserController extends Controller
{
  public function index()
  {
    $user =  Session::get('user');

    echo view('components/layout', [
      'root' => view('user/index', [
        'user' => $user
      ])
    ]);
  }

  public function show()
  {
    $user =  Session::get('user');
    
    echo view('components/layout', [
      'root' => view('auth/show', [
        'user' => $user,
      ])
    ]);
  }
}
