<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Core\Navigator;
use Core\Session;
use Core\ValidationException;

class AdminAuthController extends Controller
{
  private $Admin;

  public function __construct()
  {
    parent::__construct();
    $this->Admin = new Admin();
  }

  public function register()
  {
    $this->Admin->create([
      'email' => "developedbybarley@gmail.com",
      'password' => password_hash('Csak1enter@test', PASSWORD_DEFAULT)
    ]);
  }

  public function index()
  {
    echo view('components/admin-layout', [
      'root' => view('admin/index', [
        "paginated" => []
      ])
    ]);
  }
  public function create()
  {
    Session::create();
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
    Session::create();


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




    $email = sanitize($validated['email']) ?? null;
    $password = sanitize($validated['password']) ?? null;

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
