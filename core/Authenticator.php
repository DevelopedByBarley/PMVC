<?php

namespace Core;

class Authenticator
{
  public function attempt($table, $email, $password)
  {
    $db = new Database();
    $user = $db->query("SELECT * FROM $table WHERE email = :email", [
      'email' => $email
    ])->find();

    if ($user) {
      if (password_verify($password, $user['password'])) {
        $this->login([
          'email' => $email
        ]);

        return true;
      }
    }

    return false;
  }

  public function login($entity)
  {
    $_SESSION[$entity] = [
      'email' => $entity['email']
    ];

    session_regenerate_id(true);
  }

  public function logout()
  {
    $_SESSION = [];
    session_destroy();

    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
}
