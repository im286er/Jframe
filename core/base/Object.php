<?php

/**
 * The base class of which the class want to implement the *property* feature
 * *property* feature:
 * - getter and setter methods
 */

namespace Jframe\base;

/**
 * The Object class for the Jframe
 * @author Josin <774542602@qq.com>
 */
class Object
{
    /**
     * __get() method implement the function which can invoke the function in a way of value-access.
     * @param string $name The method which you want to invoke
     */
    public function __get($name)
    {
        if (property_exists($this, $name)){
            return $this->$name;
        }
        $methodName = 'get' . ucwords($name);
        if (method_exists($this, $methodName)){
            return call_user_func([$this, $methodName]);
        }
    }
    
    /**
     * @return string The class name which to be invoked with the corresponding namespace
     */
    public static function className()
    {
        return get_called_class();
    }
}

