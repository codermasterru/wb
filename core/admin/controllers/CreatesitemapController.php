<?php

namespace core\admin\controllers;

use core\base\controllers\BaseMethods;

class  CreatesitemapController extends BaseAdmin
{
    use BaseMethods;

    protected $all_links = [];
    protected $temp_links = [];

    protected $maxLinks = 5000;
    protected $parsingLogFile = 'parsing_log.txt';

    protected $fileArr = ['jpg', 'png', 'jpeg', 'gif', 'xls', 'xlsx', 'pdf', 'mp4', 'mpeg', 'mp3'];

    protected $filterArr = [
        'url' => ['order'],
        'get' => ['masha']
    ];


    protected function inputData($links_counter = 1)
    {

        if (!function_exists('curl_init')) {

            $this->cancel(0, 'Library CURL is absent. Creation of sitemap impossible', '', true);

//            $this->writeLog('Отсутствует библиотека CURL');
//            $_SESSION['res']['answer'] = '<div class="error">Library CURL is absent. Creation of sitemap impossible</div>';
//            $this->redirect();
        }

        if (!$this->userId) $this->exectBase();

        if (!$this->checkParsingTable()) {
            $this->cancel(0, 'You have problem with database table parsing', '', true);
        };

        set_time_limit(0);

        $reserve = $this->model->get('parsing_data')[0];

        foreach ($reserve as $name => $item) {
            if ($item) $this->$name = json_decode($item);
            else $this->$name = [SITE_URL];
        }

        $this->maxLinks = (int)$links_counter > 1 ? ceil($this->maxLinks / $links_counter) : $this->maxLinks;

        while ($this->temp_links) {

            $temp_links_counter = count($this->temp_links);

            $links = $this->temp_links;

            $this->temp_links = [];

            if ($temp_links_counter > $this->maxLinks) {

                $links = array_chunk($links, ceil($temp_links_counter / $this->maxLinks));

                $count_chunks = count($links);

                for ($i = 0; $i < $count_chunks; $i++) {

                    $this->parsing($links[$i]);

                    unset($links[$i]);

                    if ($links) {

                        $this->model->edit('parsing_data', [
                            'fields' => [
                                'temp_link' => json_encode(array_merge(...$links)),
                                'all_links' => json_encode($this->all_links)
                            ]
                        ]);

                    }
                }

            } else {

                $this->parsing($links);
            }

            $this->model->edit('parsing_data', [
                'fields' => [
                    'temp_link' => json_encode($this->temp_links),
                    'all_links' => json_encode($this->all_links)
                ]
            ]);

        }

        $this->parsing(SITE_URL);

        $this->createSiteMap();

        !$_SESSION['res']['answer'] && $_SESSION['res']['answer'] = '<div class="success">Sitemap is created</div>';
        $this->redirect();

    }

    protected function parsing($url, $index = 0)
    {
        $curl = curl_init();


        // Базовые настройки CURL

        // Адрес по которому парсим
        curl_setopt($curl, CURLOPT_URL, $url);
        // Ответы от сервера
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Возвращаем заголовки
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        //  Время выполнения скрипта
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        // Данные которые будут загружатся
        curl_setopt(CURLOPT_RANGE, 0 - 4194304);

        $out = curl_exec($curl);

        curl_close($curl);


        if (!preg_match('/Content-Type:\s+text\/html/ui', $out)) {

            unset($this->all_links[$index]);

            $this->all_links = array_values($this->all_links);

            return;

        }

        // Код ответа
        // Например               HTTP/1.1 200 OK
        if (!preg_match('/HTTP\/\d\.?\d?\s+20\d/ui', $out)) {

            $this->writeLog('Не корректная ссылка при парсинге - ' . $url, $this->parsingLogFile);

            unset($this->all_links[$index]);

            $this->all_links = array_values($this->all_links);

            $_SESSION['res']['answer'] = '<div class="error">Incorrect link parsing' . $url . '<br>Sitemap is created</div>';

            return;

        }
//        $str = "<a class='class' id='1' href='ya.ru' data-id='dataid'>";

        preg_match_all('/<a\s*?[^>]*?href\s*?=(["\'])(.+?)\1[^>]*?>/ui', $out, $links);

        if ($links[2]) {

            foreach ($links[2] as $link) {

//                $links[2] = [];
//                $links[2][0] = 'http://yandex.ru/image.jpg?ver1.1';

                if ($link === '/' || $link === SITE_URL . '/') continue;

                foreach ($this->fileArr as $ext) {
                    if ($ext) {

                        $ext = addslashes($ext);
                        $ext = str_replace('.', '\.', $ext);

                        if (preg_match('/' . $ext . '\s*?$|\?[^\/]]/ui', $link)) {
                            continue 2;
                        }
                    }
                }

                if (strpos($link, '/') === 0) {

                    $link = SITE_URL . $link;
                }

                if (!in_array($link, $this->all_links)
                    && $link !== '#'
                    && strpos($link, SITE_URL) === 0) {

                    if ($this->filter($link)) {

                        $this->all_links[] = $link;
                        $this->parsing($link, count($this->all_links) - 1);
                    }
                }
            }
        }

    }

