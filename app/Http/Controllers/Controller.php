<?php

namespace App\Http\Controllers;

use App\Models\Model;
use Core\Authenticator;
use Core\Mailer;
use Core\Request;
use Core\Toast;

class Controller
{
  protected $model;
  protected $toast;
  protected $auth;
  protected $request;
  protected $mailer;
  
  public function __construct()
  {
    $this->model = new Model();
    $this->toast = new Toast();
    $this->request = new Request();
    $this->auth = new Authenticator();
    $this->mailer = new Mailer();
  }

}
