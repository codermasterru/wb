<?php

defined('VG_ACCESS') or die;


// Путь к шаблонам пользовательской части сайта
const TEMPLATE = 'template/default/';

//Путь к административной панели сайта
const ADMIN_TEMPLATE = 'core/admin/views/';

const UPLOAD_DIR = 'userfiles/';

// Если нужно заставить перелогиниться
const COOKIE_VERSION = '1.0.0';

// Ключ шифрования
const CRYPT_KEY = 'bPeShVmYq3t6w9z$G-KaPdSgVkYp3s6v%C*F-JaNdRgUkXp2w!z$C&F)J@NcRfUj3t6w9z$B&E)H@McQkYp3s6v9y$B?E(H+RgUkXp2s5v8y/B?D@NcRfUjXn2r5u8x/';

// Ограничение времени авторизации (60 МИНУТ)
const COOKIE_TIME = '60';

//Время блокировки злоумышленника (защита перебора паролей)
const  BLOCK_TIME = 3;

//Константа для постраничной навигации
const QTY_LINKS = 3;

//  Путь к админским скриптам и стилям
const ADMIN_CSS_JS = [
    'styles' => ['css/main.css'],
    'scripts' => [
        'js/frameworkfunctions.js',
        'js/scripts.js'
    ]
];

//  Путь к пользовательским скриптам и стилям
const USER_CSS_JS = [
    'styles' => [],
    'scripts' => []
];


use core\base\exception\RouteException;

// Функция автозагрузки
/**
 * @throws RouteException
 */
function autoloadMainClasses($class_name)
{

// !!func
//str_replace - Заменяет все вхождения строки поиска на строку замены
    $class_name = str_replace('\\', '/', $class_name);

    if (!@include_once $class_name . '.php') {
        echo 'попали в ошибку';
        throw new RouteException('Не верное имя файла для подключения - ' . $class_name);
    }
}

// Регистрирует функцию автозагрузки
spl_autoload_register('autoloadMainClasses');

//