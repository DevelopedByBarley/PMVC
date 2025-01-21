<?php

namespace Core;

use PDO;

class Database
{
    use Singleton;

    public $connection;
    public $statement;
    private $query = '';

    public function __construct()
    {
        $config = require base_path('config/database.php');

        $dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['charset'];

        $this->connection = new PDO($dsn, $config['name'], $config['password'], [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }


    // FOR JOIN --------
    public function prepare($query)
    {
        $this->query = $query;
        return $this;
    }

    public function leftJoin($table, $firstColumn, $operator, $secondColumn)
    {
        $this->query .= " LEFT JOIN $table ON $firstColumn $operator $secondColumn";
        return $this;
    }

    public function where($column, $operator, $value)
    {
        if (stripos($this->query, 'WHERE') === false) {
            $this->query .= " WHERE $column $operator :$column";
        } else {
            $this->query .= " AND $column $operator :$column";
        }

        return $this;
    }

    public function execute($params = [])
    {
        $this->statement = $this->connection->prepare($this->query);
        $this->statement->execute($params);
        return $this;
    }


    // -----------------


    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    public function get($ret_type = PDO::FETCH_OBJ)
    {
        return $this->statement->fetchAll($ret_type);
    }

    public function find($ret_type = PDO::FETCH_OBJ)
    {
        return $this->statement->fetch($ret_type);
    }

    public function findOrFail($ret_type = PDO::FETCH_OBJ)
    {
        $result = $this->find($ret_type);

        if (! $result) {
            abort();
        }

        return $result;
    }

    public function debug()
    {
        return $this->query;
    }

    public function paginate($itemsPerPage = 10, $currentPage = null, $search = [], $search_columns = ['email'])
    {
        $currentPage = $currentPage ?? ($_GET['offset'] ?? 1);
        $currentPage = max((int)$currentPage, 1);
    
        // Alapértelmezett keresési feltétel
        $searchCondition = '';
        $searchParams = [];
    
        // Ha a keresés string, alkalmazzuk az összes keresési oszlopra
        if (is_string($search) && !empty($search_columns)) {
            $searchParts = [];
            foreach ($search_columns as $column) {
                $searchParts[] = "$column LIKE :search";
            }
            $searchCondition = ' WHERE ' . implode(' OR ', $searchParts);
            $searchParams[':search'] = "%$search%";
        }
    
        // Ha tömb a keresés, vizsgáljuk meg a kulcsokat és értékeket
        if (is_array($search) && !empty($search)) {
            $searchParts = [];
            foreach ($search as $key => $value) {
                if (in_array($key, $search_columns) && !empty($value)) {
                    $searchParts[] = "$key LIKE :$key";
                    $searchParams[":$key"] = "%$value%";
                }
            }
    
            if (!empty($searchParts)) {
                $searchCondition = ' WHERE ' . implode(' AND ', $searchParts);
            }
        }
    
        // Frissített lekérdezések a kereséshez
        $countQuery = preg_replace('/SELECT .*? FROM/', 'SELECT COUNT(*) as total FROM', $this->query, 1) . $searchCondition;
        $paginatedQuery = $this->query . $searchCondition . " LIMIT :limit OFFSET :offset";
    
        // Összes rekord lekérdezése
        $countStmt = $this->connection->prepare($countQuery);
        foreach ($searchParams as $param => $value) {
            $countStmt->bindValue($param, $value, PDO::PARAM_STR);
        }
        $countStmt->execute();
        $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
        $totalPages = (int)ceil($totalRecords / $itemsPerPage);
        $offset = ($currentPage - 1) * $itemsPerPage;
    
        // Adatok lekérdezése
        $this->statement = $this->connection->prepare($paginatedQuery);
        foreach ($searchParams as $param => $value) {
            $this->statement->bindValue($param, $value, PDO::PARAM_STR);
        }
        $this->statement->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $this->statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $this->statement->execute();
    
        return [
            'data' => $this->statement->fetchAll(PDO::FETCH_ASSOC),
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'items_per_page' => $itemsPerPage,
        ];
    }
    
    
}
