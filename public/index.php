<?php

use Core\Database;

ini_set('display_errors', 1);
error_reporting(E_ALL);

const BASE_PATH = __DIR__ . '/../';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();


require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../vendor/fakerphp/faker/src/autoload.php';


require base_path('playground.php');


$db = Database::getInstance();

$router = new \Core\Router();
$routes = require base_path('routes/routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
