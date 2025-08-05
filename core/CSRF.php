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

    // Session elindítása ha még nincs
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }


  public function generate()
  {
    $this->token = bin2hex(random_bytes(32));
    $encodedToken = hash_hmac('sha256', $this->token, $this->secret);

    // Lejárt tokenek törlése
    $this->cleanExpiredTokens();

    if (isset($_SESSION['csrf']) && is_array($_SESSION['csrf'])) {
      $_SESSION['csrf'][] = [
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
    $post_csrf = request('csrf') ?? getCsrfTokenFromHeader();
    $session_csrf_arr = Session::get('csrf');

    if (!isset($post_csrf) || empty($post_csrf)) {
      abort(419);
      return false;
    }

    if (!$session_csrf_arr || !is_array($session_csrf_arr)) {
      abort(419);
      return false;
    }

    $token = hash_hmac('sha256', $post_csrf, $this->secret);

    $valid_token_found = false;

    // Lejárt tokenek ellenőrzése és törlése
    foreach ($session_csrf_arr as $index => $session_csrf) {
      // Lejárt token ellenőrzése
      if (isset($session_csrf['expiry']) && $session_csrf['expiry'] < time()) {
        unset($_SESSION['csrf'][$index]);
        continue;
      }

      if (hash_equals($session_csrf['token'], $token)) {
        $valid_token_found = true;
        // Használt token eltávolítása (one-time use)
        unset($_SESSION['csrf'][$index]);
        break;
      }
    }

    if (!$valid_token_found) {
      abort(419);
      return false;
    }

    if (!$this->isSafeOrigin()) {
      abort(419);
      return false;
    }

    // Session array újraindexelése

    $_SESSION['csrf'] = array_values($_SESSION['csrf']);

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
    } else {
      $host = $_SERVER['HTTP_HOST'] ?? '';

      return in_array($host, $safe_origins);
    }

    dd('Unsafe origin detected');
    abort(419);
  }



  public function input()
  {
    if (empty($this->token)) {
      $this->generate();
    }
    echo "<input type='hidden' name='csrf' value='{$this->token}'>";
  }

  public function getToken()
  {
    if (empty($this->token)) {
      $this->generate();
    }
    return $this->token;
  }

  // Lejárt tokenek törlése
  private function cleanExpiredTokens()
  {
    if (isset($_SESSION['csrf']) && is_array($_SESSION['csrf'])) {
      foreach ($_SESSION['csrf'] as $index => $token) {
        if (isset($token['expiry']) && $token['expiry'] < time()) {
          unset($_SESSION['csrf'][$index]);
        }
      }
      $_SESSION['csrf'] = array_values($_SESSION['csrf']);
    }
  }



  // Token eltávolítása
  public function remove()
  {
    unset($_SESSION['csrf']);
    $this->token = '';
  }

  private function destroy()
  {
    $this->tokens = [];
    $this->token = '';
  }
}
