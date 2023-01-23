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

    // Создаем экземпляр класса
    static public function instance() {
        if(self::$_instance instanceof self) {
            return self::$_instance;
        }

        self::$_instance = new self;

        if(method_exists(self::$_instance, 'connect')) {
            self::$_instance->connect();
        }

        return self::$_instance;

    }

}