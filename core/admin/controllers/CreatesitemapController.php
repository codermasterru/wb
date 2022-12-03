<?php

namespace core\admin\controllers;

use core\base\controllers\BaseMethods;

class  CreatesitemapController extends BaseAdmin
{
    use BaseMethods;

    protected $linkArr = [];

    protected $parsingLogFile = 'parsing_log.txt';

    protected $fileArr = ['jpg', 'png', 'jpeg', 'gif', 'xls', 'xlsx', 'pdf', 'mp4', 'mpeg', 'mp3'];

    protected $filterArr = [
        'url' => [],
        'get' => []
    ];


    protected function inputData()
    {

        if (!function_exists('curl_init')) {

            $this->writeLog('Отсутствует библиотека CURL');
            $_SESSION['res']['answer'] = '<div class="error">Library CURL is absent. Creation of sitemap impossible</div>';
            $this->redirect();
        }

        set_time_limit(0);

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile)) {
            @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile);
        }

        $this->parsing(SITE_URL);

        $this->createSiteMap();

        !$_SESSION['res']['answer'] && $_SESSION['res']['answer'] = '<div class="success">Sitemap is created</div>';
        $this->redirect();

    }

    protected function parsing($url, $index = 0)
    {
        //
        if (mb_strlen(SITE_URL) + 1 === mb_strlen($url) && mb_strpos($url, '/') === mb_strlen($url) - 1) return;

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


        if (!preg_match("/Content-Type:\s+text\/html/ui", $out)) {

            unset($this->linkArr[$index]);

            $this->linkArr = array_values($this->linkArr);

            return;

        }

        // Код ответа
        // Например               HTTP/1.1 200 OK
        if (!preg_match("/HTTP\/\d\.?\d?\s+20\d/uis", $out)) {

            $this->writeLog('Не корректная ссылка при парсинге - ' . $url, $this->parsingLogFile);

            unset($this->linkArr[$index]);

            $this->linkArr = array_values($this->linkArr);

            $_SESSION['res']['answer'] = '<div class="error">Incorrect link parsing' . $url . '<br>Sitemap is created</div>';

            return;

        }

    }

    protected function filter($link)
    {

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