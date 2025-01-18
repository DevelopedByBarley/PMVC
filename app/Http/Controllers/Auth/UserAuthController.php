<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Navigator;
use Core\Session;
use Core\ValidationException;

class UserAuthController extends Controller
{

  public function register()
  {
    $this->model->insertIntoTable('users', [
      'email' => "developedbybarley@gmail.com",
      'password' => password_hash('Csak1enter@test', PASSWORD_DEFAULT)
    ]);
  }

  public function show()
  {
    echo view('components/layout', [
      'root' => view('auth/show', [])
    ]);
  }

  public function create()
  {
    session_start();
    if (Session::get('user')) {
      return Navigator::redirect('/user/dashboard');
    }

    //dd(Session::get('errors'));

    echo view('components/layout', [
      'root' => view('auth/create', [
        "errors" => Session::get('errors') ?? []
      ])
    ]);
  }


  public function login() {
    echo view('components/layout', [
      'root' => view('auth/store', [])
    ]);
  }

  public function store()
  {
    session_start();


    try {
      $validated = $this->request->validate([
        "email" => ['required', 'min:100'],
        "password" => ['required'],
      ]);
    } catch (ValidationException $exception) {
      Session::flash('errors', $exception->errors);
      Session::flash('old', $exception->old);
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }




    $email = filter_sanitize($validated['email']) ?? null;
    $password = filter_sanitize($validated['password']) ?? null;

    $authenticated = $this->auth->attempt($email, $password, 'admins');

    if (!$authenticated) {
      Session::flash('old', $this->request->all());
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    $this->auth::login('admin', $email);

    return Navigator::redirect('/admin/dashboard');
  }
}