    protected function filter($link)
    {
//        $link = 'https://yandex.ru/asc?order/';

        // Если есть filterArr
        if ($this->filterArr) {

            // Перебираем его
            //     protected $filterArr = [
            //        'url' => ['order'],
            //        'get' => ['masha']
            //    ];
            //
            //                          url       [order]
            foreach ($this->filterArr as $type => $values) {


                // Если есть значение  в type
                if ($values) {
                    //             []   order
                    foreach ($values as $item) {

                        $item = str_replace('/', '\/', addslashes($item));

                        if ($type === 'url') {

                            if (preg_match('/^[^?]*' . $item . '/ui', $link)) {
                                return false;
                            }

                        }

                        if ($type === 'get') {

                            if (preg_match('/(\?|&amp;|=|&)' . $item . '(=|&amp;|&|$)/ui', $link, $matches)) {
                                return false;
                            }

                        }

                    }

                }

            }

        }

        return true;
    }

    protected function checkParsingTable()
    {
        // Формируем массив с названием таблиц
        $tables = $this->model->showTables();

        // Если в массиве нет
        if (!in_array('parsing_data', $tables)) {
            //..создаем таблицу
            $query = 'CREATE TABLE parsing_data (all_links text, temp_links text)';
            // Если не получилось создать, выходим из скрипта
            if (!$this->model->query($query, 'c') ||
                !$this->model->add(
                    'parsing_data',
                    ['fields' => ['all_links' => '', 'temp_links' => '']]
                )
            ) return false;
        }
        return true;

    }

    protected function cancel($success = 0, $message = '', $log_message = '', $exit = false)
    {
        // Массив который будем отдавать клиенту
        $exitArr = [];

        $exitArr['success'] = $success;
        $exitArr['message'] = $message ?: 'ERROR_PARSING';
        $log_message = $log_message ?: $exitArr['message'];

        $class = 'success';

        // Если успешно, то
        if (!$exitArr['success']) {

            $class = 'error';

            $this->writeLog($log_message, 'parsing_log.txt');

        }

        if ($exit) {
            $exitArr['message'] = '<div> class="' . $class . '"' . $exitArr['message'] . '</div>>';
            $exit(json_encode($exitArr));
        }

    }

    protected function createSiteMap()
    {

    }


}

//HTTP/1.1 200 OK
//Date: Sat, 03 Dec 2022 08:28:06 GMT
//Content-Type: text/html;charset-utf-8;charset=UTF-8
//Content-Length: 0
//Connection: keep-alive
//Keep-Alive: timeout=120
//Server: Apache
//Set-Cookie: PHPSESSID=4ur8icm8sdmmlffbv1ajss3akms6vt54; path=/
//Expires: Thu, 19 Nov 1981 08:52:00 GMT
//Cache-Control: no-store, no-cache, must-revalidate
//Pragma: no-cache
//X-Content-Type-Options: nosniff