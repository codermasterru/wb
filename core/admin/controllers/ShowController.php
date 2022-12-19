<?php

namespace core\admin\controllers;

use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class ShowController extends BaseAdmin
{
    protected function inputData()
    {
        if (!$this->iserId) $this->exectBase();

//        $res = $this->model->get('filters', [
//            'fields' => ['id', 'name'],
//            'join' => [
//                'goods' => [
//                    'fields' => ['id as t_id', 'name as t_name'],
//                    'on' => ['id', 'parent_id']
//                ]
//            ]
//        ]);

 //       exit();

        $this->createTableData();

        $this->createData();

        return $this->expansion(get_defined_vars());
    }

    //Получает данные для вывода из текщей таблицы
    protected function createData($arr = [])
    {

        $fields = [];
        // Сортировка
        $order = [];
        // Напрвление сортировки
        $order_direction = [];

        if (!$this->columns['id_row']) return $this->data = [];

        $fields[] = $this->columns['id_row'] . ' as id';
        if ($this->columns['name']) $fields['name'] = 'name';
        if ($this->columns['img']) $fields['img'] = 'img';


        if (count($fields) < 3) {
            foreach ($this->columns as $key => $item) {
                if (!$fields['name'] && strrpos($key, 'name') !== false) {
                    $fields['name'] = $key . ' as name';
                }
                if (!$fields['img'] && strrpos($key, 'img') === 0) {
                    $fields['img'] = $key . ' as img';
                }
            }
        }
        if ($arr['fields']) {
            if (is_array($arr['fields'])) {
                $fields = Settings::instance()->arrayMergeRecursive($fields, $arr['fields']);
            } else {
                $fields[] = $arr['fields'];
            }
        }

        if ($this->columns['parent_id']) {
            if (!in_array('parent_id', $fields)) $fields[] = 'parent_id';
            $order[] = 'parent_id';
        }

        if ($this->columns['menu_position']) $order[] = 'menu_position';
        elseif ($this->columns['date']) {
            if ($order) $order_direction = ['ASC', 'DESC'];
            else $order_direction[] = 'DESC';

            $order = 'date';
        }
        if ($arr['oder']) {
            if (is_array($arr['order'])) {
                $order = Settings::instance()->arrayMergeRecursive($order, $arr['order']);
            } else {
                $order[] = $arr['order'];
            }

        }
        if ($arr['oder_direction']) {
            if (is_array($arr['$order_direction'])) {
                $order_direction = Settings::instance()->arrayMergeRecursive($order_direction, $arr['$order_direction']);
            } else {
                $order_direction[] = $arr['$order_direction'];
            }
        }


        $this->data = $this->model->get($this->table, [
            'fields' => $fields,
            'order' => $order,
            'order_direction' => $order_direction
        ]);


    }


}