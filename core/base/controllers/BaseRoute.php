<?php

namespace core\base\controllers;

class BaseRoute
{

    use Singletone, BaseMethods;

    public static function routeDirection()
    {

        if (self::instance()->isAjax()) {

            exit((new BaseAjax())->route());

        }

        //Метод будет подключать все остальное(выборки  и тд)
        RouteController::instance()->route();

    }

}