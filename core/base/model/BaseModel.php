<?php

namespace core\base\model;

use core\base\controllers\Singletone;
use core\base\exception\DbException;

class BaseModel
{

    use Singletone;

    protected $db;

    private function __construct()
    {

        $this->db = @new \mysqli(HOST, USER, PASS, DB_NAME);

        if ($this->db->connect_error) {
            throw new DbException('Ошибка подключения к базе данных: '
                . $this->db->connect_errno . ' ' . ($this->db->connect_error));
        }

        $this->db->query("SET NAMES UTF8");
    }

    final public function query($query, $crud = 'r', $return_id = false)
    {
        $result = $this->db->query($query);

        // Если affected_rows === -1  выдаем ошибку
        if ($this->db->affected_rows === -1) {
            throw new DbException('Ошибка в SQL запросе: '
                . $query . ' - ' . $this->db->errno . ' ' . $this->db->error);
        }

        switch ($crud) {
            case 'r':
                if ($result->num_rows) {
                    $res = [];
                    for ($i = 0; $i < $result->num_rows; $i++) {
                        $res[] = $result->fetch_assoc();
                    }
                    return $res;
                }
                return false;
                break;
            case 'c':
                if ($return_id) return $this->db->insert_id;

                return true;

                break;

            default:
                return true;

                break;
        }
    }


    // Получение данных


    /**
     * @param $table
     * @param $setArr
     * @return void
     *
     * [
     * 'fields'=>['id', 'name'],
     * 'where' => ['name'=>'Maria','Olga ,'surname'=>'Sergeevna'],
     * 'operand' => ['=', '<>'],
     * 'condition' =>['AND'],
     * 'order' =>['fio','name'],
     * 'order_direction' => ['ASC', 'DESC'],
     * 'limit' => '1'
     * ]
     */
    final public function get($table, $set = [])
    {
        // Формирует поля запроса
        $fields = $this->createFields($table, $set);

        $order = $this->createOrder($table, $set);

        // Формирует условие запроса
        $where = $this->createWhere($table, $set);

        // Формирует объединение
        $join_arr = $this->createJoin($table, $set);


        $fields .= $join_arr['fields'];
        $join = $join_arr['join'];
        $where .= $join_arr['where'];

        $fields = rtrim($fields, ',');


        $limit = $set['limit'] ?: '';

        $query = "SELECT $fields FROM $table $join $where $order $limit";

        return $this->query($query);
    }

    protected function createFields($table = false, $set)
    {
        // Поля равны полям либо выборка по всем
        // Проверка на массив и на пустоту
        $set['fields'] = (is_array($set['fields']) && !empty($set['fields'])) ? $set['fields'] : ['*'];

        $table = $table ? $table . '.' : '';

        $fields = '';

        // Филдс присоеденяем  к названию таблицы
        foreach ($set['fields'] as $field) {
            $fields .= $table . $field . ',';
        }

        return $fields;
    }


    /**
     * 'order' =>['fio','name'],
     *  'order_direction' => ['ASC', 'DESC'],
     */

    // ORDER BY id  ASC, name DESC
    protected function createOrder($table = false, $set)
    {

        $table = $table ? $table . '.' : '';

        $order_by = '';

        if ((is_array($set['order'])) && !empty($set['order'])) {

            $set['order_direction'] = (is_array($set['order_direction']) && !empty($set['order_direction'])) ? $set['order_direction'] : ['ASC'];

            $order_by = 'ORDER BY ';

            $direct_count = 0;

            foreach ($set['order'] as $order) {
                if ($set['order_direction'][$direct_count]) {
                    $order_direction = strtoupper($set['order_direction'][$direct_count]);
                    $direct_count++;
                } else {
                    $order_direction = strtoupper($set['order_direction'][$direct_count - 1]);
                }

                $order_by .= $table . $order . ' ' . $order_direction . ',';
            }
            $order_by = rtrim($order_by, ',');
        }

        return $order_by;
    }

    protected function createWhere($table = false, $set, $instruction = 'WHERE')
    {
        $table = $table ? $table . '.' : '';

        $where = '';

        if (is_array($set['where']) && !empty($set['where'])) { // lv 1

            $set['operand'] = (is_array($set['operand']) && !empty($set['operand'])) ? $set['operand'] : ['='];
            $set['condition'] = (is_array($set['condition']) && !empty($set['condition'])) ? $set['condition'] : ['AND'];

            $where = $instruction; //  Занесли WHERE по умолчанию


            // Счетчики
            $o_count = 0;
            $c_count = 0;

            foreach ($set['where'] as $key => $item) {// foreach lv 1

                $where .= ' ';

                if ($set['operand'][$o_count]) {
                    $operand = $set['operand'][$o_count];
                    $o_count++;
                } else {
                    $operand = $set['operand'][$o_count - 1];
                }

                if ($set['condition'][$c_count]) {
                    $condition = $set['condition'][$c_count];
                    $c_count++;
                } else {
                    $condition = $set['condition'][$c_count - 1];
                }



                if ($operand === 'IN' || $operand === 'NOT IN') { // IF LV 2

                    if (is_string($item) && strpos($item, 'SELECT')) {

                        $in_str = $item;

                    } else {
                        if (is_array($item)) $temp_item = $item;
                         else $temp_item = explode(',', $item);

                        $in_str = '';
                        foreach ($temp_item as $v) {
                            $in_str .= "'" . trim($v) . "',";
                        } // FOREACH
                    }
                }// IF LV 2
            } // foreach lv 1
        } // IF lv 1
        $where .= $table . $key . $operand . ' (' . trim($in_str) . ') ' . $condition;

        exit();

    }
}