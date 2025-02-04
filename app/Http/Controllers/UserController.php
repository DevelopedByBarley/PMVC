<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Core\Session;

class UserController extends Controller
{
  private $User;

  public function __construct()
  {
    parent::__construct();
    $this->User = new User();
  }
 
  public function index()
  {
    $search = $_GET['search'] ?? '';
    $user =  Session::get('user');
    $users = $this->User->all(true, $search, ['name', 'email', 'created_at']);

    echo view('components/user-layout', [
      'root' => view('user/index', [
        'title' => 'Üdvözöljük',
        'paginated' => $users,
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
