<?php

namespace Core;

class CSRF
{
  private $secret;
  private $lifeTime;
  private $token = '';
  public $tokens = [];

  public function __construct()
  {
    $config = require base_path('config/auth.php');
    $this->secret = $config['csrf']['secret'];
    $this->lifeTime = $config['csrf']['lifetime'];
  }

  public function generate()
  {
    $this->token = bin2hex(random_bytes(32));
    $encodedToken = hash_hmac('sha256',  $this->token, $this->secret);

    if (isset($_SESSION['csrf']) && is_array($_SESSION['csrf'])) {
      $_SESSION['csrf'][] =  [
        'token' => $encodedToken,
        'expiry' => time() + $this->lifeTime
      ];
    } else {
      $_SESSION['csrf'] = [[
        'token' => $encodedToken,
        'expiry' => time() + $this->lifeTime
      ]];
    }

    return $this;
  }

  public function check()
  {
    $post_csrf = request('csrf');
    $session_csrf_arr = Session::get('csrf');

    if (!isset($post_csrf)) {
      abort(419);
      exit;
    }

    if (!$session_csrf_arr) {
      abort(419);
      exit;
    }

    $token = hash_hmac('sha256', $post_csrf, $this->secret);

    $valid_token_found = false;

    foreach ($session_csrf_arr as $session_csrf) {
      if (hash_equals($session_csrf['token'], $token)) {
        $valid_token_found = true;
        break;
      }
    }

    if (!$valid_token_found) {
      abort(419);
      exit;
    }

    if (!$this->isSafeOrigin()) {
      abort(419);
      exit;
    }

    Session::unset('csrf');

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
