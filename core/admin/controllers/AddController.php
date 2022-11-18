<?php

namespace core\admin\controllers;

use core\base\settings\Settings;

class AddController extends BaseAdmin
{

    protected function inputData()

    {
        if (!$this->iserId) $this->exectBase();

        // Собирает данные
        $this->createTableData();

        // Создает выходные данные
        $this->createOutputData();

        $this->createForeignData();
    }

    protected function createForeignData($settings=false)
    {
        if(!$settings) $settings = Settings::instance();
    }


}