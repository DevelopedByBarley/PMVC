<?php

namespace App\Models;

use Core\Database;
use Illuminate\Support\Collection;

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

  public function insertIntoTable($table, $data)
  {
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_map(function ($key) {
      return ":$key";
    }, array_keys($data)));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

    return $this->db->query($sql, $data);
  }
}
