<?php

/**
 * This is the Base Exception class for Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\exception;

class GuestNotAllowedException extends BaseException
{

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
