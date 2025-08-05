<?php

namespace App\Http\Controllers\API;

use Core\Response;

class ApiTestController extends ApiController
{
    public function __construct()
    {
           parent::__construct();
    }

    public function index()
    {
        $users = $this->db->query('SELECT * FROM users')->get();
        Response::api([
            'status' => 'success',
            'data' => $users
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
        $name = request('name', null);

        return Response::api([
            'name' => $name,
            'status' => 'success',
            'message' => 'Resource created successfully'
        ], 201);
    }

    public function update($vars)
    {
        $id = $vars[0];
        $name = request('name', null);

        return Response::api([
            'id' => $id,
            'name' => $name,
            'status' => 'success',
            'message' => 'Resource updated successfully'
        ]);
    }


}