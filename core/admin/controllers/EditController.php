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

        $id = is_numeric($this->parameters[$this->table]) ?
            $this->clearNum($this->parameters[$this->table]) :
            $this->clearStr($this->parameters[$this->table]);

        if (!$id) throw new RouteException('Не корректный идентификатор - ' . $id .
            ' при редактировании таблицы ' . $this->table);

        // Получаем данные
        $this->data = $this->model->get($this->table, [
            'where' => [$this->columns['id_row'] => $id]
        ]);

        $this->data && $this->data = $this->data[0];


    }



}