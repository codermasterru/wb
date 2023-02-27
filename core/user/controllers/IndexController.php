<?php

namespace core\user\controllers;

use core\base\controllers\BaseController;

class IndexController extends BaseController
{
    protected $name;

    protected function inputData()
    {
        $this->init();

        $header = $this->render(TEMPLATE . 'header');
        $content = $this->render();
        $footer = $this->render(TEMPLATE . 'footer');

     $a = $this->render(TEMPLATE . 'templater', compact('header', 'content', 'footer'));

        //return $this->render(TEMPLATE . 'templater', compact('header', 'content', 'footer'));
        return $a;

    }
}