<?php

namespace core\base\controllers;

class BaseRoute
{
    // Подключаем Singletone для получения только одного экземпляра класса
    // Подключаем BaseMethods для подключения базовых методов
    use Singletone, BaseMethods;

    public static function routeDirection()
    {
        // Если Ajax-ом что нибудь передается
        if (self::instance()->isAjax()) {

        // Подключаем route в BaseAjax
            exit((new BaseAjax())->route());

        }

        //Метод будет подключать все остальное(выборки  и тд)
        RouteController::instance()->route();

    }

}