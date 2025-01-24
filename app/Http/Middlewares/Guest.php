<?php

namespace App\Http\Middlewares;

use Core\Session;

class Guest
{

    public function handle()
    {
        Session::create();
        if (isset($_SESSION['user'])) {
            return header('Location: /logjn');
            exit();
        }
    }
}
