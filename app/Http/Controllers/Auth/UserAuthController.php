<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Navigator;
use Core\Session;
use Core\ValidationException;
//index, show, create, edit, delete

class UserAuthController extends Controller
{

  public function index()
  {
    echo view('components/layout', [
      'root' => view('auth/index', [])
    ]);
  }

  public function create()
  {
    session_start();
    if (Session::get('user')) {
      return Navigator::redirect('/user/dashboard');
    }

    echo view('components/layout', [
      'root' => view('auth/create', [
        "errors" => Session::get('errors') ?? []
      ])
    ]);
  }
  
  public function loginPage() {
    echo view('components/layout', [
      'root' => view('auth/store', [])
    ]);
  }


  public function login()
  {
    session_start();

    try {
      $validated = $this->request->validate([
        "email" => ['required'],
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

  public function store()
  {
    session_start();

    try {
      $validated = $this->request->validate([
        "email" => ['required'],
        "password" => ['required'],
      ]);
    } catch (ValidationException $exception) {
      Session::flash('errors', $exception->errors);
      Session::flash('old', $exception->old);
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    $email = filter_sanitize($validated['email']) ?? null;
    $password = filter_sanitize($validated['password']) ?? null;

    $this->model->insertIntoTable('users', [
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    $this->auth::login('user', $email);

    $this->mailer->prepare("arpadsz@max.hu", "Teszt")->template('test', ['email' => $email])->send();

    return Navigator::redirect('/user/dashboard');
  }

  public function logout()
  {
    $this->auth::logout();
    return Navigator::redirect('/login');
  }
}
