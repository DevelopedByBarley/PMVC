<?php

namespace App\Http\Middlewares;

use Core\Session;

class Guest
{

    public function handle()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /user');
            exit();
        }
        if (isset($_SESSION['admin'])) {
            header('Location: /admin/dashboard');
            exit();
        }
    }
}
