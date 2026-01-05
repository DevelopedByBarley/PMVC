<?php

namespace Core;

use Exception;
use PDO;
use PDOException;

class Database
{
    use Singleton;

    public $connection;
    public $statement;
    private $query = '';
    private $redirected = false;
    private $paginator;

    public function __construct()
    {
        $config = require base_path('config/database.php');
        $this->paginator = new Paginator();

        try {
            $dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['charset'];

            $this->connection = new PDO($dsn, $config['name'], $config['password'], [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            Log::critical('Database connection fail!', 'Fail in Database class construct function with message:' . $e->getMessage());
            dd($e->getMessage());
        }
    }

    /**
     * Sets the SQL query and returns the current instance for method chaining.
     *
     * @param string $query The SQL query string.
     * @return self The current instance for method chaining.
     */
    public function prepare($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Adds a LEFT JOIN clause to the current SQL query and returns the current instance for method chaining.
     *
     * @param string $table The table to join.
     * @param string $first_column The first column for the join condition.
     * @param string $operator The operator used in the join condition.
     * @param string $second_column The second column for the join condition.
     * @return self The current instance for method chaining.
     */
    public function leftJoin($table, $conditions)
    {
        $onClause = [];
        foreach ($conditions as $condition) {
            $onClause[] = "{$condition[0]} {$condition[1]} {$condition[2]}";
        }
        $this->query .= " LEFT JOIN $table ON " . implode(' AND ', $onClause);
        return $this;
    }

    /**
     * Adds a WHERE clause to the current SQL query and returns the current instance for method chaining.
     *
     * @param string $column The column to apply the condition on.
     * @param string $operator The operator to compare the column value.
     * @param mixed $value The value to compare the column against.
     * @return self The current instance for method chaining.
     */
    public function where($column, $operator, $value)
    {
        if (stripos($this->query, 'WHERE') === false) {
            $this->query .= " WHERE $column $operator $value";
        } else {
            $this->query .= " AND $column $operator $value";
        }

        return $this;
    }

    public function whereNotNull($column)
    {
        if (stripos($this->query, 'WHERE') === false) {
            $this->query .= " WHERE $column IS NOT NULL";
        } else {
            $this->query .= " AND $column IS NOT NULL";
        }

        return $this;
    }



    public function whereNull($column)
    {
        if (stripos($this->query, 'WHERE') === false) {
            $this->query .= " WHERE $column IS NULL";
        } else {
            $this->query .= " AND $column IS NULL";
        }

        return $this;
    }



    /**
     * Executes the current SQL query with the provided parameters.
     *
     * @param array $params The parameters to bind to the query.
     * @return self The current instance for method chaining.
     */
    public function execute($params = [])
    {
        try {
            $this->statement = $this->connection->prepare($this->query);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            Log::critical('Database fail in execute method', 'Fail in Database class execute function with message:' . $e->getMessage());
            dd($e->getMessage());
        }
    }

    /**
     * Executes a custom SQL query with the provided parameters.
     *
     * @param string $query The SQL query string to execute.
     * @param array $params The parameters to bind to the query (optional).
     * @return self The current instance for method chaining.
     * @throws DatabaseException If a database error occurs.
     */
    public function query($query, $params = [])
    {
        try {
            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            Log::critical('Database fail in query method', 'Fail in Database class query function with message:' . $e->getMessage());
            dd($e->getMessage());
        }
    }

    public function getLastInsertedId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Fetches all results from the executed query.
     *
     * @param int $ret_type The fetch mode (default is PDO::FETCH_OBJ).
     * @return array The fetched results.
     */
    public function get($ret_type = PDO::FETCH_OBJ)
    {
        return $this->statement->fetchAll($ret_type);
    }

    /**
     * Fetches the first result from the executed query.
     *
     * @param int $ret_type The fetch mode (default is PDO::FETCH_OBJ).
     * @return mixed The fetched result.
     */
    public function find($ret_type = PDO::FETCH_OBJ)
    {
        return $this->statement->fetch($ret_type);
    }

    /**
     * Fetches the first result or throws an error if no result is found.
     *
     * @param int $ret_type The fetch mode (default is PDO::FETCH_OBJ).
     * @return mixed The fetched result.
     * @throws DatabaseException If no result is found.
     */
    public function findOrFail($ret_type = PDO::FETCH_OBJ)
    {
        $result = $this->find($ret_type);

        if (!$result) {
            abort();
        }

        return $result;
    }

    /**
     * Returns the current SQL query as a string for debugging.
     *
     * @return string The current SQL query.
     */
    public function debug()
    {
        return $this->query;
    }

    public function selectCount($table)
    {
        $this->statement = $this->prepare("SELECT COUNT(*) AS count FROM $table");
        return $this;
    }

    public function paginate(int $limit = 25, $search = [], array $search_columns = [])
    {
        // Biztosítjuk, hogy a search paraméter megfelelő típusú legyen a Paginator számára
        $data = $this->statement->fetchAll(PDO::FETCH_OBJ);
        return $this->paginator->data($data)->filter($search, $search_columns)->paginate($limit);
    }


    /*   public function paginate($itemsPerPage = 10, $currentPage = null, $search = [], $search_columns = [])
    {
        try {
            $currentPage = $currentPage ?? ($_GET['offset'] ?? 1);
            $currentPage = max((int)$currentPage, 1);

            $searchCondition = '';
            $searchParams = [];

            if (is_string($search) && !empty($search_columns)) {
                $searchParts = [];
                foreach ($search_columns as $column) {
                    $searchParts[] = "$column LIKE :search";
                }
                $searchCondition = ' WHERE ' . implode(' OR ', $searchParts);
                $searchParams[':search'] = "%$search%";
            }

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

            return (object)[
                'data' => (object)$this->statement->fetchAll(PDO::FETCH_OBJ),
                'total_records' => $totalRecords,
                'total_pages' => $totalPages,
                'current_page' => $currentPage,
                'items_per_page' => $itemsPerPage,
            ];
        } catch (Exception $e) {
            dd("Pagination query failed: " . $e->getMessage());
        }
    } */
}
