<?php

    $router->apiResource('test', App\Http\Controllers\API\ApiController::class);

    $router->apiGet('/haha', [App\Http\Controllers\API\ApiController::class, 'index']);
?>