<?php
/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\exception;

class CsrfAttackException extends BaseException
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}