<?php

namespace core\admin\controllers;

use core\base\controllers\BaseController;
use core\base\model\UserModel;

class LoginController extends BaseController
{

    protected $model;

    protected function inputData()
    {

        $this->model = UserModel::instance();

        $a = 1;
    }


}