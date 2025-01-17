<?php

namespace App\Http\Middlewares;

class Admin
{

	public function handle()
	{
		if (!isset($_SESSION['admin'])) {
			return header('Location: /admin/login');
			exit();
		}
	}
}
