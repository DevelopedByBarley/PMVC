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

  public function join($table, $join_table_id_name, $id) {
    return $this->db->query("SELECT * FROM $table WHERE $join_table_id_name = :id", ['id' => $id])->get();
  }

  public function findAll($table, $id) {
    return $this->db->query("SELECT * FROM $table WHERE id = :id", ['id' => $id])->get();
  }

  public function find($id, $table)
  {
    return $this->db->query("SELECT * FROM $table WHERE id = :id", ['id' => $id])->find();
  }

  public function destroy($table, $id)
  {
      return $this->db->query("DELETE FROM $table WHERE id = :id", ['id' => $id]);
  }
  
  public function destroyAll($table, $id)
  {
      return $this->db->query("DELETE FROM $table");
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

  public function updateTable($table, $data, $condition)
  {
    $set = implode(", ", array_map(function ($key) {
      return "$key = :$key";
    }, array_keys($data)));

    $sql = "UPDATE $table SET $set WHERE $condition";

    return $this->db->query($sql, $data);
  }


}
