<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Core\Database;

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
    $paginated = $this->db->prepare("SELECT users.*, posts.body AS post_body FROM users")
      ->leftJoin('posts', 'posts.user_id', '=', 'users.id')
      ->execute()
      ->paginate(10, null, $search ?? '', ['users.email', 'users.created_at']);


    echo view('components/admin-layout', [
      'root' => view('admin/index', [
        'title' => 'Vezérlőpult',
        'paginated' => arr_to_obj($paginated) ?? [],
      ])
    ]);
  }
}
