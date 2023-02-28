<?php

namespace core\user\controllers;

use core\base\controllers\BaseController;

class IndexController extends BaseUser
{
    protected $name;

    protected function inputData()
    {
        parent::inputData();
        echo $this->getController();

        exit();

    }
}