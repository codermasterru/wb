<?php

namespace core\base\controllers;

use core\base\exception\RouteException;

abstract class BaseController
{
    protected $routes;

    //
    protected $controller;

    // Собирает данные из БД
    protected $inputMethod;

    // Выводит отображение
    protected $outputMethod;

    //
    protected $parameters;

    //Метод будет подключать все остальное(выборки  и тд)
    public function route()
    {
        $controller = str_replace('/', '\\', $this->controller);

        try {
            // Используем ReflectionMethod , запускаем request у класса контроллера
            $object = new \ReflectionMethod($controller, 'request');

            //массив аргументов для отправки в класс контроллер
            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod
            ];

            //запускаем request в классе контроллере
            $object->invoke(new $controller, $args);

        }catch (\ReflectionException $e){
            throw new RouteException($e->getMessage());
        }
    }

    public function request($args){

    }
}