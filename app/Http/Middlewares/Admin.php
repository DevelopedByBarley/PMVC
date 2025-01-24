<?php

namespace App\Http\Middlewares;

use Core\Database;
use Core\Session;

class Admin
{
	protected $db = null;

	public function __construct()
	{
		$this->db = Database::getInstance();
	}

	public function handle()
	{
		Session::create();
		if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
			return header('Location: /admin');
			exit();
		} else {
			$email = $_SESSION['admin']->email;
			$admin = $this->db->query("SELECT * FROM admins WHERE email = :email", [':email' => $email])->find();
			unset($admin->password);
			$_SESSION['admin'] = $admin;
		}
	}
}
