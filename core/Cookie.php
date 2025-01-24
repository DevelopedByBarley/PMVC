<?php

namespace Core;

class Cookie
{
    /**
     * Set a cookie.
     * 
     * @param string $key   The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int    $expiry Expiry time in seconds (default: 1 hour).
     * @return void
     */
    public static function set(string $key, string $value, int $expiry = 3600, bool $onlyHttp = false): void
    {
        setcookie($key, $value, time() + $expiry, "/", "", false, $onlyHttp);
    }

    /**
     * Get the value of a cookie.
     * 
     * @param string $key The name of the cookie.
     * @return string|null The cookie value or null if it doesn't exist.
     */
    public static function get(string $key): ?string
    {
        return $_COOKIE[$key] ?? null;
    }

    /**
     * Delete a cookie.
     * 
     * @param string $key The name of the cookie.
     * @return void
     */
    public static function delete(string $key): void
    {
        if (isset($_COOKIE[$key])) {
            setcookie($key, "", time() - 3600, "/", "", false, true);
            unset($_COOKIE[$key]);
        }
    }

    /**
     * Generate a unique cookie ID.
     * 
     * @return string A unique ID.
     */
    public static function id(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Generate a secure cookie key.
     * 
     * @return string A random key for securing cookies.
     */
    public static function key(): string
    {
        return hash('sha256', uniqid((string) mt_rand(), true));
    }
}
