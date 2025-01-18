<?php

namespace Core;

use PDO;

class Database
{
    use Singleton;

    public $connection;
    public $statement;

    public function __construct()
    {
        $config = require base_path('config/database.php'); 
        
        $dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['charset'];

        $this->connection = new PDO($dsn, $config['name'], $config['password'], [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    public function get()
    {
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function find()
    {
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    public function findOrFail()
    {
        $result = $this->find();

        if (! $result) {
            abort();
        }

        return $result;
    }

}
