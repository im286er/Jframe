<?php

/**
 * This is the bootstrap setting of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\behavior;

use Jframe\base\Object;

class Filter extends Object
{

    /**
     * The filter method's work
     * @param $object
     * @param $method
     */
    public function init($object, $method)
    {
        
    }

    /**
     * @param string $value
     * @return string The upper string
     */
    public function arrayToUpper($value)
    {
        return strtoupper($value);
    }

}
