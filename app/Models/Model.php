<?php

namespace App\Models;

use Core\Database;

class Model
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::getInstance();
  }

  public function find($id, $table)
  {
    return $this->db->query("SELECT * FROM $table WHERE id = :id", ['id' => $id])->find();
  }
}
