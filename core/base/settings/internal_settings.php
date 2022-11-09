<?php

defined('VG_ACCESS') or die;

// Путь к шаблонам пользовательской части сайта
const TEMPLATE = 'template/default';

//Путь к административной панели сайта
const ADMIN_TEMPLATES = 'core/admin/views/';

// Если нужно заставить перелогиниться
const COOKIE_VERSION = '1.0.0';

// Ключ шифрования
const CRYPT_KEY = '';

// Ограничение времени авторизации (60 МИНУТ)
const COOKIE_TIME = '60';

//Время блокировки злоумышленника (защита перебора паролей)
const  BLOCK_TIME = 3;

//Константа для постраничной навигации
const QTY_LINKS = 3;

//  Путь к админским скриптам и стилям
const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => []
];

//  Путь к пользовательским скриптам и стилям
const USER_ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => []
];


//


//