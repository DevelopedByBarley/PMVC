<?php
use App\Http\Controllers\Controller;

ini_set('display_errors', 1);
error_reporting(E_ALL);
const BASE_PATH = __DIR__ . '/../';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/functions.php';

require base_path('playground.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->safeLoad();



/* $db = Database::getInstance();
$user = $db->query('SELECT * FROM users WHERE id = 4')->findOrFail();
dd($user->email); 
 */
$controller = new Controller();
$controller->render();
