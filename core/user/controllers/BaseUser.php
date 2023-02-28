<?php

namespace core\user\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseController;

abstract class BaseUser extends BaseController
{

    protected $model;

    protected $table;


    protected function inputData(){

        $this->init();

        !$this->model && $this->model = Model::instance();
    }

    protected function outputData()
    {
        if (!$this->content) {
            $args = func_get_arg(0);
            $vars = $args ?: [];

//            if (!$this->template) $this->template = ADMIN_TEMPLATE . 'show';

            $this->content = $this->render($this->template, $vars);
        }

        $this->header = $this->render(TEMPLATE . 'include/header',$vars);
        $this->footer = $this->render(TEMPLATE . 'include/footer',$vars);

        return $this->render(TEMPLATE . 'layout/default');
    }


    protected  function img($img){



    }

}