<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Core\Database;
use Core\Response;

class AdminController extends Controller
{

  private $db;
  private $Admin;

  public function __construct()
  {
    parent::__construct();
    $this->Admin = new Admin();
    $this->db = Database::getInstance();
  }


  public function index()
  {
    $search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
    $paginated = $this->Admin->all(true, $search, ['name', 'email', 'created_at']);


    return Response::view('admin/index', 'admin-layout', [
      'title' => 'Vezérlőpult',
      'paginated' => arr_to_obj($paginated) ?? [],
    ]);
  }
}
