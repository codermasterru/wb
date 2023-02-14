<?php

namespace core\admin\controllers;

use core\base\controllers\BaseController;
use core\base\model\UserModel;
use core\base\settings\Settings;

class LoginController extends BaseController
{

    protected $model;

    protected function inputData()
    {

        $this->model = UserModel::instance();

        return $this->render('', ['adminPath' => Settings::get('routes')['admin']['alias']]);
    }


}