<?php

namespace App\Http\Middlewares;

use Core\Database;
use Core\Session;

class Auth
{
    protected $db = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function handle()
    {
        Session::create();
        if (!isset($_SESSION['user']) || !$_SESSION['user']) {
            header('Location: /login');
            exit();
        } else {
            $email = $_SESSION['user']->email;
            $user = $this->db->query("SELECT * FROM users WHERE email = :email", [':email' => $email])->find();
            if ($user) {
                unset($user->password);
                $_SESSION['user'] = $user;
            } else {
                // User not found in database, redirect to login
                unset($_SESSION['user']);
                header('Location: /login');
                exit();
            }
        }
    }
}
