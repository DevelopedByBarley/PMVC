<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Faker;
use Core\Navigator;
use Core\Session;
use Core\Storage;
use Core\ValidationException;
//index, show, create, edit, delete

class UserAuthController extends Controller
{


  public function index()
  {
    $user =  Session::get('user');

    echo view('components/layout', [
      'root' => view('auth/index', [
        'user' => $user
      ])
    ]);
  }

  public function show()
  {
    $user =  Session::get('user');
    $user->notes = $this->model->join('notes', 'user_id', $user->id);

    echo view('components/layout', [
      'root' => view('auth/show', [
        'user' => $user,
      ])
    ]);
  }

  public function create()
  {
    Session::create();

    if (Session::get('user')) {
      return Navigator::redirect('/user');
    }

    echo view('components/layout', [
      'root' => view('auth/create', [
        "errors" => Session::get('errors') ?? []
      ])
    ]);
  }

  public function loginPage()
  {
    Session::create();
    if (Session::get('user')) {
      return Navigator::redirect('/user');
    }
    echo view('components/layout', [
      'root' => view('auth/store', [
        "errors" => Session::get('errors') ?? []
      ])
    ]);
  }


  public function login()
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

    $authenticated = $this->auth->attempt($email, $password, 'users');

    if (!$authenticated) {
      Session::flash('old', $this->request->all());
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    return Navigator::redirect('/user');
  }

  public function store()
  {
    (new Storage())->files($_FILES['file'])->deletePrevImages('/', ['1992607091679a707a21ff06.02347278  .jpg'])->save();
    $faker = Faker::create();
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

    $this->model->insertIntoTable('users', [
      'name' => $faker->name(),
      'phone' => $faker->phoneNumber(),
      'BIO' => $faker->text(100),
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);


    $this->auth::login('user', $email);

    $this->mailer->prepare("arpadsz@max.hu", "Teszt")->template('test', ['email' => $email])->send();

    return Navigator::redirect('/user');
  }

  public function logout()
  {
    $this->auth::logout();
    return Navigator::redirect('/login');
  }
}
