<?php

namespace core\base\settings;

use core\base\controllers\Singletone;

trait BaseSettings
{
    use Singletone{
        instance as SingletoneInstance;
    }

    private $baseSettings;

    static public function get($property)
    {
        return self::instance()->$property;
    }

    static private function instance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }


        self::SingletoneInstance()->baseSettings = Settings::instance();
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