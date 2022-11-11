<?php

// Объявляем константу безопасности
define('VG_ACCESS', true);


// Устанавливаем заголовок
header('Content-Type:text/html;charset-utf-8');


// Стартуем сессию
session_start();

//Подключаем файл настроек
require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';

use core\base\exception\RouteException;
use core\base\controllers\RouteController;
try {
    //Метод будет подключать все остальное(выборки  и тд)
    RouteController::instance()->route();

} catch (RouteException $exception) {

}
