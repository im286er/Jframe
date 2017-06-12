<?php
/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\validator;

class RequiredValidator extends Validator
{
    public function getValidatorInfo($ruleName)
    {
        return ['notEmpty', '不能为空!'];
    }

}