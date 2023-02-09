<?php

namespace core\base\controllers;

use core\base\settings\Settings;

class BaseAjax extends BaseController
{

    public function route()
    {
        // Собираем настройки
        $route = Settings::get('routes');

        $controller = $route['admin']['path'] . 'AjaxController';

        // Понимаем POST  или GET
        $data = $this->isPost() ? $_POST : $_GET;

        $httpReferer = str_replace('/', '\/', $_SERVER['REQUEST_SHEMA'] . '://' . $_SERVER['SERVER_NAME'] . PATH . $route['admin']['alias']);

        if (isset($data['ADMIN_MODE']) || preg_match('/^' . $httpReferer . '(\/?|$)/', $_SERVER['HTTP_REFERER'])) {

            unset($data['ADMIN_MODE']);

            $controller = $route['admin']['path'] . 'AjaxController';

        }

        $controller = str_replace('/', '\\', $controller);

        $ajax = new $controller;

        $ajax->data = $data;

        return ($ajax->ajax());
    }

    protected function createAjaxData($data)
    {

        $this->data = $data;
    }

}