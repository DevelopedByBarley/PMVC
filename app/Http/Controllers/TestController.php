<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Core\Database;
use Core\Response;

class TestController extends Controller
{
  private Database $db;
  protected $post;

  public function __construct()
  {
    $this->db = Database::getInstance();
    $this->post = new Post();
  }




  public function index()
  {


    $this->post->updateById(6, ['body' => 'Updated Post Title']);
    return Response::json([
      'message' => 'Test index method called successfully!',
    ]);
  }
  public function show($id)
  {
    echo json_encode([
      'message' => 'Test show method called successfully!',
      'id' => $id
    ]);
  }
  public function create()
  {
    echo json_encode([
      'message' => 'Test create method called successfully!',
    ]);
  }
  public function edit()
  {
    echo json_encode([
      'message' => 'Test edit method called successfully!'
    ]);
  }
  public function store()
  {
    echo json_encode([
      'message' => 'Test store method called successfully!'
    ]);
  }
  public function update()
  {
    echo json_encode([
      'message' => 'Test update method called successfully!'
    ]);
  }
  public function destroy()
  {
    echo json_encode([
      'message' => 'Test destroy method called successfully!'
    ]);
  }
}
