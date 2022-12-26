<?php

namespace core\admin\models;

use core\base\controllers\Singletone;
use core\base\model\BaseModel;

class Model extends BaseModel
{
    use Singletone;

    public function showForeignKeys($table, $key = false)
    {

        $db = DB_NAME;

        if ($key) $where = "AND COLUMN_NAME =  '$key'  LIMIT 1";

        $query = "SELECT COLUMN_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
                    FROM  information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table' AND
                    CONSTRAINT_NAME <> 'PRIMARY' AND REFERENCED_TABLE_NAME is not null $where";

        return $this->query($query);

    }

    public function updateMenuPosition($table, $row, $where, $end_pos, $update_rows = [])
    {

        if ($update_rows && isset($update_rows['where'])) {

            $update_rows['operand'] = isset($update_rows['operand']) ? $update_rows['operand'] : ['='];

            if ($where) {

                $old_data = $this->get($table, [
                    'fields' => [$update_rows['where'], $row],
                    'where' => $where
                ])[0];

                $start_pos = $old_data[$row];

                if ($old_data[$update_rows['where']] !== $_POST[$update_rows['where']]) {

                    $pos = $this->get($table, [
                        'fields' => ['COUNT(*) as count'],
                        'where' => [$update_rows['where'] => $old_data[$update_rows['where']]],
                        'no_concat' => true
                    ])[0]['count'];

                    if($)

                }

            }


        } else {

            if ($where) {

                $start_pos = $this->get($table, [
                    'fields' => [$row],
                    'where' => $where
                ])[0][$row];

            } else {

                $start_pos = $this->get($table, [
                        'fields' => ['COUNT(*) as count'],
                        'no_concat' => true
                    ])[0]['count'] + 1;

            }

        }


    }
}