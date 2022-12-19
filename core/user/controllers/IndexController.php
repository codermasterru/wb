<?php

namespace core\user\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseController;

class IndexController extends BaseController
{
    protected $name;

    protected function inputData()
    {

        $model = Model::instance();

        $res = $model->get('goods', [
            'where' => ['id' => '13,14'],
            'operand' => ['IN'],
            'join' => [
                'goods_filters' => [
                    'fields' => null,
                    'on' => ['id', 'teachers']
                ],
                'filters f' => [
                    'fields' => ['name as student_name', 'content'],
                    'on' => ['students', 'id']
                ],
                'filters' => [
                    'on' => ['parent_id', 'id']
                ]
            ],
           // 'join_structure' => true,
            'order' => ['id'],
            'order_direction'=> ['DESC']
        ]);

    //    exit;

    }


}