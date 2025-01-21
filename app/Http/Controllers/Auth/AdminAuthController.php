<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Navigator;
use Core\Paginator;
use Core\Session;
use Core\ValidationException;

class AdminAuthController extends Controller
{

  public function register()
  {
    $this->model->insertIntoTable('admins', [
      'email' => "developedbybarley@gmail.com",
      'password' => password_hash('Csak1enter@test', PASSWORD_DEFAULT)
    ]);
  }

  public function index()
  {
    $paginated = (new Paginator)->paginate($this->model->all('users'), $_GET['search'] ?? [], ['email', 'phone']);
    dd($paginated);
    echo view('components/admin-layout', [
      'root' => view('admin/index', [
        "paginated" => []
      ])
    ]);
  }
  public function create()
  {
    session_start();
    if (Session::get('admin')) {
      return Navigator::redirect('/admin/dashboard');
    }

    echo view('components/admin-layout', [
      'root' => view('admin/create', [
        "errors" => Session::get('errors') ?? []
      ])
    ]);
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

    $authenticated = $this->auth->attempt($email, $password, 'admins');

    if (!$authenticated) {
      Session::flash('old', $this->request->all());
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    $this->auth::login('admin', $email);

    return Navigator::redirect('/admin/dashboard');
  }

  public function logout()
  {
    $this->auth::logout();
    return Navigator::redirect('/admin');
  }
}
