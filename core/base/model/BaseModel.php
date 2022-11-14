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
     * 'where' => ['fio'=>'smirnova', 'name'=>'Maria', 'surname'=>'Sergeevna'],
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

        // Формирует условие запроса
        $where = $this->createWhere($table, $set);

        // Формирует объединение
        $join_arr = $this->createJoin($table, $set);


        $fields .= $join_arr['fields'];
        $join = $join_arr['join'];
        $where .= $join_arr['where'];

        $fields = rtrim($fields, ',');

        $order = $this->createOrder($table, $set);

        $limit = $set['limit'] ?: '';

        $query = "SELECT $fields FROM $table $join $where $order $limit";

        return $this->query($query);
    }

    protected function createFields($table = false, $set)
    {
        $set['fields'] = $set['fields'] ? $set['fields'] : ['*'];

        return true;
    }

}