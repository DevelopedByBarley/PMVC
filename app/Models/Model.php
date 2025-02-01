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

  public function __construct()
  {
    $this->db = Database::getInstance();
  }

  public function join($table, $join_table_id_name, $id)
  {
    try {
      return $this->db->query("SELECT * FROM $table WHERE $join_table_id_name = :id", ['id' => $id])->get();
    } catch (Exception $e) {
      Log::critical("Database join error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  public function all($table, $withPaginate = false, $search = '' || [], $search_columns = [])
  {
    try {
      return !$withPaginate 
        ? $this->db->query("SELECT * FROM $table")->get() 
        : $this->db->prepare("SELECT * FROM $table")->paginate(25, null, $search, $search_columns);
    } catch (Exception $e) {
      Log::critical("Database all error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  public function findAll($table, $id)
  {
    try {
      return $this->db->query("SELECT * FROM $table WHERE id = :id", ['id' => $id])->get();
    } catch (Exception $e) {
      Log::critical("Database findAll error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  public function find($id, $table)
  {
    try {
      return $this->db->query("SELECT * FROM $table WHERE id = :id", ['id' => $id])->find();
    } catch (Exception $e) {
      Log::critical("Database find error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  public function destroy($table, $id)
  {
    try {
      return $this->db->query("DELETE FROM $table WHERE id = :id", ['id' => $id]);
    } catch (Exception $e) {
      Log::critical("Database destroy error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }

  public function destroyAll($table, $condition, $params = [])
  {
    try {
      return $this->db->query("DELETE FROM $table WHERE $condition", $params);
    } catch (Exception $e) {
      Log::critical("Database destroyAll error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
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

  public function paginateByQuery($base_query, $limit = 1, $search = [], $search_columns = [])
  {
    try {
      return $this->db->prepare($base_query)->paginate($limit, null, $search, $search_columns);
    } catch (Exception $e) {
      Log::critical("Database paginateByQuery error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  public function leftJoin($base_query, $joins = [], $conditions = null, $params = [], $fetch = 'multiple')
  {
    try {
      $query = $base_query;
      foreach ($joins as $join) {
        $query .= ' LEFT JOIN ' . $join['table'] . ' ON ' . $join['on'];
      }
      if ($conditions) {
        $query .= ' WHERE ' . $conditions;
      }
      return $fetch === 'multiple'
        ? $this->db->query($query, $params)->get(PDO::FETCH_ASSOC)
        : $this->db->query($query, $params)->find(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      Log::critical("Database leftJoin error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }
}
