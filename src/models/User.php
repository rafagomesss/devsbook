<?php

namespace src\models;

use \core\Model;

class User extends Model
{
    public function __set($key, $value)
    {
        $this->$key = $value;
        return $this;
    }
}
