<?php

namespace App\Http\Middlewares;

class Admin
{

	public function handle()
	{
		session_start();
		if (!isset($_SESSION['admin'])) {
			return header('Location: /admin');
			exit();
		}
	}
}
