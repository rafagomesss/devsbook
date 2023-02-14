<?php

namespace src\handlers;

class SessionHandler
{
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function delete(string $key): void
    {
        if (!empty($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
}