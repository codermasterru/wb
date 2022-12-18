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
                'goods_filters' => ['on' => ['id', 'teachers']],
                'filters' => [
                    'fields' => ['name as student_name', 'content'],
                    'on' => ['students', 'id']
                ],
                [
                    'table' => 'filters',
                    'on' => ['parent_id', 'id']
                ]
            ],
            'join_structure' => true
        ]);

        exit;

    }


}