<?php

namespace core\admin\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseController;
use core\base\exception\RouteException;
use core\base\settings\Settings;

abstract class BaseAdmin extends BaseController
{
    protected $model;


    protected $table;
    protected $columns;
    protected $data;

    protected $menu;
    protected $title;


    protected function inputData()
    {
        // // Инициализируем скрипты и стили
        $this->init(true);

        // Инициализируем заголовок сайта
        $this->title = 'Test engine';


        // Объект модели
        if (!$this->model) $this->model = Model::instance();

        // Возвращает таблицу меню
        if (!$this->menu) $this->menu = Settings::get('projectTables');

        // Отправляем заголовки
        $this->sendNoCacheHeaders();

    }


    // Отправляем  заголовки
    protected function sendNoCacheHeaders()
    {
        header("Last-Modified: " . gmdate("D, d m Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Cache-control: max-age=0");
        header("Cache-Control: post-check-0, pre-check=0");

    }

    // Вызывает inputData самого себя
    protected function exectBase()
    {
        self::inputData();
    }

    // Достали название таблицы
    protected function createTableData()
    {
        if (!$this->table) {
            if ($this->parameters) {
                $this->table = array_keys($this->parameters)[0];
            } else {
                $this->table = Settings::get('defaultTable');
            }
        }

        $this->columns = $this->model->showColumns($this->table);

        if (!$this->columns) new RouteException('Не найдены поля в таблице - ' . $this->table, 2);


    }


//Получает данные для вывода из текщей таблицы
// Массив полей  и флаг, если флаг  в true то пришедшее нужно добавить к базовому запросу
    protected function createData($arr = [], $add = true)
    {

        $fields = [];

        // Сортировка
        $order = [];
        // Напрвление сортировки
        $order_direction = [];

        // Если флаг true то
        if ($add) {

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
                $fields = Settings::instance()->arrayMergeRecursive($fields, $arr['fields']);
            }

            if ($this->columns['parent_id']) {
                if (!in_array('parent_id', $fields)) $fields[] = 'parent_id';
                $order[] = 'parent_id';
            }

            if ($this->columns['menu_position']) $order[] = 'menu_position';
            elseif ($this->columns['date']) {
                if ($order) $order_direction = ['ASC', 'DESC'];
                else $order_direction[] = ['DESC'];

                $order = 'date';
            }
            if ($arr['oder']) {
                $order = Settings::instance()->arrayMergeRecursive($order, $arr['order']);
            }
             if ($arr['oder_direction']) {
                $order_direction = Settings::instance()->arrayMergeRecursive($order_direction, $arr['order_direction']);
            }

            // иначе
        } else {
            if (!$arr) return $this->data = [];

            $fields = $arr['fields'];
            $order = $arr['order'];
            $order_direction = ['order_direction'];
        }

        $this->data= $this->model->get($this->table, [
            'fields' => $fields,
            'order' => $order,
            'order_direction' => $order_direction
        ]);

        exit();
    }


    protected function outputData()
    {

    }
}