<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Core\Log;
use Core\Navigator;
use Core\Response;
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
    return Response::view('admin/index', 'admin-layout', [
      "paginated" => []
    ]);
  /*   echo view('components/admin-layout', [
      'root' => view('admin/index', [
        "paginated" => []
      ])
    ]); */
  }
  public function loginPage()
  {
  
    Session::create();
    if (Session::get('admin')) {
      return Navigator::redirect('/admin/dashboard');
    }

    return Response::view('admin/admins/login', 'admin-layout', [
      "errors" => Session::get('errors') ?? []
    ]);
  }

  public function store()
  {
    Session::create();
    Log::info('Admin login próbálkozás', ['email' => $this->request->key('email')], 'admin');
    try {
      $validated = $this->request->validate([
        "email" => ['required', 'email'],
        "password" => ['required'],
      ]);

    
    } catch (ValidationException $exception) {
      Log::warning('Admin login hiba', ['errors' => $exception->errors, 'old' => $exception->old], 'admin');
      Session::flash('errors', $exception->errors);
      Session::flash('old', $exception->old);
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }




    $email = $validated['email'] ?? null;
    $password = $validated['password'] ?? null;

    $authenticated = $this->auth->attempt($email, $password, 'admins');

    if (!$authenticated) {
      Session::flash('old', $this->request->all());
      return $this->toast->danger('Sikertelen bejelentkezés, kérjük próbálja meg más adatokkal!')->back();
    }

    // A login már megtörtént az attempt() metódusban, nem kell külön hívni
    return Navigator::redirect('/admin/dashboard');
  }

  public function logout()
  {
    Log::info('Admin kijelentkezés kísérlet.', ['admin_id' => session('admin')->id], 'admin');
    $this->auth::logout();
    Log::info('Admin kijelentkezés megtörtént', null, 'admin');
    return Navigator::redirect('/admin/login');
  }

  public function resetPassword()
  {
    try {
      $validated = $this->request->validate([
        'id' => ['required', 'integer'],
        'email' => ['required', 'email'],
        'current_password' => ['required'],
        'password' => ['required', 'password'],
        'password_confirmation' => ['required', 'password'],
      ]);
    } catch (ValidationException $e) {
      Session::flash('errors', $e->errors);
      Session::flash('old', $e->old);
      return $this->toast->danger('Hiba a beküldött adatokban.', 'Hiba')->back();
    }
    $admin = $this->Admin->findOneBy('email', $validated['email']);

    if (!$admin) {
      return $this->toast->danger('Nincs ilyen e-mail címmel regisztrált adminisztrátor.')->back();
    }


    if((int)$admin->id !== (int)$validated['id']) {
      return $this->toast->danger('Hibás adminisztrátor azonosító.')->back();
    }

    if($validated['password'] !== $validated['password_confirmation']) {
      return $this->toast->danger('Az új jelszó és a megerősítés nem egyezik.')->back();
    }


    if (!password_verify($validated['current_password'], $admin->password)) {
      return $this->toast->danger('A jelenlegi jelszó nem megfelelő.')->back();
    }

    if((int)$validated['id'] !== (int)session('admin')->id) {
      return $this->toast->danger('Nem módosíthatja más adminisztrátor jelszavát.')->back();
    }

    $this->Admin->update([
      'password' => password_hash($validated['password'], PASSWORD_DEFAULT),
    ], $admin->id);

    return $this->toast->success('Sikeres jelszóváltoztatás.')->redirect('/admin/login');
  }
}
