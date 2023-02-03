<?php

namespace core\admin\controllers;

class DeleteController extends BaseAdmin
{

    protected function inputData()
    {

        if (!$this->userId) $this->exectBase();

        // Получаем текущую таблицу
        $this->createTableData();

        if (!empty($this->parameters[$this->table])) {

            $id = is_numeric($this->parameters[$this->table]) ?
                $this->clearNum($this->parameters[$this->table]) :
                $this->clearStr($this->parameters[$this->table]);


            // Если есть id
            if($id){
                $this->data = $this->model->get($this->table, [
                    'where' => [$this->columns['id_row'] => $id]
                ]);

            }

        }

    }

}