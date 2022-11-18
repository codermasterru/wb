<?php

namespace core\admin\controllers;

class AddController extends BaseAdmin
{

    protected function inputData()

    {
        if (!$this->iserId) $this->exectBase();

        // Собирает данные
        $this->createTableData();

        // Создает выходные данные
        $this->createOutputData();
        $this->model->showForeignKeys($this->table);
    }




}