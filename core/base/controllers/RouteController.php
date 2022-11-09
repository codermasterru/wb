<?php

namespace core\base\controllers;

use  core\base\settings\ShopSettings;
use  core\base\settings\Settings;

class RouteController
{
//    use Singletone;
    //Роуты??
    protected $routes;

    //
    protected $controller;

    // Собирает данные из БД
    protected $inputMethod;


    static private $_instance;

    private function __construct()
    {
        $s = Settings::get('templateArr');
        $s1 = ShopSettings::get('templateArr');
        exit();
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