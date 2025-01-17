<?php

namespace App\Http\Middlewares;

class Guest
{

    public function handle()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            return header('Location: /logjn');
            exit();
        }
    }
}
