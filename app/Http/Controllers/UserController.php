<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Policies\UserPolicy;
use Core\Response;
use Core\Session;
use Core\ValidationException;

class UserController extends Controller
{
  private $User;
  private $policy;

  public function __construct()
  {
    parent::__construct();
    $this->policy = new UserPolicy();
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

  public function update($vars)
  {
    $user = Session::get('user');

    $authorized = $this->policy->authorize('update', (int)$user->id, (int)$vars[0]);
    if (!$authorized) {
      return Response::redirect('/user/profile')->withToast('error', 'Unauthorized action');
    }
    try {
      $validated = $this->request->validate([
        'name' => ['required', 'string',  'max:255'],
        'email' => ['required', 'email', 'max:255'],
        'password' => ['required'],
      ]);
    } catch (ValidationException $e) {
      // Hibakezelés
      $this->toast->danger('Hiba történt a profil frissítésekor!')
        ->title('Hiba')
        ->description($e->getMessage())
        ->delay(3000)
        ->icon('fas fa-exclamation-triangle')
        ->show()
        ->back();
    }

    // Frissítés
    $this->User->updateById($user->id, $validated);

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
