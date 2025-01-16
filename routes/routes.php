

<?php
//$router->get('/', 'index.php');

use App\Http\Controllers\Controller;

$router->get('/', [Controller::class, 'render']);
$router->get('/test/{id}', [Controller::class, 'renderTest']);
