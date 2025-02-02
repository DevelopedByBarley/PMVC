<?php

use App\Http\Controllers\Auth\EmailVerificationController;
$router->get('/verify-email', [EmailVerificationController::class, "index"]);

