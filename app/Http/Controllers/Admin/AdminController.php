<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AdminInviteService;
use Core\Log;
use Core\Response;
use Core\Session;
use Core\ValidationException;

class AdminController extends Controller
{

  private $admin;
  private AdminInviteService $inviteService;


  public function __construct()
  {
    $this->admin = new Admin();
    $this->inviteService = new AdminInviteService();
    parent::__construct();
  }

  public function dashboard()
  {
    Log::info('Admin dashboard megnyitva', ['admin' => auth('admin')->email ?? null], 'admin');
    return Response::view('admin/dashboard/index', 'admin-layout', [
      'title' => 'Admin irányítópult',
      'documentation_route' => '/admin/documentation/dashboard',
    ]);
  }

  public function index()
  {
    Log::info('Admin lista megnyitva', [
      'search' => $_GET['search'] ?? null,
      'admin' => auth('admin')->email ?? null
    ], 'admin');
    return Response::view('admin/admins/index', 'admin-layout', [
      'title' => 'Adminok kezelése',
      'admins' => $this->paginator->data($this->admin->all())
        ->filter($_GET['search'] ?? '', ['name', 'email', 'role'])
        ->paginate(10)
    ]);
  }

  public function show($id)
  {
    $admin = $this->admin->find($id);
    if (!$admin) {
      Log::warning('Admin nem található', ['id' => $id, 'admin' => auth('admin')->email ?? null], 'admin');
      return abort(404);
    }

    Log::info('Admin megtekintése', ['id' => $id, 'email' => $admin->email ?? null, 'admin' => auth('admin')->email ?? null], 'admin');
    return Response::view('admin/admins/show', 'admin-layout', [
      'title' => 'Admin részletek',
      'admin' => $admin,
    ]);
  }

  public function create()
  {
    Log::info('Admin létrehozása oldal megnyitva', ['admin' => auth('admin')->email ?? null], 'admin');
    return Response::view('admin/admins/create', 'admin-layout', [
      'title' => 'Új admin létrehozása',
    ]);
  }

