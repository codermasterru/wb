<?php

namespace core\base\controllers;

trait BaseMethods
{

    //Очистка строковых данных данных
    protected function clearStr($str)
    {
        if (is_array($str)) {
            foreach ($str as $key => $item) $str[$key] = $this->clearStr($item);
            return $str;
        } else {
            return trim(strip_tags($str));
        }
    }

    // Очистка цифровых данных
    protected function clearNum($num)
    {
        return (!empty($num) && preg_match('/\d/', $num)) ?
            preg_replace('/[^\d.]/', '', $num) * 1 : 0;
    }

    // Проверяет посланы ли данные постом
    protected function isPost()
    {

        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    // Определяем идет ли к нам Ajax запрос
    protected function isAjax()
    {
        // Если Аджаксом ничего не было передано  возвращаем false
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    // "Реддиректим" на нужную страницу
    protected function redirect($http = false, $code = false)
    {
        if ($code) {
            $codes = ['301' => 'HTTP/1.1 301 Move Permanently'];

            if ($codes[$code]) {
                header($codes[$code]);
            }
        }
        if ($http) $redirect = $http;
        else $redirect = $_SERVER['HTTP_REFERER'] ?? PATH;

        header("Location: $redirect");
        exit();
    }

    // Подключаем стили
    protected function getStyles()
    {
        if ($this->styles) {
            foreach ($this->styles as $style) {
                echo '<link rel="stylesheet" href="' . $style . '">';
            }
        }
    }

    // Подключаем скрипты
    protected function getScripts()
    {

        if ($this->scripts) {
            foreach ($this->scripts as $script) {
                echo '<script src="' . $script . '"></script>';
            }
        }

    }

    // Логируем
    protected function writeLog($message, $file = 'log.txt', $event = 'Fault')
    {

        $dateTime = new \DateTime();

        $str = $event . ':' . $dateTime->format('d-m-Y G:i:s') . ' - ' . $message . "\r\n";

        file_put_contents('log/' . $file, $str, FILE_APPEND);
    }

    protected function getController(){

        return $this->controller ?: $this->controller = preg_split('/_?controller/', strtolower(preg_replace('/([^A-Z])(A-Z)/', '$1_$2', (new \ReflectionClass($this))->getShortName())),0,PREG_SPLIT_NO_EMPTY)[0];

    }

}