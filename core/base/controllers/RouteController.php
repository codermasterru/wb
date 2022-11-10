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
            if (strpos($address_str, $this->routes['admin']['alias']) === strlen(PATH)) {

                // Админка
                $url = explode('/', substr($address_str, strlen(PATH . $this->routes['admin']['alias']) + 1));

                // Если выполнилось то попали на плагин
                if ($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])) {


                    // Иначе попали в административную панель
                } else{

                }
            } else {
                //Пользовательская часть

                //Разбиваем  адресную строку  -->  array
                $url = explode('/', substr($address_str, strlen(PATH)));

                //Определяем нужен ЧПУ или нет --> bool
                $hrUrl = $this->routes['user']['hrUrl'];

                //Выясняем маршрут
                $this->controller = $this->routes['user']['path'];

                $route = 'user';
            }

            $this->createRoute($route, $url);
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

    /**
     * @param $var
     * @param $arr
     * @return void
     */
    private function createRoute($var, $arr)
    {
        $route = [];

        if (!empty($arr[0])) {
            // Если существует альяс
            if ($this->routes[$var]['routes'][$arr[0]]) {
                $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);
                $this->controller .= ucfirst(route[0] . 'Controller');
            } else {
                $this->controller .= ucfirst($arr[0] . 'Controller');
            }
        } else {
            $this->controller .= $this->routes['default']['controller'];
        }

        $this->inputMethod = $route[1] ?: $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ?: $this->routes['default']['outputMethod'];

//        exit();
    }


}