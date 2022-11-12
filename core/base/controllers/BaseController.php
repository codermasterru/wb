<?php

namespace core\base\controllers;

use core\base\exception\RouteException;
use core\base\settings\Settings;

abstract class BaseController
{
    use \core\base\controllers\BaseMethods;

    protected $routes;

    //Вся инфа после сборки методов
    protected $page;
    protected $errors;
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

        } catch (\ReflectionException $e) {
            throw new RouteException($e->getMessage());
        }
    }

    public function request($args)
    {
        //
        $this->parameters = $args['parameters'];

        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        // Запускаем
        $data = $this->$inputData();

        if (method_exists($this, $outputData)) {

            $page = $this->$outputData($data);

            if ($page) $this->page = $page;

        } elseif ($data) {
            $this->page = $data;
        }

        if ($this->errors) {
            $this->writeLog();
        }
        $this->getPage();
    }

    private function getPage()
    {
        if (is_array($this->page)) {
            foreach ($this->page as $block) echo $block;
        } else {
            echo $this->page;
        }

        exit();
    }

    protected function render($path = '', $parameters = [])
    {

        extract($parameters);

        // Если путь не пришел то подключим шаблон по умолчанию
        if (!$path) {


            $class = new \ReflectionClass($this);

            //Пространство имен для this
            $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');

            $routes = Settings::get('routes');

            // Проверка на плагины
            if ($space === $routes['user']['path']) {
                $template = TEMPLATE;
            } else {
                $template = ADMIN_TEMPLATE;
            }

            $path = $template . explode('controller', strtolower($class->getShortName()))[0];
        }

        ob_start();


        if (!@include_once $path . '.php') throw new RouteException('Отсутствует шаблон - ' . $path);

        return ob_get_clean();
    }
}