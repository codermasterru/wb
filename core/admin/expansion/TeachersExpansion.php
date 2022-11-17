<?php

namespace core\admin\expansion;

use core\base\controllers\Singletone;

class TeachersExpansion
{
    use Singletone;

    public function expansion($args = []){
        $this->title = 'New Title';
    }
}