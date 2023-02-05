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

                if ($this->data) {

                    $this->data = $this->data[0];

                    if (count($this->parameters) > 1) {

                        $this->checkDeleteFile();

                    }

                    $settings = $this->settings ?: Settings::instance();

                    $files = $settings::get('fileTemplates');

                    foreach ($files as $file) {

                        foreach ($settings::get('templateArr')['file'] as $item) {

                            if (!empty($this->data[$item])) {

                                $fileData = json_decode($this->data[$item], true);

                                if (is_array($fileData)) {

                                    foreach ($fileData as $f) {

                                        @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $f);

                                    }

                                } else {

                                    @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $f); // delete $f

                                }

                            }

                        }


                    }

                    if (!empty($this->data['menu_position'])) {

                        if (!empty($this->data['parent_id'])) {

                            $pos = $this->get($this->table, [
                                'fields' => ['COUNT(*) as count'],
                                'where' => ['parent_id' => $this->data['parent_id']]
                            ]);


                        }

                    }

                }

            }

        }

        $_SESSION['res']['answer'] = '<div class="error">' . $this->messages['deleteFail'] . '</div>>';

        $this->redirect();
    }

    protected function checkDeleteFile()
    {

        $this->redirect();

    }

}