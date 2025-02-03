<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Core\EmailVerify;
use Core\Faker;
use Core\Navigator;
use Core\Session;
use Core\Storage;
use Core\Token;
use Core\ValidationException;
//index, show, create, edit, delete

class UserAuthController extends Controller
{
  private $User;

  public function __construct()
  {
    parent::__construct();
    $this->User = new User();
  }


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

    $authenticated = $this->auth->attempt($email, $password, 'users', true);

    if (!$authenticated) {
      Session::flash('old', $this->request->all());
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal, vagy erősítse meg regisztrációját az emailben kapott linken!')->back();
    }

    return Navigator::redirect('/user');
  }

  public function store()
  {
    (new Storage())->files($_FILES['file'])->deletePrevImages('/', ['1992607091679a707a21ff06.02347278  .jpg'])->save();
    $faker = Faker::create();
    try {
      $validated = $this->request->validate([
        "email" => ['required', 'unique:email|users'],
        "password" => ['required'],
      ]);
    } catch (ValidationException $exception) {
      Session::flash('errors', $exception->errors);
      Session::flash('old', $exception->old);
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    $email = sanitize($validated['email']) ?? null;
    $password = sanitize($validated['password']) ?? null;

    $last_id = $this->User->create([
      'name' => $faker->name(),
      'phone' => $faker->phoneNumber(),
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);


    if ($last_id) {

      $token = new Token();

      [$based, $token, $expires_at] = $token->from($email)->generate()->all();

      $verify = new EmailVerify();

      $verify->store($last_id, $token, $expires_at)->send($email, $based);


      //$this->auth::login('user', $email);
      //this->mailer->prepare("arpadsz@max.hu", "Teszt")->template('test', ['email' => $email])->send();

      $this->toast->success('Sikeres regisztráció, kérjük erősítse meg e-mail címére küldött levéllel a regisztrációját.')->back();
    }
  }

  public function logout()
  {
    $this->auth::logout();
    return Navigator::redirect('/login');
  }
}
