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
