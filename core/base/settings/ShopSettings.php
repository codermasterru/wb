<?php

namespace core\base\settings;


class ShopSettings
{

    use BaseSettings;

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
}