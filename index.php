<?php

// Объявляем константу безопасности
define('VG_ACCESS', true);


// Устанавливаем заголовок
header('Content-Type:text/html;charset-utf-8');

error_reporting(E_ERROR | E_PARSE);

// Стартуем сессию
session_start();

//Подключаем файл настроек подключения к БД и др
require_once 'config.php';

// Внутренние константы
require_once 'core/base/settings/internal_settings.php';

use core\base\exception\RouteException;
use core\base\exception\DbException;
use core\base\controllers\BaseRoute;

try {
    // Обращаемся к Методу routeDirection() класса BaseRoute
    BaseRoute::routeDirection();

} catch (RouteException|DbException $e) {
    exit($e->getMessage());
}