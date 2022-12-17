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

        $res = $model->get('teachers', [
            'where' => ['id' => '13,14'],
            'operand' => ['IN'],
            'join' => [
                'stud_teach' => ['on' => ['id', 'teachers']],
                'students' => [
                    'fields' => [ 'name as student_name'],
                    'on'=>['students', 'id']
                ]
            ],
          //  'join_structure' => true
        ]);

        exit;

    }


}