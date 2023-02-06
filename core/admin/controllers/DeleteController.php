<?php

namespace core\admin\controllers;

use core\base\settings\Settings;

class DeleteController extends BaseAdmin
{

    protected function inputData()
    {

        if (!$this->userId) $this->exectBase();

        // Получаем текущую таблицу
        $this->createTableData();

        if (!empty($this->parameters[$this->table])) {

            // Чистим id
            $id = is_numeric($this->parameters[$this->table]) ?
                $this->clearNum($this->parameters[$this->table]) :
                $this->clearStr($this->parameters[$this->table]);


            // Если есть id
            if ($id) {

                $this->data = $this->model->get($this->table, [
                    'where' => [$this->columns['id_row'] => $id]
                ]);

                // Если есть данные
                if ($this->data) {

                    // Выбираем нужные данные
                    $this->data = $this->data[0];

                    // Если есть параметры
                    if (count($this->parameters) > 1) {

                        $this->checkDeleteFile();

                    }

                    // Собираем настройки
                    $settings = $this->settings ?: Settings::instance();
                    $files = $settings::get('fileTemplates');


                    if ($files) {

                        foreach ($files as $file) {

                            foreach ($settings::get('templateArr')[$file] as $item) {

                                if (!empty($this->data[$item])) {

//                                    if (preg_match('/^[\[{].*?[}\]]$/',$this->data[$item]))
//                                        $fileData = json_decode($this->data[$item], true);
//                                    else
//                                        $fileData = $this->data[$item];

                                    $fileData = json_decode($this->data[$item], true) ?: $this->data[$item];


                                    if (is_array($fileData)) {

                                        foreach ($fileData as $f) {

                                            @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $f);

                                        }

                                    } else {

                                        @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $fileData); // delete $f

                                    }

                                }

                            }


                        }


                    }


                    if (!empty($this->data['menu_position'])) {

                        $where = [];

                        if (!empty($this->data['parent_id'])) {

                            $pos = $this->model->get($this->table, [
                                'fields' => ['COUNT(*) as count'],
                                'where' => ['parent_id' => $this->data['parent_id']],
                                'no_concat' => true
                            ])[0]['count'];

                            $where = ['where' => 'parent_id'];
                        } else {

                            $pos = $this->model->get($this->table, [
                                'fields' => ['COUNT(*) as count'],
                                'no_concat' => true
                            ])[0]['count'];

                        }

                        // updateMenuPosition  ($table,$row,$where,        $end_pos, $update_rows = [])
                        $this->model->updateMenuPosition($this->table,
                            //$row,           $where,                          $end_pos, $update_rows = []
                            'menu_position', [$this->columns['id_row'] => $id], $pos, $where);

                    }


                    // Если удаление прошло успешно
                    if ($this->model->delete($this->table, ['where' => [$this->columns['id_row'] => $id]])) {

                        $tables = $this->model->showTables();

                        if (in_array('old_alias', $tables)) {

                            $this->model->delete('old_alias', [
                                'where' => [
                                    'table_name' => $this->table,
                                    'table_id' => $id
                                ]
                            ]);

                        }

                        $manyToMany = $settings::get('manyToMany');

                        if ($manyToMany) {

                            foreach ($manyToMany as $mTable => $tables) {

                                $targetKey = array_search($this->table, $tables);

                                if ($targetKey !== false) {

                                    $this->model->delete($mTable, [
                                        'where' => [$tables[$targetKey] . '_' . $this->columns['id_row'] = $id]
                                    ]);

                                }

                            }

                        }

                        $_SESSION['res']['answer'] = '<div class="success">' . $this->messages['deleteSuccess'] . '</div>>';

                    }

                }

            }

        }

        $_SESSION['res']['answer'] = '<div class="error">' . $this->messages['deleteFail'] . '</div>>';

        $this->redirect($this->adminPath . 'show/' . $this->table);
    }

    protected function checkDeleteFile()
    {

        $this->redirect();

    }

}