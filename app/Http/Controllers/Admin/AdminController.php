<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{



  public function index()
  {
    $paginated = $this->model->all('admins', true, $_GET['search'] ?? '', ['email']);
    echo view('components/admin-layout', [
      'root' => view('admin/index', [
        'title' => 'Vezérlőpult',
        'paginated' => arr_to_obj($paginated) ?? [],
      ])
    ]);
  }
}
