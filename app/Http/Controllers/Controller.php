<?php

namespace App\Http\Controllers;

use App\Models\Model;
use Core\Authenticator;
use Core\Request;
use Core\Toast;

class Controller
{
  protected $model;
  protected $toast;
  protected $auth;
  protected $request;
  
  public function __construct()
  {
    $this->model = new Model();
    $this->toast = new Toast();
    $this->request = new Request();
    $this->auth = new Authenticator();
  }


  public  function render()
  {
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
