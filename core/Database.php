<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    use Singleton;

    public $connection;
    public $statement;
    private $query = '';
    private $toast;

    public function __construct()
    {
        $config = require base_path('config/database.php');
        $this->toast = new Toast();

        try {
            $dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['charset'];

            $this->connection = new PDO($dsn, $config['name'], $config['password'], [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            Log::error('Database connection fail!', 'Fail in Database class construct function with message:' . $e->getMessage());
            dd($e);
        }
    }


    /**
     * Sets the SQL query and returns the current instance.
     *
     * @param string $query The SQL query string.
     * @return self The current instance for method chaining.
     */

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
        try {
            $this->statement = $this->connection->prepare($this->query);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            Log::error('Database fail in execute method', 'Fail in Database class execute function with message:' . $e->getMessage());
            $this->toast->danger('Általános rendszer hiba')->back();
        }
    }

    /**
     * Executes a database query with the given parameters.
     *
     * @param string $query The SQL query string to be executed.
     * @param array $params The parameters to bind to the query (optional).
     * @return self The current instance for method chaining.
     * @throws DatabaseException If there is a database error during query execution.
     */

    public function query($query, $params = [])
    {
        try {
            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            Log::error('Database fail in query method', 'Fail in Database class query function with message:' . $e->getMessage());
            $this->toast->danger('Általános rendszer hiba')->back();
        }
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
        try {
            $currentPage = $currentPage ?? ($_GET['offset'] ?? 1);
            $currentPage = max((int)$currentPage, 1);

            $searchCondition = '';
            $searchParams = [];


            $countQuery = preg_replace('/SELECT .*? FROM/', 'SELECT COUNT(*) as total FROM', $this->query, 1) . $searchCondition;
            $paginatedQuery = $this->query . $searchCondition . " LIMIT :limit OFFSET :offset";

            $countStmt = $this->connection->prepare($countQuery);
            foreach ($searchParams as $param => $value) {
                $countStmt->bindValue($param, $value, PDO::PARAM_STR);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            $totalPages = (int)ceil($totalRecords / $itemsPerPage);
            $offset = ($currentPage - 1) * $itemsPerPage;

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
        } catch (PDOException $e) {
            Log::error('Database fail in paginate method', 'Query: ' . $this->query . ' Error: ' . $e->getMessage());
            $this->toast->danger('Általános rendszer hiba')->back();
        }
    }
}
