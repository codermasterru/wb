<?php

namespace core\admin\controllers;

class EditController extends BaseAdmin
{

    protected function inputData()
    {

    }

    protected function checkOldAlias($id)
    {
        $tables = $this->model->showTables();

        if (in_array('old_alias', $tables)) {

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