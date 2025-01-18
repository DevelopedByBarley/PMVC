<?php

namespace Core;

class CSRF
{
  private $secret;
  private $lifeTime;
  private $token = 'dsadsddda';
  public $tokens = [];

  public function __construct()
  {
    $config = require base_path('config/auth.php');
    $this->secret = $config['csrf']['secret'];
    $this->lifeTime = $config['csrf']['lifetime'];
  }

  // Token generálás
  public function generate()
  {
    $this->token = bin2hex(random_bytes(32));

    // A generált token tárolása a session-ben
    $_SESSION['csrf'] = [
      'token' => hash_hmac('sha256', $this->token, $this->secret),
      'expiry' => $this->lifeTime +  time()
    ];

    return $this;
  }

  public function check()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }


    if (!isset($_POST['csrf'])) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
      header('X-E-Message:  Problem in CSRF Token POST');
      abort(403);
      exit;
    }

    if (!Session::get('csrf')) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
      header('X-E-Message: SESSION problem in CSRF Token');
      abort(403);

      exit;
    }

    if ($_SESSION['csrf']['expiry'] < time()) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
      header('X-E-Message: CSRF token expired');
      abort(403);
      exit;
    }



    $token = hash_hmac('sha256', $_POST['csrf'], $this->secret);

    if (!hash_equals(Session::get('csrf')['token'], $token)) {
      abort(403);
    }

    if (!$this->isSafeOrigin()) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
      header('X-E-Message: UNSAFE origin');
      exit;
    }

    return true;
  }


  private function isSafeOrigin()
  {

    $safeOrigins = ['http://localhost:8080', 'http://localhost:9090'];

    // Ellenőrizzük az Origin fejlécet
    if (isset($_SERVER['HTTP_ORIGIN'])) {
      $origin = rtrim($_SERVER['HTTP_ORIGIN'], '/');
      if (in_array($origin, $safeOrigins)) {
        return true;
      }
    }

    return false;
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
