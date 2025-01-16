<?php

namespace App\Http\Controllers;

use App\Models\Model;
use Core\Toast;

class Controller
{
  protected $model;
  protected $toast;
  
  public function __construct()
  {
    $this->model = new Model();
    $this->toast = new Toast();
  }

  public  function render()
  {
    session_start();
    $user = $this->model->find(1, 'users');

    if (!$user) {
      $this->toast->danger()->redirect('/asd');
    }

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
