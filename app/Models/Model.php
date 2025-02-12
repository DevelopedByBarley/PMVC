<?php

namespace App\Models;

use Core\Database;
use Core\Log;
use Exception;
use InvalidArgumentException;
use PDO;

class Model
{
  protected $db;
  protected $table; // A model specifikus tábla neve

  public function __construct()
  {
    $this->db = Database::getInstance();

    if (!isset($this->table)) {
      throw new Exception("Table property is not defined in " . get_called_class());
    }
  }


  public function all($withPaginate = false, $search = '' || [], $search_columns = [])
  {
    try {
      return !$withPaginate
        ? $this->db->query("SELECT * FROM $this->table")->get()
        : $this->db->query("SELECT * FROM $this->table")->paginate(25, $search, $search_columns);
    } catch (Exception $e) {
      Log::critical("Database all error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  public function find($id)
  {
    try {
      return $this->db->query("SELECT * FROM $this->table WHERE id = :id", ['id' => $id])->find();
    } catch (Exception $e) {
      Log::critical("Database find error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }
  
  public function findAll($id)
  {
    try {
      return $this->db->query("SELECT * FROM $this->table WHERE id = :id", ['id' => $id])->get();
    } catch (Exception $e) {
      Log::critical("Database find error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  public function create(array $data, array $exceptions = [])
  {
    return $this->insertIntoTable($this->table, $data, $exceptions);
  }

  public function update(array $data, $condition, array $exceptions = [])
  {
    return $this->updateTable($this->table, $data, $condition, $exceptions);
  }

  public function insertIntoTable($table, $data, $exceptions = [])
  {
    try {
      $filteredData = array_diff_key($data, array_flip($exceptions));
      if (empty($filteredData)) {
        throw new InvalidArgumentException('No data to insert due to exceptions.');
      }

      $columns = implode(", ", array_keys($filteredData));
      $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($filteredData)));
      $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

      return $this->db->query($sql, $filteredData)->getLastInsertedId();
    } catch (Exception $e) {
      Log::critical("Database insert error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  public function updateTable($table, $data, $condition, $exceptions = [])
  {
    try {
      $filteredData = array_diff_key($data, array_flip($exceptions));
      if (empty($filteredData)) {
        throw new InvalidArgumentException('No data to update due to exceptions.');
      }

      $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($filteredData)));
      $sql = "UPDATE $table SET $set WHERE $condition";

      return $this->db->query($sql, $filteredData);
    } catch (Exception $e) {
      Log::critical("Database update error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }

  public function destroy($id)
  {
    try {
      return $this->db->query("DELETE FROM $this->table WHERE id = :id", ['id' => $id]);
    } catch (Exception $e) {
      Log::critical("Database destroy error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }
}
