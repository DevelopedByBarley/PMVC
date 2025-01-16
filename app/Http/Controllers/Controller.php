<?php

namespace App\Http\Controllers;


class Controller
{
  public static function render()
  {
    echo view('components/layout', [
      'root' => view('welcome', [])
    ]);
  }
  public static function renderTest($id)
  {
    echo view('components/layout', [
      'root' => view('welcome', ["id" => $id])
    ]);
  }

}
