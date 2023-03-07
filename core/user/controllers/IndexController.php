<?php

namespace core\user\controllers;

use core\base\controllers\BaseController;

class IndexController extends BaseUser
{
    protected $name;

    protected function inputData()
    {
        parent::inputData();

        $alias = '';

        $res = $this->alias(['catalog'=>'auto'], '?page=2');

        $a = 1;

    }
}