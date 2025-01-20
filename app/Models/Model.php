<?php

namespace App\Models;

use Core\Database;
use PDO;

class Model
{
  protected $db;

  public function __construct()
  {
    $db_config = base_path('config/database.php');
    $this->db = $db_config['active'] ? Database::getInstance() : null;
  }

  public function join($table, $join_table_id_name, $id)
  {
    return $this->db->query("SELECT * FROM $table WHERE $join_table_id_name = :id", ['id' => $id])->get();
  }


  public function all($table, $withPaginate = false)
  {
    return !$withPaginate ? $this->db->query("SELECT * FROM $table")->get() :  $this->db->prepare("SELECT * FROM $table")->paginate();
  }

  public function findAll($table, $id)
  {
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

  public function paginateByQuery($base_query, $limit = 10)
  {
    return $this->db->prepare($base_query)->paginate($limit);
  }

  public static function paginate($data)
  {
    $itemsPerPage = 5; // Egy oldalon lévő elemek száma
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Aktuális oldal
    $totalRecords = count($data); // Összes elem száma
    $totalPages = ceil($totalRecords / $itemsPerPage); // Összes oldal száma

    // Oldal adatok kiválasztása
    $startIndex = ($currentPage - 1) * $itemsPerPage;
    $dataForPage = array_slice($data, $startIndex, $itemsPerPage);

    // Válasz formázása
    return [
      'data' => $dataForPage,
      'total_records' => $totalRecords,
      'total_pages' => $totalPages,
      'current_page' => $currentPage,
      'items_per_page' => $itemsPerPage,
    ];
  }





  /* 
  $results = (new Model())->leftJoin( "SELECT users.id, users.name, posts.title AS post_title, notes.body FROM users", [
        ['table' => 'notes', 'on' => 'users.id = notes.user_id'],
        ['table' => 'posts', 'on' => 'notes.id = posts.note_id']
      ],
      'posts.title LIKE "%dsd%"'
  );
  */

  public function leftJoin($base_query, $joins = [], $conditions = null)
  {
    $query = $base_query;

    foreach ($joins as $join) {
      $query .= ' LEFT JOIN ' . $join['table'] . ' ON ' . $join['on'];
    }

    if ($conditions) {
      $query .= ' WHERE ' . $conditions;
    }

    return $this->db->query($query)->get(PDO::FETCH_ASSOC);
  }
}
