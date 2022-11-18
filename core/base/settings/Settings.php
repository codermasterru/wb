<?php

namespace core\base\settings;

use core\base\controllers\Singletone;

class Settings
{
    use Singletone;


    private $routes = [
        'admin' => [
            'alias' => 'admin',
            'path' => 'core/admin/controllers/',
            'hrUrl' => false,
            'routes' => [

            ]
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'dir' => false,
            'routes' => [

            ]
        ],
        'user' => [
            'path' => 'core/user/controllers/',
            'hrUrl' => true,
            'routes' => [
//                'catalog'=>'site/input/output'
            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];

    private $templateArr = [
        'text' => ['name', 'phone', 'address'],
        'textarea' => ['goods_content']
    ];

    private $expansion = 'core/admin/expansion/';

    private $defaultTable = 'teachers';

    private $projectTables = [
        'teachers' => ['name'=>'Учителя','img'=>'pages.png' ],
        'students' => ['name'=>'Ученики']
    ];

    private $blockNeedle = [
        'vg-rows'=>[],
        'vg-img' => ['id'],
        'vg-content' =>['content']
    ];

    private $translate = [
        'name' =>['Название', 'Не более 100 символов']
    ];

    static public function get($property)
    {
        return self::instance()->$property;
    }

    public function clueProperties($class)
    {
        $baseProperties = [];

        foreach ($this as $name => $item) {
            $property = $class::get($name);

            if (is_array($property) && is_array($item)) {
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }
            if ($property) $baseProperties[name] = $this->$name;
        }

        return $baseProperties;
    }

// Метод склеивает массив
    public function arrayMergeRecursive()
    {

        $arrays = func_get_args();

        $base = array_shift($arrays);

        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                if (is_array($value) && is_array($base[$key])) {
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                } else {
                    if (is_int($key)) {
                        if (!in_array($value, $base)) array_push($base, $value);
                        continue;
                    }
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }
}