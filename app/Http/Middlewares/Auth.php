<?php

namespace App\Http\Middlewares;

class Auth {


    public function handle() {
        session_start();
        if(!isset($_SESSION['user'])) {
            return header('Location: /login');
            exit();
        }
    }


}
