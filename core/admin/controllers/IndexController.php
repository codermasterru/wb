<?php

namespace core\admin\controllers;

use core\admin\models\Model;
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

//'admin' => [
//    'alias' => 'admin',
//    'path' => 'core/admin/controllers/',
//    'hrUrl' => false,
//    'routes'=>[
//
//    ]

