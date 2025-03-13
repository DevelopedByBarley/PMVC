<?php

namespace Core;

class Authenticator
{
  public function checkPassword($email, $password, $table = 'users', $verified = false)
  {
    $db = Database::getInstance();

    $user = $db->query("SELECT * FROM $table WHERE email = :email", [
      'email' => $email
    ])->find();



    if ($verified && is_null($user->email_verified_at)) {
      return false;
    };

    if ($user) {
      if (password_verify($password, $user->password)) {
        return $user;
      }
    }

    return false;
  }

  

  public function attempt($email, $password, $table = 'users', $verified = false)
  {
    $db = Database::getInstance();

    $user = $db->query("SELECT * FROM $table WHERE email = :email", [
      'email' => $email
    ])->find();



    if ($verified && is_null($user->email_verified_at)) {
      return false;
    };

    if ($user) {
      if (password_verify($password, $user->password)) {
        $this->login(rtrim($table, 's'), $email);

        return $user;
      }
    }

    return false;
  }

  public static function login($entity, $email)
  {
    $_SESSION[$entity] = (object)[
      'email' => $email
    ];

    session_regenerate_id(true);
  }

  public static function logout()
  {
    $_SESSION = [];
    session_destroy();

    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
}
