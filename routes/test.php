<?php

use App\Http\Controllers\TestController;

$router->except(['index', 'show', 'create'])->resources('test', TestController::class, ['admin']);
