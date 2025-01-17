<?php

namespace App\Http\Controllers;

use App\Models\Model;
use Core\Authenticator;
use Core\Toast;

class Controller
{
  protected $model;
  protected $toast;
  protected $auth;
  
  public function __construct()
  {
    $this->model = new Model();
    $this->toast = new Toast();
    $this->auth = new Authenticator();
  }

  public  function render()
  {
    session_start();

    echo view('components/layout', [
      'root' => view('welcome', [])
    ]);
  }
  public  function renderTest($id)
  {
    session_start();

    echo view('components/layout', [
      'root' => view('welcome', ["id" => $id])
    ]);
  }
}
