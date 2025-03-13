<?php

namespace Core;

class Token
{
  private $data;
  private $secret;
  private $lifeTime;
  private $token;

  public function __construct()
  {
    $config = require base_path('config/auth.php');
    $this->secret = $config['token']['secret'];
    $this->lifeTime = $config['token']['lifetime'];
  }
  
  public function from($data)
  {
    $this->data = base64_encode($data);

    return $this;
  }

  public function generate()
  {
    $this->token = hash_hmac('sha256',  $this->data, $this->secret);

    return $this;
  }

  public function get()
  {
    return $this->data;
  }

  public function token()
  {
    return $this->token;
  }

  public function all()
  {
    return [
      $this->data,
      $this->token,
      time() + $this->lifeTime
    ];
  }


  public function attempt($based, $token)
  {
    if(!$based || !$token) return false;
    
    $encoded =  hash_hmac('sha256', $based, $this->secret);

    if (hash_equals($encoded, $token)) {
      return true;
    }

    return false;
  }
}
