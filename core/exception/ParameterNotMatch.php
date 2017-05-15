<?php

namespace Jframe\exception;

/**
 * Throws the Class Not Found exception let the user found the reason of the exception occur.
 */
class ParameterNotMatch extends BaseException
{

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