  public function store()
  {

    Log::info('Admin létrehozása folyamatban...', ['admin' => auth('admin')->email], 'admin');

    $validated =  $this->request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'role' => ['required'],
    ]);

    // Create token for email verification
    $adminId = $this->admin->createAdmin($validated);

    if (!$adminId) {
      Log::error('Hiba történt az admin létrehozása során.', ['admin' => auth('admin')->email], 'admin');
      return $this->toast->danger('Hiba történt az admin létrehozása során.')->back();
    }

    $token = $this->inviteService->createInvite($validated, $adminId, 48);


    $invite_url = $_ENV['APP_URL'] . '/admin/invite/accept?token=' . $token;


    $this->mailer->prepare($validated['email'], 'Meghívó admin fiók létrehozására')->template('admin-invite', [
      'name' => $validated['name'],
      'invite_url' => $invite_url,
    ])->send();

    Log::info('Meghívó e-mail elküldve: ' . $validated['email'], ['admin' => $validated['email']], 'admin');

    return $this->toast->success('Meghívó e-mail elküldve.')->redirect('/admins/create');
  }

  public function edit($id)
  {
    $admin = $this->admin->find($id);
    if (!$admin) {
      Log::warning('Admin nem található szerkesztéshez', ['id' => $id, 'admin' => auth('admin')->email ?? null], 'admin');
      return abort(404);
    }

    Log::info('Admin szerkesztés oldal megnyitva', ['id' => $id, 'email' => $admin->email ?? null, 'admin' => auth('admin')->email ?? null], 'admin');
    return Response::view('admin/admins/edit', 'admin-layout', [
      'title' => 'Admin szerkesztése',
      'admin' => $admin,
    ]);
  }

  public function destroy($id)
  {
    $admin = $this->admin->find($id);
    if (!$admin) {
      Log::warning('Admin nem található törléshez', ['id' => $id, 'admin' => auth('admin')->email ?? null], 'admin');
      return abort(404);
    }

    if ($admin->id === auth('admin')->id) {
      return $this->toast->danger('Nem törölheti saját magát!')->back();
    }

    Log::info('Admin törlése: ' . $admin->email, ['admin' => auth('admin')->email], 'admin');
    $this->admin->destroy($id);
    Log::info('Admin törlés befejezve', ['deleted_id' => $id, 'deleted_email' => $admin->email ?? null, 'admin' => auth('admin')->email ?? null], 'admin');

    return $this->toast->success('Admin sikeresen törölve.')->back();
  }

  public function update($id)
  {
    $admin = $this->admin->find($id);
    if (!$admin) {
      Log::warning('Admin nem található frissítéshez', ['id' => $id, 'admin' => auth('admin')->email ?? null], 'admin');
      return abort(404);
    }

    Log::info('Admin frissítése: ' . $admin->email, ['admin' => auth('admin')->email], 'admin');

    $validated =  $this->request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'role' => ['required'],
    ]);

    $updated = $this->admin->update($validated, $id);

    if(!$updated) {
      Log::error('Hiba történt az admin frissítése során: ' . $admin->email, ['admin' => auth('admin')->user()->email]);
      return $this->toast->danger('Hiba történt az admin frissítése során.')->back();
    }

    Log::info('Admin sikeresen frissítve', ['id' => $id, 'email' => $admin->email ?? null, 'admin' => auth('admin')->email ?? null], 'admin');

    return $this->toast->success('Admin sikeresen frissítve.')->redirect('/admins/edit/' . $id);
  }


  public function acceptInviteForm()
  {
    $token = $this->request->get('token');

    Log::info('Meghívó elfogadó oldal megnyitva', ['has_token' => isset($token), 'admin' => auth('admin')->email ?? null], 'admin');

    $invite = $this->inviteService->validateInvite($token);

    if (!$invite) {
      Log::warning('Érvénytelen meghívó token a formhoz', ['has_token' => isset($token)], 'admin');
      return abort(404);
    }

    return Response::view('admin/admins/accept-invite', 'admin-layout', [
      'title' => 'Admin meghívó elfogadása',
      'invite' => $invite,
      'token' => $token,
    ]);
  }


  public function acceptInvite()
  {
    Log::info('Admin meghívó elfogadás indítása', [], 'admin');
    try {
      $validated =  $this->request->validate([
        'token' => ['required', 'string'],
        'password' => ['required', 'string', 'password'],
        'password_confirmation' => ['required', 'string', 'password'],
      ]);
    } catch (ValidationException $e) {
      Log::warning('Meghívó elfogadás validáció hiba', ['errors' => $e->errors ?? null], 'admin');
      Session::flash('errors', $e->errors);
      Session::flash('old', $e->old);
      return $this->toast->danger('Hiba a beküldött adatokban.')->back();
    }

    if ($validated['password'] !== $validated['password_confirmation']) {
      return $this->toast->danger('A jelszavak nem egyeznek.')->back();
    }


    // dd($validated);

    try {
      // If passwords match, and token is valid, reset password and activate admin account
      $validated_token = $this->inviteService->validateInvite($validated['token']);
      if (!$validated_token['id']) {
        Log::warning('Érvénytelen vagy lejárt meghívó token', [], 'admin');
        return $this->toast->danger('Érvénytelen vagy lejárt meghívó token.')->back();
      }

      $admin = $this->admin->find($validated_token['admin_id']);

      $this->admin->update([
        'name' => $admin->name,
        'email' => $admin->email,
        'role' => $admin->role,
        'password' => password_hash($validated['password'], PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'email_verified_at' => date('Y-m-d H:i:s'),
      ], $admin->id);

      // Deactivate token 
      $this->inviteService->deleteToken($validated['token']);

      Log::info('Admin meghívó elfogadva', ['admin_id' => $admin->id], 'admin');

      return $this->toast->success('Admin fiók sikeresen elfogadva.')->redirect('/admin/login');
    } catch (\RuntimeException $e) {
      Log::error('Hiba történt meghívó elfogadásakor', ['message' => $e->getMessage()], 'admin');
      return $this->toast->danger($e->getMessage())->back();
    }
  }
}
