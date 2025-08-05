<?php

namespace App\Http\Controllers\API;

use Core\Database;
use Core\Response;

class ApiController
{

    protected $db;

    public function __construct()
    {
        // Initialization code for API controller
        $this->db = Database::getInstance(); // Assuming you have a Database class for DB operations
    }


}
