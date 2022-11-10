<?php

namespace core\base\controllers;

use core\base\exception\RouteException;
use  core\base\settings\ShopSettings;
use  core\base\settings\Settings;

class RouteController
{
//    use Singletone;
    //Роуты??
    protected $routes;

    //
    protected $controller;

    // Собирает данные из БД
    protected $inputMethod;

    // Выводит отображение
    protected $outputMethod;

    //
    protected $parametrs;


    static private $_instance;

    private function __construct()
    {
        //  Получаем адресную строку
        $address_str = $_SERVER['REQUEST_URI'];

        //strrpos — Возвращает позицию последнего вхождения подстроки в строке
        //strlen strlen — Возвращает длину строки
        // Если символ / стоит в конце строки и это не корень сайта
        if (strrpos($address_str, '/') === strlen($address_str) - 1 &&
            strrpos($address_str, '/') !== 0) {
            //Перенаправляем на
            $this->redirect(rtrim($address_str, '/'), 301);
        }


        //substr Возвращает подстроку строки string, начинающейся с start символа по счету и длиной length символов.
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));

        if ($path === PATH) {

            $this->routes = Settings::get('routes');

            if (!$this->routes) throw new RouteException('Сайт находится на техническом обслуживании');

            //Если позиция найдена
            if (strrpos($address_str, $this->routes['admin']['alias']) === strlen(PATH)) {
                // Админка
            } else {
                //Пользовательская часть
            }
        } else {
            try {
                throw new \Exception('Некорректная директория сайта');
            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }
    }


    private function redirect(string $rtrim, int $int)
    {
    }


    private function __clone()
    {
    }

    /**
     * @return RouteController|Singletone
     */
    static public function instance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }


}