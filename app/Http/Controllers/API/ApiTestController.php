<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Core\Response;

class ApiTestController extends ApiController
{

    protected $user;
    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function index()
    {
        $users = $this->user->all();

        Response::api([
            'status' => 'success',
            'data' => $users,
        ]);
    }


    public function show($vars)
    {

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
