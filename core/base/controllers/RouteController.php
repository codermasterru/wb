<?php

namespace core\base\controllers;

use core\base\exception\RouteException;
use  core\base\settings\Settings;

class RouteController extends BaseController
{
    use Singletone;

    private function __construct()
    {
        //  Получаем адресную строку
        $address_str = $_SERVER['REQUEST_URI'];

        if($_SERVER['QUERY_STRING']) {
            $address_str = substr($address_str, 0, strpos($address_str, $_SERVER['QUERY_STRING']) - 1);
        }

        //substr Возвращает подстроку строки string, начинающейся с start символа по счету и длиной length символов.
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));

        if ($path === PATH) {
            if(strrpos($address_str, '/') === strlen($address_str) - 1
                && strrpos($address_str, '/') !== strlen(PATH) - 1) {

                $this->redirect(rtrim($address_str, '/'), 301);

            }

            $this->routes = Settings::get('routes');

            if (!$this->routes) throw new RouteException('Отсутствуют маршруты в базовых настройках', 1);

            //Разбиваем  адресную строку  -->  array
            $url = explode('/', substr($address_str, strlen(PATH)));

            //Если позиция найдена
            if ($url[0] && $url[0] === $this->routes['admin']['alias']) {

                array_shift($url);

                // Если выполнилось то попали на плагин
                if ($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])) {

                    $plugin = array_shift($url);

                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');

                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')) {

                        $pluginSettings = str_replace('/', '\\', $pluginSettings);

                        $this->routes = Settings::get('routes');
                    }
                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    $dir = str_replace('//', '/', $dir);

                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;

                    $hrUrl = $this->routes['plugins']['hrUrl'];

                    $route = 'plugins';

                    // Иначе попали в административную панель
                } else {
                    //Определяем какой контроллер будет обрабатывать
                    $this->controller = $this->routes['admin']['path'];

                    // Будет ли ЧПУ
                    $hrUrl = $this->routes['admin']['hrUrl'];

                    $route = 'admin';
                }
            } else {

                //Определяем нужен ЧПУ или нет --> bool
                $hrUrl = $this->routes['user']['hrUrl'];

                //Выясняем маршрут
                $this->controller = $this->routes['user']['path'];

                $route = 'user';
            }

            $this->createRoute($route, $url);

            // Если на месте параметров что то есть , то
            if ($url[1]) {

                $count = count($url);
                $key = '';

                // Если работаем не с ЧПУ
                if (!$hrUrl) {
                    $i = 1;
                } else {
                    //Иначе работаем с ЧПУ
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }

                for (; $i < $count; $i++) {
                    if (!$key) {
                        $key = $url[$i];
                        $this->parameters[$key] = '';
                    } else {
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }

        } else {
            throw new RouteException('Некорректная директория сайта', 1);
        }
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
            if ($this->routes[$var]['routes'][$arr[0]]) {

                $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);

                $this->controller .= ucfirst($route[0] . 'Controller');

            } else {

                $this->controller .= ucfirst($arr[0] . 'Controller');

            }
        } else {
            $this->controller .= $this->routes['default']['controller'];
        }

        //
        $this->inputMethod = $route[1] ?: $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ?: $this->routes['default']['outputMethod'];

    }


}