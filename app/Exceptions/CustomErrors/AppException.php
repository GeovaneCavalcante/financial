<?php

namespace App\Exceptions\CustomErrors;

use \Exception;

class AppException extends Exception
{
    public $message = 'Internal Server Error';
    public $code = 500;

    public function __construct($message = null, $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
