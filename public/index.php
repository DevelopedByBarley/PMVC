<?php
use App\Http\Controllers\Controller;
use Core\Database;

ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->safeLoad();

require_once __DIR__ . '/../core/functions.php';

const BASE_PATH = __DIR__ . '/../';


$db = new Database();
$users = $db->query('SELECT * FROM users WHERE id = 1')->findOrFail();
dd($users);
$controller = new Controller();
$controller->render();
