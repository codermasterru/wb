<?php

namespace core\admin\controllers;

use core\base\controllers\BaseMethods;

class CreateSiteMapController extends BaseAdmin
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

        if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile)){
                @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile);
        }

        $this->parsing(SITE_URL);

        $this->createSiteMap();

        !$_SESSION['res']['answer'] && $_SESSION['res']['answer'] = '<div class="succes">Sitemap is created</div>';
        $this->redirect();

    }

    protected function parsing($url, $index = 0)
    {

    }

    protected function filter($link)
    {

    }

    protected function createSiteMap()
    {

    }


}