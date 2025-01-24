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
            return header('Location: /login');
            exit();
        } else {
            $email = $_SESSION['user']->email;
            $user = $this->db->query("SELECT * FROM users WHERE email = :email", [':email' => $email])->find();
            unset($user->password);
            $_SESSION['user'] = $user;
        }
    }
}
