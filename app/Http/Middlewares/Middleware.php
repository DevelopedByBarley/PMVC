<?php

   namespace App\Http\Middlewares;

    class Middleware {
        const MAP = [
            'auth' => Auth::class,
            'guest' => Guest::class,
            'admin' => Admin::class,
            'verify' => EmailVerify::class,
        ];

        public static function resolve($key) {
            $middleware = static::MAP[$key] ?? false;
        
            (new $middleware)->handle();
        }


    }
?>