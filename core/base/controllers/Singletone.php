<?php

namespace core\base\controllers;

trait Singletone
{
    static private $_instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return RouteController|Singletone
     */
    static public function instance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
}