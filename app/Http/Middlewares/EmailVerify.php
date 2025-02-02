<?php

namespace App\Http\Middlewares;

use Core\Database;
use Core\Session;


// You can use after the auth, cause you need a user in session for that middleware like that ['auth', 'verify'];

class EmailVerify
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::getInstance();
  }

  public function handle()
  {
    $user = Session::get('user');
    if (!$user) {
      header('Location: /login');
      exit();
    } else {
      if (is_null($user->email_verified_at)) {
        header('Location: /login');
        unset($_SESSION['user']);
        exit;
      }
    }
  }
}
