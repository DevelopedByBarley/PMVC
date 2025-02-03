<?php

namespace App\Http\Middlewares;

use Core\Session;

class Guest
{

    public function handle()
    {
        if (isset($_SESSION['user'])) {
            return header('Location: /user');
            exit();
        }
        if (isset($_SESSION['admin'])) {
            return header('Location: /admin/dashboard');
            exit();
        }
    }
}
