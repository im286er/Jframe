<?php

/**
 * This is the bootstrap setting of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542606@qq.com | www.supjos.cn>
 */

namespace Jframe\exception;

/**
 * Throws the Class Not Found exception let the user found the reason of the exception occur.
 */
class VerbsNotAllowed extends BaseException
{

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
