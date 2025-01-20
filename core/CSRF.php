<?php

namespace Core;

class CSRF
{
  private $secret;
  private $lifeTime;
  private $token = 'dsadsddda';
  public $tokens = [];
  private $request;

  public function __construct()
  {
    $config = require base_path('config/auth.php');
    $this->secret = $config['csrf']['secret'];
    $this->lifeTime = $config['csrf']['lifetime'];
    $this->request = new Request();
  }

  // Token generálás
  public function generate()
  {
    $this->token = bin2hex(random_bytes(32));
    $encodedToken = hash_hmac('sha256',  $this->token, $this->secret);

    if (empty(Session::get('csrf'))) {
      if (isset($_SESSION['csrf']) && is_array($_SESSION['csrf'])) {
        // Ha már van csrf tömb a session-ben, akkor adjuk hozzá a generált tokent
        $_SESSION['csrf'][] =  [
          'token' => $encodedToken,
          'expiry' => time() + $this->lifeTime
        ];
      } else {
        // Ha még nincs csrf tömb a session-ben, akkor hozzunk létre újat és tegyük bele a generált tokent
        $_SESSION['csrf'] = [[
          'token' => $encodedToken,
          'expiry' => time() + $this->lifeTime
        ]];
      }
    }


    return $this;
  }

  public function check()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $post_csrf = $this->request->key('csrf');
    $session_csrf_arr = Session::get('csrf');



    if (!isset($post_csrf)) {
      abort(419);
      exit;
    }

    if (!$session_csrf_arr) {
      abort(419);
      exit;
    }
    foreach ($session_csrf_arr  as $session_csrf) {
      $token = hash_hmac('sha256', $_POST['csrf'], $this->secret);
      if (hash_equals($session_csrf['token'], $token)) {
        Session::unset('csrf');
        break;
      }
    }

    if (!$this->isSafeOrigin()) {
      exit;
    }

    Session::unset('csrf');
    return true;

    if (!$this->isSafeOrigin()) {
      abort(419);
      exit;
    }

    return true;
  }


  private function isSafeOrigin()
  {
    $config = require base_path('config/auth.php');
    $safe_origins = $config['csrf']['safe_origins'];

    // Ellenőrizzük az Origin fejlécet
    if (isset($_SERVER['HTTP_ORIGIN'])) {
      $origin = rtrim($_SERVER['HTTP_ORIGIN'], '/');
      if (in_array($origin, $safe_origins)) {
        return true;
      }
    }

    abort(419);
  }



  public function input()
  {
    echo "<input type='hidden' name='csrf' value='$this->token'>";
  }



  // Token eltávolítása
  public function remove()
  {
    // Token törlése a session-ból
    unset($_SESSION['csrf']);
  }

  private function destroy()
  {
    // Az összes token törlése
    $this->tokens = [];
  }
}
