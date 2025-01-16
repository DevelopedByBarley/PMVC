<?php

namespace App\Http\Middlewares;

class Auth {


    public function handle() {
        if(!isset($_SESSION['user'])) {
            return header('Location: /logjn');
            exit();
        }
    }


}
