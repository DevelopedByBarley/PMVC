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
}
