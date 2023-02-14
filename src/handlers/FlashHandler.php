<?php

namespace src\handlers;

class FlashHandler
{
    public static function set($value)
    {
        SessionHandler::set('flash', $value);
    }

    public static function get(string $key)
    {
        $message = SessionHandler::get($key);
        SessionHandler::delete($key);
        return $message;
    }
}