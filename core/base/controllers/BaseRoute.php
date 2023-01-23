<?php

namespace core\base\controllers;

class BaseRoute
{

    use Singletone, BaseMethods;

    public static function routeDirection()
    {
        // Если isAjax возвращает true
        if (self::instance()->isAjax()) {
        // Выходим
            exit((new BaseAjax())->route());

        }

        //Метод будет подключать все остальное(выборки  и тд)
        RouteController::instance()->route();

    }

}