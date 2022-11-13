<?php

namespace core\base\exception;


use core\base\controllers\BaseMethods;

class RouteException extends \Exception
{
    protected $messages;

    use BaseMethods;


    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);

        $this->messages = include 'messages.php';

        $error = $this->getMessage() ?: $this->messages[$this->getCode()];

        $error .= "\r\n" . 'file ' . $this->getFile() . "\r\n" . "In line " . $this->getLine() . "\r\n";

        if ($this->messages[$this->getCode()]) $this->messages = $this->messages[$this->getCode()];

        $this->writeLog($error);
        echo $this->messages;
    }
}







