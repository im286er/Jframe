<?php
/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\tool;

class Inflector
{
    /**
     * Return the camel2Id string. eg.
     * userName ===> User Name
     * @param $string
     * @return string
     */
    public static function camel2Id($string)
    {
        $result = [];
        \preg_match_all('/[A-Z][^A-Z]+/', ucfirst($string), $result);
        return implode(' ', $result[0]);
    }
}