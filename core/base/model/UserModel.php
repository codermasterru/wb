<?php

namespace core\base\model;

use core\base\controllers\BaseMethods;
use core\base\controllers\Singletone;
use core\base\exception\AuthException;

class UserModel extends BaseModel
{

    use Singletone;

    use BaseMethods;

    // Имя куки длоя пользовательской части
    private $cookieName = 'identifier';


    private $cookieAdminName = 'WQEngineCache';

    // Массив с данными  из БД
    private $userData = [];

    // Сюда будем сваливать ошибки
    private $error;

    // Таблица где будем хранить данные пользователей сайта
    private $userTable = 'visitors';


    // Таблица с админискими данными
    private $adminTable = 'users';


    // Таблица в которой содержатся некорректные попытки входа
    private $blockedTable = 'blocked_access';

// Возвращает  имя админской таблицы
    public function getAdminTable()
    {

        return $this->adminTable;

    }

// Возвращает  имя таблицы с блоками

    public function getBlockedTable()
    {

        return $this->blockedTable;

    }

// Возвращает  ошибки

    public function getLastError()
    {

        return $this->error;

    }

// Установили куки , создали таблицу, сделали записи
    public function setAdmin()
    {

        $this->cookieName = $this->cookieAdminName;

        $this->userTable = $this->adminTable;


        // Проверяет содержится ли  БД таблица  с данным именем
        // Если не то создаем
        if (!in_array($this->userTable, $this->showTables())) {

            // Создаем запрос на создание таблицы user
            $query = 'CREATE TABLE ' . $this->userTable . '
            (
            id int auto_increment primary key,
            name  varchar(255) null,
            login varchar(255) null,
            password varchar(32) null,
            credentials text null
            )
            charset = utf8
            ';

            if (!$this->query($query, 'u')) {

                exit('Ошибка создания таблицы ' . $this->userTable);
            }

            // Добавили  учетку по умолчанию (admin, admin, 123)
            $this->add($this->userTable, [
                'fields' => ['name' => 'admin', 'login' => 'admin', 'password' => md5('123')]
            ]);
        }

        // Создаем таблицу  с блоками
        if (!in_array($this->blockedTable, $this->showTables())) {

            $query = 'CREATE TABLE ' . $this->blockedTable . '
            (
            id int auto_increment primary key,
            login varchar(255) null,
            ip varchar(32) null,
            trying tinyint(1) null,
            time datetime null
            )
            charset = utf8
            ';

            if (!$this->query($query, 'u')) {

                exit('Ошибка создания таблицы ' . $this->blockedTable);
            }

        }

    }

// Проверка пользователя
    public function checkUser($id = false, $admin = false)
    {

        // Если пришел $admin  и userTable не равен adminTable то вызовем setAdmin()
        $admin && $this->userTable !== $this->adminTable && $this->setAdmin();

        // Метод по умолчанию
        $method = 'unPackage';

        // Если пришел id
        if ($id) {

            //  В массив с данными попадает этот id
            $this->userData['id'] = $id;

            // И перепределили метод на set
            $method = 'set';

        }

        try {

            $this->$method();

        } catch (AuthException $e) {

            $this->error = $e->getMessage();

            !empty($e->getCode()) && $this->writeLog($this->error, 'log_user.txt');

            return false;
        }

        // Вернет массив с данными
        return $this->userData;

    }

    private function set()
    {

        $cookieString = $this->package();

        if ($cookieString) {

            setcookie($this->cookieName, $cookieString, time() + 60 * 60 * 24 * 365 * 10, PATH);

            return true;

        }

        throw new AuthException('Ошибка формирования cookie', 1);

    }

    private function package()
    {

        if (!empty($this->userData['id'])) {

            $data['id'] = $this->userData['id'];

            $data['version'] = COOKIE_VERSION;

            $data['cookieTime'] = date('Y-m-d H:i:s');

            return Crypt::instance()->encrypt(json_encode($data));

        }

        throw new AuthException('Некорректный идентификатор  пользователя - ' . $this->userData['id'], 1);

    }

    private function unPackage()
    {

        if (empty($_COOKIE[$this->cookieName]))
            throw new AuthException('Отсутствуют cookie пользователя');

        $data = json_decode(Crypt::instance()->decrypt($_COOKIE[$this->cookieName]), true);

        if (empty($data['id']) || empty($data['version']) || empty($data['cookieTime'])) {

            $this->logout();

            throw new AuthException('Некорректные данные в cookie пользователя', 1);

        }

        $this->validate($data);

        $this->userData = $this->get($this->userTable, [
            'where' => ['id' => $data['id']]
        ]);

        if (!$this->userData) {

            $this->logout();
            throw new AuthException('Не найдены данные в таблице ' . $this->userTable . ' по идентификатору ' . $data['id'], 1);

        }

        $this->userData = $this->userData[0];

        return true;

    }

    // Проверяет версию cookie и время cookie
    private function validate($data)
    {

        if (!empty(COOKIE_VERSION)) {


            if ($data['version'] !== COOKIE_VERSION) {

                $this->logout();

                throw new AuthException('Некорректная версия куки');

            }
        }

        if (!empty(COOKIE_TIME)) {

            if ((new \DateTime()) > (new \DateTime($data['cookieTime']))->modify(COOKIE_TIME . ' minutes')) {

                throw  new AuthException('Превышено время бездействия пользователя');

            }

        }

    }

    public function logout()
    {

        setcookie($this->cookieName, '',1, PATH);

    }

}















