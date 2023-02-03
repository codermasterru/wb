<?php

namespace core\admin\controllers;

use core\base\exception\RouteException;

class EditController extends BaseAdmin
{
    protected $action = 'edit';

    protected function inputData()
    {
        if (!$this->userId) $this->exectBase();

        $this->checkPost();

        // Собирает данные
        $this->createTableData();

        // Метод получает данные из БД
        $this->createData();

        // Проверяет внешние ключи
        $this->createForeignData();

        // Формирует
        $this->createMenuPosition();

        $this->createRadio();

        // Создает выходные данные
        $this->createOutputData();

        $this->createManyToMany();

        $this->template = ADMIN_TEMPLATE . 'add';


        return $this->expansion();

    }

    // Метод получает данные из БД
    protected function createData()
    {

        $id = $this->clearNum($this->parameters[$this->table]);

        if (!$id) throw new RouteException('Не корректный идентификатор - ' . $id .
            ' при редактировании таблицы' . $this->table);

        // Получаем данные
        $this->data = $this->model->get($this->table, [
            'where' => [$this->columns['id_row'] => $id]
        ]);

        $this->data && $this->data = $this->data[0];


      //  exit();

    }

    // Проверка старых ссылок
    protected function checkOldAlias($id)
    {
        // Получаем массив названий всех таблиц
        $tables = $this->model->showTables();

        //   Если в массиве есть 'old_alias'
        if (in_array('old_alias', $tables)) {

            //
            $old_alias = $this->model->get($this->table, [
                'alias' => ['alias'],
                'where' => [$this->columns['id_row'] => $id]
            ])[0]['alias'];

            if ($old_alias && $old_alias !== $_POST['alias']) {

                $this->model->delete('old_alias', [
                    'where' => ['alias' => $old_alias, 'table_name' => $this->table]
                ]);

                $this->model->delete('old_alias', [
                    'where' => ['alias' => $_POST['alias'], 'table_name' => $this->table]
                ]);

                $this->model->add('old_alias', [
                    'fields' => ['alias' => $old_alias, 'table_name' => $this->table, 'table_id' => $id]
                ]);
            }

        }
    }

}