<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Core\EmailVerify;
use Core\Faker;
use Core\Navigator;
use Core\Response;
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

    $users = $this->User->all();


    return Response::view('auth/create', 'layout', [
      "errors" => Session::get('errors') ?? [],
      'test' => 'test value',
      'users' => $users
    ]);
  }

  public function loginPage()
  {
    Session::create();
    if (Session::get('user')) {
      return Navigator::redirect('/user');
    }

    Response::view('auth/store', 'layout', [
      "errors" => Session::get('errors') ?? [],
      'old' => Session::get('old') ?? []
    ]);
  }


  public function login()
  {
    Session::create();

    try {
      $validated = $this->request->validate([
        "email" => ['required', 'email'],
        "password" => ['required'],
      ]);
    } catch (ValidationException $exception) {
      Session::flash('errors', $exception->errors);
      Session::flash('old', $exception->old);
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    $email = $validated['email'] ?? null;
    $password = $validated['password'] ?? null;

    $authenticated = $this->auth->attempt($email, $password, 'users');

    if (!$authenticated) {
      Session::flash('old', $this->request->all());
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal, vagy erősítse meg regisztrációját az emailben kapott linken!')->back();
    }

    return Navigator::redirect('/user');
  }

  public function store()
  {
    $file = request('file');
    (new Storage())->file($file)->save();
    $faker = Faker::create();
    try {
      $validated = $this->request->validate([
        "email" => ['required', 'email','unique:email|users'],
        "password" => ['required'],
      ]);
    } catch (ValidationException $exception) {
      Session::flash('errors', $exception->errors);
      Session::flash('old', $exception->old);
      return $this->toast->danger('Sikertelen regisztráció, kérjük próbálja meg más adatokkal!')->back();
    }

    $email = $validated['email'] ?? null;
    $password = $validated['password'] ?? null;

    $last_id = $this->User->create([
      'name' => $faker->name(),
      'phone' => '06305541004',
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);


    if ($last_id) {

      $token = new Token();

      [$based, $token, $expires_at] = $token->from($email)->generate()->all();

      $verify = new EmailVerify();

      $verify->store($last_id, $token, $expires_at)->send($email, $password, $based);


      //$this->auth::login('user', $email);
      //this->mailer->prepare("arpadsz@max.hu", "Teszt")->template('test', ['email' => $email])->send();

      $this->toast->success('Sikeres regisztráció! Kérjük erősítse meg emailben kapott linket, hogy be tudjon lépni az oldalra.')
        ->title('Regisztráció sikeres')
        ->description('Az adatok sikeresen mentésre kerültek.')
        ->position('bottom-0 end-0')
        ->delay(3000)
        ->icon('fas fa-check-circle')
        ->back();
    }
  }

  public function logout()
  {
    $this->auth::logout();
    return Navigator::redirect('/login');
  }
}
