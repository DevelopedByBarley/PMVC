<?php

use Core\Database;

$users = (new Database)->query("SELECT * FROM dd")->get();
dd($users);