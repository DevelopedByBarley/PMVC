<?php

namespace Core;

use Exception;
use PDO;
use PDOException;

class Database
{
    use Singleton;

    public PDO $connection;
    public $statement;
    private string $query = '';
    private array $bindings = [];
    private Paginator $paginator;

    public function __construct()
    {
        $config = require base_path('config/database.php');
        $this->paginator = new Paginator();

        try {
            $dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['charset'];

            $this->connection = new PDO($dsn, $config['name'], $config['password'], [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            
            // Növeljük a max_allowed_packet értéket 64MB-ra
            $this->connection->exec("SET GLOBAL max_allowed_packet=67108864");
        } catch (PDOException $e) {
            Log::critical('Database connection fail!', 'Fail in Database class construct function with message:' . $e->getMessage());
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Sets the SQL query and returns the current instance for method chaining.
     *
     * @param string $query The SQL query string.
     * @return self The current instance for method chaining.
     */
    public function prepare(string $query): self
    {
        $this->query = $query;
        $this->bindings = [];
        return $this;
    }

    /**
     * Generic join method for all join types
     *
     * @param string $type The join type (INNER, LEFT, RIGHT, FULL OUTER)
     * @param string $table The table to join
     * @param mixed $conditions String for simple condition or array for complex conditions
     * @return self
     */
    public function join(string $type, string $table, $conditions): self
    {
        $joinClause = " {$type} JOIN {$table} ON ";
        
        if (is_string($conditions)) {
            // Simple string condition: "users.id = posts.user_id"
            $joinClause .= $conditions;
        } elseif (is_array($conditions)) {
            if (isset($conditions[0]) && is_array($conditions[0])) {
                // Multiple conditions: [['users.id', '=', 'posts.user_id'], ['users.status', '=', "'active'"]]
                $onClause = [];
                foreach ($conditions as $condition) {
                    if (count($condition) === 3) {
                        $onClause[] = "{$condition[0]} {$condition[1]} {$condition[2]}";
                    }
                }
                $joinClause .= implode(' AND ', $onClause);
            } else {
                // Single condition array: ['users.id', '=', 'posts.user_id']
                if (count($conditions) === 3) {
                    $joinClause .= "{$conditions[0]} {$conditions[1]} {$conditions[2]}";
                }
            }
        }
        
        $this->query .= $joinClause;
        return $this;
    }

    /**
     * INNER JOIN
     */
    public function innerJoin(string $table, $conditions): self
    {
        return $this->join('INNER', $table, $conditions);
    }

    /**
     * LEFT JOIN - Updated to use the new generic join method
     */
    public function leftJoin(string $table, $conditions): self
    {
        return $this->join('LEFT', $table, $conditions);
    }

    /**
     * RIGHT JOIN
     */
    public function rightJoin(string $table, $conditions): self
    {
        return $this->join('RIGHT', $table, $conditions);
    }

    /**
     * FULL OUTER JOIN
     */
    public function fullOuterJoin(string $table, $conditions): self
    {
        return $this->join('FULL OUTER', $table, $conditions);
    }

    /**
     * CROSS JOIN
     */
    public function crossJoin(string $table): self
    {
        $this->query .= " CROSS JOIN {$table}";
        return $this;
    }

    /**
     * Advanced join with custom join conditions and WHERE-like syntax
     *
     * @param string $type Join type
     * @param string $table Table to join
     * @param callable $callback Callback function to build complex join conditions
     * @return self
     */
    public function joinWhere(string $type, string $table, callable $callback): self
    {
        $joinBuilder = new JoinBuilder();
        $callback($joinBuilder);
        
        $this->query .= " {$type} JOIN {$table} ON " . $joinBuilder->toSql();
        $this->bindings = array_merge($this->bindings, $joinBuilder->getBindings());
        
        return $this;
    }

    /**
     * LEFT JOIN with advanced conditions
     */
    public function leftJoinWhere(string $table, callable $callback): self
    {
        return $this->joinWhere('LEFT', $table, $callback);
    }

    /**
     * INNER JOIN with advanced conditions
     */
    public function innerJoinWhere(string $table, callable $callback): self
    {
        return $this->joinWhere('INNER', $table, $callback);
    }

    /**
     * Adds a WHERE clause to the current SQL query and returns the current instance for method chaining.
     *
     * @param string $column The column to apply the condition on.
     * @param string $operator The operator to compare the column value.
     * @param mixed $value The value to compare the column against.
     * @return self The current instance for method chaining.
     */
    public function where(string $column, string $operator, $value): self
    {
        $placeholder = ':where_' . count($this->bindings);
        
        if (stripos($this->query, 'WHERE') === false) {
            $this->query .= " WHERE $column $operator $placeholder";
        } else {
            $this->query .= " AND $column $operator $placeholder";
        }
        
        $this->bindings[$placeholder] = $value;
        return $this;
    }

    public function whereNotNull(string $column): self
    {
        if (stripos($this->query, 'WHERE') === false) {
            $this->query .= " WHERE $column IS NOT NULL";
        } else {
            $this->query .= " AND $column IS NOT NULL";
        }
        return $this;
    }

    public function whereNull(string $column): self
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
    public function execute(array $params = []): self
    {
        try {
            $this->statement = $this->connection->prepare($this->query);
            $allParams = array_merge($this->bindings, $params);
            $this->statement->execute($allParams);
            return $this;
        } catch (PDOException $e) {
            Log::critical('Database fail in execute method', 'Fail in Database class execute function with message:' . $e->getMessage());
            throw new Exception('Query execution failed: ' . $e->getMessage());
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
    public function query(string $query, array $params = []): self
    {
        try {
            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            Log::critical('Database fail in query method', 'Fail in Database class query function with message:' . $e->getMessage());
            throw new Exception('Query execution failed: ' . $e->getMessage());
        }
    }

    public function getLastInsertedId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Returns the number of affected rows from the last executed statement.
     */
    public function rowCount(): int
    {
        if (!$this->statement) {
            return 0;
        }

        return $this->statement->rowCount();
    }

    /**
     * Fetches all results from the executed query.
     *
     * @param int $ret_type The fetch mode (default is PDO::FETCH_OBJ).
     * @return array The fetched results.
     */
    public function get(int $ret_type = PDO::FETCH_OBJ): array
    {
        return $this->statement->fetchAll($ret_type);
    }

    /**
     * Fetches the first result from the executed query.
     *
     * @param int $ret_type The fetch mode (default is PDO::FETCH_OBJ).
     * @return mixed The fetched result.
     */
    public function find(int $ret_type = PDO::FETCH_OBJ)
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
    public function findOrFail(int $ret_type = PDO::FETCH_OBJ)
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
    public function debug(): string
    {
        return $this->query;
    }

    public function selectCount(string $table): self
    {
        $this->prepare("SELECT COUNT(*) AS count FROM $table");
        return $this;
    }

        public function paginate(int $limit = 25, $search = [], array $search_columns = [])
    {
        // Biztosítjuk, hogy a search paraméter megfelelő típusú legyen a Paginator számára
        $data = $this->statement->fetchAll(PDO::FETCH_OBJ);
        return $this->paginator->data($data)->filter($search, $search_columns)->paginate($limit);
    }
}
