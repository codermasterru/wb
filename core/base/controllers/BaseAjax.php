<?php

namespace core\base\controllers;

use core\base\settings\Settings;

class BaseAjax extends BaseController
{

    public function route()
    {
        // Собираем настройки
        $route = Settings::get('routes');

        $controller = $route['user']['path'] . 'AjaxController';

        // Понимаем POST  или GET
        $data = $this->isPost() ? $_POST : $_GET;

        if (isset($data['ADMIN_MODE'])) {

            unset($data['ADMIN_MODE']);

            $controller = $route['admin']['path'] . 'AjaxController';

        }

        $controller = str_replace('/', '\\', $controller);

        $ajax = new $controller;

        $ajax->createAjaxData($data);

        return ($ajax->ajax());
    }

    protected function createAjaxData($data) {

        $this->data = $data;
    }

}