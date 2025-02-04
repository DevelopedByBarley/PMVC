<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
  public function create()
  {
    dd('hello');
  }

  public function show() {
    dd('Show');
  }
}
