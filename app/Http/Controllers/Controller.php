<?php

namespace App\Http\Controllers;

use Core\Authenticator;
use Core\CSRF;
use Core\Mailer;
use Core\Request;
use Core\Storage;
use Core\Toast;

class Controller
{
  protected $toast;
  protected $auth;
  protected $request;
  protected $mailer;
  protected $storage;
  protected $csrf;
  
  public function __construct()
  {
    $this->toast = new Toast();
    $this->request = new Request();
    $this->auth = new Authenticator();
    $this->mailer = new Mailer();
    $this->storage = new Storage();
    $this->csrf = new CSRF();
  }

}
