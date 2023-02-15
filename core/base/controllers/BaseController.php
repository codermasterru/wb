<?php

namespace core\base\controllers;

use core\base\exception\RouteException;
use core\base\model\UserModel;
use core\base\settings\Settings;

include_once  'libraries/function.php';

abstract class BaseController
{
    use BaseMethods;

    //protected $routes;

    protected $header;

    protected $content;
    protected $footer;
    protected $page;


    protected $errors;


    protected $controller;


    // Собирает данные из БД
    protected $inputMethod;
    // Выводит отображение

    protected $outputMethod;
    protected $parameters;

    protected $template;
    protected $styles;
    protected $scripts;
    protected $userId;

    protected $data;

    protected $ajaxData;

    //Метод будет подключать все остальное(выборки  и тд)
    public function route()
    {
        // Меняем слэши
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
            $this->writeLog($this->errors);
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
        exit;

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

    // Инициализируем скрипты и стили
    protected function init($admin = false)
    {
        if (!$admin) {
            if (USER_CSS_JS['styles']) {
                foreach (USER_CSS_JS['styles'] as $item) {
                    $this->styles[] = PATH . USER_TEMPLATE . trim($item, '/');
                }
            }

            if (USER_CSS_JS['scripts']) {
                foreach (USER_CSS_JS['scripts'] as $item) {
                    $this->scripts[] = PATH . TEMPLATE . trim($item, '/');
                }
            }
        } else {
            if (ADMIN_CSS_JS['styles']) {
                foreach (ADMIN_CSS_JS['styles'] as $item) {
                    $this->styles[] = PATH . ADMIN_TEMPLATE. trim($item, '/');
                }
            }

            if (ADMIN_CSS_JS['scripts']) {
                foreach (ADMIN_CSS_JS['scripts'] as $item) {
                    $this->scripts[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
                }
            }
        }
     }


     protected function checkAuth($type=false){

        if(!($this->userId = UserModel::instance()->checkUser(false, $type))){

            $type && $this->redirect(PATH);

        }

        if(property_exists($this, 'userModel')){

            $this->userModel = UserModel::instance();

        }

     }
}