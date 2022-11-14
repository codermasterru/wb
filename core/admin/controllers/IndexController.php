<?php

namespace core\admin\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseController;


class IndexController extends BaseController
{
    protected function inputData()
    {

        $db = Model::instance();

        $table = 'teachers';
        $res = $db->get($table, [
            'fields'=>['id', 'name'],
            'where' => ['fio'=>'smirnova', 'name'=>'Maria', 'surname'=>'Sergeevna'],
            'operand' => ['=', '<>'],
            'condition' =>['AND'],
            'order' =>['fio','name'],
            'order_direction' => ['ASC', 'DESC'],
            'limit' => '1'
        ]);

        exit();
    }
}