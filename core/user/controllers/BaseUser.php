<?php

namespace core\user\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseController;

abstract class BaseUser extends BaseController
{

    protected $model;

    protected $table;


    protected function inputData()
    {

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

        $this->header = $this->render(TEMPLATE . 'include/header', $vars);
        $this->footer = $this->render(TEMPLATE . 'include/footer', $vars);

        return $this->render(TEMPLATE . 'layout/default');
    }


    protected function img($img = '', $tag = false)
    {
        if (!$img && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . DEFAULT_IMAGE_DIRECTORY)) {

            $dir = scandir($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . DEFAULT_IMAGE_DIRECTORY);

            $imgArr = preg_grep('/' . $this->getController() . '\./i', $dir) ?: preg_grep('/default\./i', $dir);

            $imgArr && $img = array_shift($imgArr);

        }

        if($img){


        }

        return  $img;

    }

}


