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


  public function all($withPaginate = false, $search = [], $search_columns = [], $orderBy = null, $orderDirection = 'ASC')
  {
    try {
      return !$withPaginate
        ? $this->db->query("SELECT * FROM $this->table" . ($orderBy ? " ORDER BY $orderBy $orderDirection" : ""))->get()
        : $this->db->query("SELECT * FROM $this->table" . ($orderBy ? " ORDER BY $orderBy $orderDirection" : ""))->paginate(25, $search, $search_columns);
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

  public function findOrFail($id)
  {
    $record = $this->find($id);
    if (!$record || empty($record)) {
      abort(404, "Record with ID $id not found in " . $this->table . " table.");
    }
    return $record;
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

  /**
   * Alternative update method with ID
   */
  public function updateById($id, array $data, array $exceptions = [])
  {
    return $this->updateTable($this->table, $data, "id = $id", $exceptions);
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

      // Check if condition contains placeholders or raw SQL
      if (strpos($condition, ':') !== false || strpos($condition, '=') !== false) {
        // Raw condition like "id = 123" or with placeholders
        $sql = "UPDATE $table SET $set WHERE $condition";
        $params = $filteredData;
      } else {
        // Simple condition like just "123" - assume it's an ID
        $sql = "UPDATE $table SET $set WHERE id = :condition_id";
        $params = array_merge($filteredData, ['condition_id' => $condition]);
      }

      return (object)[
        'success' => $this->db->query($sql, $params)->rowCount() > 0,
        'updated_data' => $filteredData
      ];
    } catch (Exception $e) {
      Log::critical("Database update error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }

  public function destroy($id)
  {
    try {
      return (object)[
        'success' => $this->db->query("DELETE FROM $this->table WHERE id = :id", ['id' => $id])->rowCount() > 0,
        'deleted_id' => $id
      ];
    } catch (Exception $e) {
      Log::critical("Database destroy error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }

  public function destroyAll()
  {
    try {
      return (object)[
        'success' => $this->db->query("DELETE FROM $this->table")->rowCount() > 0,
      ];
    } catch (Exception $e) {
      Log::critical("Database destroyAll error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Find records by specific column and value
   */
  public function findBy(string $column, $value)
  {
    try {
      return $this->db->query("SELECT * FROM $this->table WHERE $column = :value", ['value' => $value])->get();
    } catch (Exception $e) {
      Log::critical("Database findBy error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Find first record by specific column and value
   */
  public function findOneBy(string $column, $value)
  {
    try {
      return $this->db->query("SELECT * FROM $this->table WHERE $column = :value", ['value' => $value])->find();
    } catch (Exception $e) {
      Log::critical("Database findOneBy error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Count all records in the table
   */
  public function count(): int
  {
    try {
      $result = $this->db->query("SELECT COUNT(*) as count FROM $this->table")->find();
      return $result ? (int)$result->count : 0;
    } catch (Exception $e) {
      Log::critical("Database count error in Model.", "Database error: " . $e->getMessage());
      return 0;
    }
  }

  /**
   * Check if record exists by ID
   */
  public function exists($id): bool
  {
    try {
      $result = $this->db->query("SELECT 1 FROM $this->table WHERE id = :id LIMIT 1", ['id' => $id])->find();
      return !empty($result);
    } catch (Exception $e) {
      Log::critical("Database exists error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Get records with custom WHERE conditions
   */
  public function where(array $conditions = [])
  {
    try {
      if (empty($conditions)) {
        return $this->all();
      }

      $whereClause = [];
      $params = [];

      foreach ($conditions as $column => $value) {
        $whereClause[] = "$column = :$column";
        $params[$column] = $value;
      }

      $sql = "SELECT * FROM $this->table WHERE " . implode(' AND ', $whereClause);
      return $this->db->query($sql, $params)->get();
    } catch (Exception $e) {
      Log::critical("Database where error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Get records with ORDER BY clause
   */
  public function orderBy(string $column, string $direction = 'ASC')
  {
    try {
      $direction = strtoupper($direction);
      if (!in_array($direction, ['ASC', 'DESC'])) {
        $direction = 'ASC';
      }

      return $this->db->query("SELECT * FROM $this->table ORDER BY $column $direction")->get();
    } catch (Exception $e) {
      Log::critical("Database orderBy error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Get records with LIMIT
   */
  public function limit(int $limit, int $offset = 0)
  {
    try {
      return $this->db->query("SELECT * FROM $this->table LIMIT $limit OFFSET $offset")->get();
    } catch (Exception $e) {
      Log::critical("Database limit error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Get first record
   */
  public function first()
  {
    try {
      return $this->db->query("SELECT * FROM $this->table LIMIT 1")->find();
    } catch (Exception $e) {
      Log::critical("Database first error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Get last record (assumes id column exists)
   */
  public function last()
  {
    try {
      return $this->db->query("SELECT * FROM $this->table ORDER BY id DESC LIMIT 1")->find();
    } catch (Exception $e) {
      Log::critical("Database last error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Soft delete (assumes deleted_at column exists)
   */
  public function softDelete($id)
  {
    try {
      $data = ['deleted_at' => date('Y-m-d H:i:s')];
      return $this->db->query(
        "UPDATE $this->table SET deleted_at = :deleted_at WHERE id = :id",
        array_merge($data, ['id' => $id])
      );
    } catch (Exception $e) {
      Log::critical("Database softDelete error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Restore soft deleted record
   */
  public function restore($id)
  {
    try {
      return $this->db->query("UPDATE $this->table SET deleted_at = NULL WHERE id = :id", ['id' => $id]);
    } catch (Exception $e) {
      Log::critical("Database restore error in Model.", "Database error: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Get only non-deleted records (if using soft deletes)
   */
  public function withoutDeleted()
  {
    try {
      return $this->db->query("SELECT * FROM $this->table WHERE deleted_at IS NULL")->get();
    } catch (Exception $e) {
      Log::critical("Database withoutDeleted error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Execute raw SQL query
   */
  public function raw(string $sql, array $params = [])
  {
    try {
      return $this->db->query($sql, $params)->get();
    } catch (Exception $e) {
      Log::critical("Database raw query error in Model.", "Database error: " . $e->getMessage());
      return null;
    }
  }
}
