<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Core\Response;
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

    return Response::view('auth/index', 'layout', [
      'user' => $user,
      'search' => $search
    ]);
  }

  public function show()
  {
    $user =  Session::get('user');

    $users = $this->User->all(true, ['name' => $user->name], ['name']);

    return Response::view('auth/show', 'layout', [
      'user' => $user,
      'users' => $users
    ]);
  }

  public function update()
  {
    $user = Session::get('user');
    $data = request();

    // Validáció
    $this->request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'avatar' => 'nullable|file|image|max:2048'
    ]);

    // Frissítés
    $this->User->update($user->id, $data);

    // Visszajelzés
    $this->toast->success('Profil frissítve!')
      ->title('Sikeres frissítés')
      ->description('A profil adatai sikeresen frissítve lettek.')
      ->delay(3000)
      ->icon('fas fa-check-circle')
      ->show();

    return Response::redirect('/user/profile'); 
  }
}
