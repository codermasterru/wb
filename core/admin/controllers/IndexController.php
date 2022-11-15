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


        $res = $db->get($table,[
            'fields' => ['id', 'name'],
            'where' => ['name' => "O'Raily"],
            'limit' => 1
        ] )[0];

        exit('id=' . $res['id'] . ' Name = ' . $res['name']);


    }
}
//
//[
//    'fields' => ['id', 'name'],
//    'where' => ['name' => 'masha', 'surname' => 'SELECT name FROM students WHERE id = 1'],
//    'operand' => ['IN', '<>'],
//    'condition' => ['AND'],
//    'order' => ['name'],
//    'order_direction' => ['DESC'],
//    'limit' => '1',
//    'join' => [
//        [
//            'table' => 'join_table1',
//            'fields' => ['id as j_id', 'name as j_name'],
//            'type' => 'left',
//            'where' => ['name' => 'Sasha'],
//            'operand' => ['='],
//            'condition' => ['OR'],
//            'on' => [
//                'table' => 'teachers',
//                'fields' => ['id', 'parent_id']
//            ]
//        ],
//        'join_table2'=>[
//            'table' => 'join_table2',
//            'fields' => ['id as j2_id', 'name as j2_name'],
//            'type' => 'left',
//            'where' => ['name' => 'Sasha'],
//            'operand' => ['<>'],
//            'condition' => ['AND'],
//            'on' => [
//                'table' => 'teachers',
//                'fields' => ['id', 'parent_id']
//            ]
//        ]
//    ]
//]