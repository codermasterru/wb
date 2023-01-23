<?php

namespace core\user\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseController;

class IndexController extends BaseController
{
    protected $name;

    protected function inputData()
    {
        $this->init(false);

    }

    protected function outputData()
    {

        if (!$this->content) {
            $args = func_get_arg(0);
            $vars = $args ?: [];

            if (!$this->template) $this->template = USER_TEMPLATE . 'show';

            $this->content = $this->render($this->template, $vars);
        }

        $this->header = $this->render(USER_TEMPLATE . 'include/header');
        $this->footer = $this->render(USER_TEMPLATE . 'include/footer');


        return $this->render(USER_TEMPLATE . 'layout/default');

    }


}