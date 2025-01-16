<?php

namespace App\Http\Controllers;

use Core\Toast;

class Controller
{
  public static function render()
  {
    session_start();
    
    echo view('components/layout', [
      'root' => view('welcome', [])
    ]);
  }
  public static function renderTest($id)
  {
    session_start();

    echo view('components/layout', [
      'root' => view('welcome', ["id" => $id])
    ]);
  }

}
