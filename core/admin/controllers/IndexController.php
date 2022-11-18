<?php

namespace core\admin\controllers;

use core\base\controllers\BaseController;
use core\base\settings\Settings;


class IndexController extends BaseController
{
    protected function inputData()
    {
        // Получаем маршруты
        $redirect = PATH . Settings::get('routes')['admin']['alias'] . '/show';

        // Перенаправляем на core/admin/controllers/
        $this->redirect($redirect);
    }
}