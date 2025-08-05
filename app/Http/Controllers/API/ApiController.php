<?php

namespace App\Http\Controllers\API;

use Core\Response;

class ApiController
{
    public function __construct()
    {
        // Initialization code for API controller   
    }

    public function index()
    {
        // Handle API index request
        Response::api([
            'status' => 'success',
            'message' => 'API index endpoint reached'
        ]);
    }


    public function show($vars) {

        return Response::api([
            'status' => 'success',
            'message' => "Showing API resource with ID: {$vars[0]}"
        ]);
    }

    public function store()
    {
        // Handle API store request
        return Response::json([
            'status' => 'success',
            'message' => 'Resource created successfully'
        ], 201);
    }


}
