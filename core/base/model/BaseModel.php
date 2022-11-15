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

    /**
     * @param $query
     * @param string $crud = r - SELECT / c - INSERT / u - UPDATE / d - DELETE
     * @param $return_id
     * @return array|bool
     * @throws DbException
     */

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
            case 'c':
                if ($return_id) return $this->db->insert_id;

                return true;

            default:
                return true;
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
     * 'where' => ['name'=>'Maria','Olga ,'surname'=>'Sergeevich'],
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
        $fields = $this->createFields($set, $table);

        $order = $this->createOrder($set, $table);

        // Формирует условие запроса
        $where = $this->createWhere($set, $table);

        if (!$where) $new_where = true;
        else $new_where = false;

        // Формирует объединение
        $join_arr = $this->createJoin($set, $table, $new_where);

        $fields .= $join_arr['fields'];
        $join = $join_arr['join'];
        $where .= $join_arr['where'];

        $fields = rtrim($fields, ',');


        $limit = $set['limit'] ? 'LIMIT ' . $set['limit'] : '';

        $query = "SELECT $fields FROM $table $join $where $order $limit";

        return $this->query($query);
    }
    protected function createFields($set, $table = false) {

        $set['fields'] = (is_array($set['fields']) && !empty($set['fields']))
            ? $set['fields'] : ['*'];

        $table = ($table && !$set['no_concat']) ? $table .  '.' : '';

        $fields = '';

        foreach ($set['fields'] as $field) {
            $fields .= $table . $field . ',';

        }

        return $fields;

    }

    protected function createOrder($set, $table = false) {

        $table = ($table && !$set['no_concat']) ? $table .  '.' : '';

        $order_by = '';

        if(is_array($set['order']) && !empty($set['order'])) {

            $set['order_direction'] = (is_array($set['order_direction']) && !empty($set['order_direction']))
                ? $set['order_direction'] : ['ASC'];

            $order_by = 'ORDER BY ';

            $direct_count = 0;

            foreach ($set['order'] as $order) {

                if($set['order_direction'][$direct_count]) {

                    $order_direction = strtoupper($set['order_direction'][$direct_count]);
                    $direct_count++;

                } else {

                    $order_direction = strtoupper($set['order_direction'][$direct_count - 1]);
                }
                if(is_int($order)) $order_by .= $order . ' ' . $order_direction . ',';
                else $order_by .= $table . $order . ' ' . $order_direction . ',';

            }

            $order_by = rtrim($order_by, ',');

        }

        return $order_by;

    }

    protected function createWhere($set, $table = false, $instruction = 'WHERE') {

        $table = ($table && !$set['no_concat']) ? $table .  '.' : '';

        $where = '';

        if (is_string($set['where'])) {
            return $instruction . ' ' . trim($set['where']);
        }

        if(is_array($set['where']) && !empty($set['where'])) {

            $set['operand'] = (is_array($set['operand']) && !empty($set['operand'])) ? $set['operand'] : ['='];
            $set['condition'] = (is_array($set['condition']) && !empty($set['condition'])) ? $set['condition'] : ['AND'];

            $where = $instruction;

            $o_count = 0;
            $c_count = 0;

            foreach ($set['where'] as $key => $item) {

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

                if($operand === 'IN' || $operand === 'NOT IN') {

                    if(is_string($item) && strpos($item, 'SELECT') === 0) {

                        $in_str = $item;
                    }else {
                        if(is_array($item)) $temp_item = $item;
                        else $temp_item = explode(',', $item);

                        $in_str = '';

                        foreach ($temp_item as $v) {
                            $in_str .= "'" . addslashes(trim($v)) . "',";

                        }
                    }

                    $where .= $table . $key . ' ' . $operand . ' (' . trim($in_str, ',') . ') ' . $condition;

                } elseif (strpos($operand, 'LIKE') !== false) {

                    $like_template = explode('%', $operand);

                    foreach ($like_template as $lt_key => $lt) {
                        if(!$lt) {
                            if(!$lt_key) {
                                $item = '%' . $item;
                            }else {
                                $item .='%';

                            }
                        }

                    }

                    $where .= $table . $key . ' LIKE ' . "'" . addslashes($item) . "' $condition";

                } else  {

                    if(strpos($item, 'SELECT') === 0) {

                        $where .= $table . $key . $operand . '(' . $item . ") $condition";
                    } else {

                        $where .= $table . $key . $operand . "'" . addslashes($item) . "' $condition";
                    }
                }

            }

            $where = substr($where, 0, strrpos($where, $condition));

        }

        return $where;

    }

    protected function createJoin($set, $table, $new_where = false) {

        $fields = '';
        $join = '';
        $where = '';
        $tables = '';

        if($set['join']) {

            $join_table = $table;

            foreach ($set['join'] as $key => $item) {

                if(is_int($key)) {
                    if(!$item['table']) continue;
                    else $key = $item['table'];
                }

                if($join) $join .= ' ';

                if($item['on']) {

                    $join_fields = [];

                    switch (2) {

                        case (is_array($item['on']['fields']) && count($item['on']['fields'])):
                            $join_fields = $item['on']['fields'];
                            break;

                        case (is_array($item['on']) && count($item['on'])):
                            $join_fields = $item['on'];
                            break;

                        default:
                            continue 2;
                            break;
                    }

                    if(!$item['type']) $join .= 'LEFT JOIN ';
                    else $join .= trim(strtoupper($item['type'])). ' JOIN ';

                    $join .= $key . ' ON ';

                    if($item['on']['table']) $join .= $item['on']['table'];
                    else $join .= $join_table;

                    $join .= '.' . $join_fields[0] . '=' . $key . '.' .$join_fields[1];

                    $join_table = $key;
                    $tables .= ', ' . trim($join_table);

                    if($new_where) {

                        if($item['where']) {
                            $new_where = false;
                        }

                        $group_condition = 'WHERE';

                    } else {
                        $group_condition = $item['group_condition'] ? strtoupper($item['group_condition']) : 'AND';
                    }

                    $fields .= $this->createFields($item, $key);
                    $where .= $this->createWhere($item, $key, $group_condition);

                }

            }
        }

        return compact('fields', 'join', 'where', 'tables');

    }
}