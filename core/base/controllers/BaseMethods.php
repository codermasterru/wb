<?php

namespace core\base\controllers;

trait BaseMethods
{

    //Очистка строковых данных данных
    protected function clearStr($str)
    {
        if (is_array($str)) {
            foreach ($str as $key => $item) $str[$key] = trim(strip_tags($item));
            return $str;
        } else {
            return trim(strip_tags($str));
        }
    }

    // Очистка цифровых данных
    protected function clearNum($num)
    {
        return $num * 1;
    }

    /**
     * @return bool
     */
    protected function isPost()
    {

        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * @return bool
     */
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

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

    protected function getStyles()
    {
        if ($this->styles) {
            foreach ($this->styles as $style) {
                echo '<link rel="stylesheet" href="' . $style . '">';
            }
        }
    }

    protected function getScripts()
    {

        if ($this->scripts) {
            foreach ($this->scripts as $script) {
                echo '<script src="' . $script . '"></script>';
            }
        }

    }

    protected function writeLog($message, $file='log.txt', $event= 'Fault'){

        $dateTime = new \DateTime();

        $str = $event . ':' . $dateTime->format('d-m-Y G:i:s') . ' - ' . $message . "\r\n";

        file_put_contents('log/' . $file, $str, FILE_APPEND);
    }


}