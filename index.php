<?php

// Объявляем константу безопасности
define('VG_ACCESS', true);


// Устанавливаем заголовок
header('Content-Type:text/html;charset-utf-8');

error_reporting(E_ERROR | E_PARSE);

// Стартуем сессию
session_start();

//Подключаем файл настроек
require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';

use core\base\exception\RouteException;
use core\base\exception\DbException;
use core\base\controllers\RouteController;


try {
    //Метод будет подключать все остальное(выборки  и тд)
    RouteController::instance()->route();

} catch (RouteException $e) {
    exit($e->getMessage());
}
catch (DbException $e) {
    exit($e->getMessage());
}