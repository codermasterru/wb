<?php

namespace core\admin\controllers;

use core\base\settings\Settings;

class AddController extends BaseAdmin
{
    protected $action = 'add';

    protected function inputData()
    {
        if (!$this->userId) $this->exectBase();

        $this->checkPost();

        // Собирает данные
        $this->createTableData();

        // Проверяет внешние ключи
        $this->createForeignData();

        // Формирует
        $this->createMenuPosition();

        $this->createRadio();

        // Создает выходные данные
        $this->createOutputData();

        $this->createManyToMany();

        return $this->expansion();
    }



}