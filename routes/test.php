<?php

use App\Http\Controllers\TestController;

$router->except(['show'])->resources('test', TestController::class);
