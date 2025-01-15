<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php'; // Az autoload.php fájl betöltése a vendor könyvtárból
require_once __DIR__ . '/../core/functions.php';

const BASE_PATH = __DIR__.'/../';

use App\Http\Controllers\Controller;


$controller = new Controller();
$controller->render();
