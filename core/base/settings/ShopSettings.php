<?php

namespace core\base\settings;

use core\base\controllers\Singletone;

class ShopSettings
{
use Singletone{
    instance as traitInstance;
}


    private $baseSettings;

    private $routes = [
        'plugins' => [
            'dir' => false,
            'routes' => [
//                'product' => 'goods'
            ]
        ]
    ];

    private $templateArr = [
        'text' => ['price', 'short'],
        'textarea' => ['goods_content']
    ];

    static public function get($property)
    {
        return self::instance()->$property;
    }

    static private function instance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }


        self::traitInstance()->baseSettings = Settings::instance();
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
        self::$_instance->setProperty($baseProperties);
        return self::$_instance;
    }

    protected function setProperty($properties)
    {
        if ($properties) {
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }


}