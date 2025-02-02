<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Database;
use Core\EmailVerify;
use Core\Session;
use Core\Token;

class EmailVerificationController extends Controller

{
  private $token;
  private $db;
  private $verification;


  public function __construct()
  {
    parent::__construct();
    $this->db = Database::getInstance();
    $this->token = new Token();
    $this->verification = new EmailVerify();
  }

  public function index()
  {
    $based_token = $_GET['token'];
    $email =  base64_decode($based_token);

    $user_id = $this->db->query("SELECT id FROM users WHERE email = :email", [':email' => $email])->findOrFail()->id;

    $token_data = $this->verification->token($user_id);

    if (strtotime($token_data->expires_at) < time()) {
      $this->toast->danger('A token már lejárt, itt egy új kérvényes oldalt kéne megjeleníteni.')->redirect('/register');
    };

    $is_success = $this->token->attempt($based_token, $token_data->token);

    if ($is_success) {
      $this->verification->deleteVerificationToken($user_id);
      $this->db->query("UPDATE `users` SET `email_verified_at` = CURRENT_TIMESTAMP() WHERE `users`.`id` = :id", [':id' => $user_id]);
      $this->toast->success('Regisztráció megerősítése sikeres, mostmár bejelentkezhet.')->redirect('/login');

    }
  }
}
