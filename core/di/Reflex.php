<?php

namespace Jframe\di;

/**
 * The reflex tool class for the PHPer to do the object-create-process, and the initialise process
 * @copyright (c) 2017-2020, www.supjos.cn All Rights Reserved.
 * @version  1.2.2
 */
class Reflex
{

    /**
     * Create an object from a given class-name, along with some class-properties
     * @param array $classData The array contains a key named [[class]], it's value was a object or class-name
     * @param array $properties
     * @return object The class object from the given $className, using the default contructor
     */
    public static function createObject($classData, $properties = [])
    {
        if (!isset($classData['class'])) {
            throw new ClassNotSetException("[[class]] attribute not set.", 300);
        }
        $className = $classData['class'];
        unset($classData['class']);
        $reflectionClass = new \ReflectionClass($className);
        $hasContructor = $reflectionClass->getConstructor();
        if (is_null($hasContructor)) {
            $classObject = $reflectionClass->newInstanceArgs();
        } else {
            $classObject = $reflectionClass->newInstanceArgs($classData);
        }
        if ($classObject instanceof $className) {
            if (!empty($properties)) {
                foreach ($properties as $property => $value) {
                    if (property_exists($classObject, $property)) {
                        $propertyAccess = new \ReflectionProperty($className, $property);
                        $propertyAccess->setAccessible(true);
                        $propertyAccess->setValue($classObject, $value);
                    }
                }
            }
            return $classObject;
        }
    }

    /**
     * Invoke the method of the given class-name, return the method result
     * @param string|object $className The class-name or class object
     * @param string $method The method which you want to invoke from the given class-name
     * @param array $params The parameters which you passed to the method
     * @return mixed The result of the method return
     */
    public static function invoke($className, $method, $params = [])
    {
        $result = '';
        $classMethod = new \ReflectionMethod($className, $method);
        if ($classMethod->isStatic()) {
            $result = $classMethod->invokeArgs(null, $params);
        }
        if ($classMethod->isPublic() && !$classMethod->isStatic()) {
            if (is_object($className) && $className instanceof $className) {
                $classObject = $className;
            } else {
                $classObject = (new \ReflectionClass($className))->newInstanceArgs();
            }
            // Checking the method's parameters suit well
            if (count($classMethod->getParameters()) > count($params)) {
                throw new ParameterNotMatchException("Parameter Not Match!", 301);
            }
            $result = $classMethod->invokeArgs($classObject, $params);
        }
        return $result;
    }

    /**
     * @var array $_container
     */
    private $_container = [];

    /**
     * @param string $className
     * @param array $configs
     */
    public function set($className, $configs = [])
    {
        if (!empty($className) && !isset($this->_container[$className])) {
            $this->_container[$className] = empty($configs) ? [] : $configs;
        }
    }

    /**
     * @var array The collections of the temperary create object process, To accessory the object-create time
     */
    private $_objects = [];

    /**
     * @param string $className, Which class object you want to get from the container
     */
    public function get($className)
    {
        if (isset($this->_objects[$className]) && is_object($this->_objects[$className])) {
            return $this->_objects[$className];
        } else {
            if (isset($this->_container[$className])) {
                $reflectionClass = new \ReflectionClass($className);
                $classContructor = $reflectionClass->getConstructor();
                if (is_null($classContructor)) {
                    return $this->_objects[$className] = self::createObject(['class' => $className], $this->_container[$className]);
                } else {
                    $constructorParameters = $classContructor->getParameters();
                    $dependencyClassName = $constructorParameters[0]->getClass()->getName();
                    return $this->_objects[$className] = self::createObject(['class' => $className, $this->get($dependencyClassName)], $this->_container[$className]);
                }
            } else {
                throw new ClassNotSetException("class [[{$className}]] must be set before get()!", 305);
            }
        }
    }

}

class BaseException extends \Exception
{

    public function __toString()
    {
        echo '<pre>';
        return parent::__toString();
    }

}

class ParameterNotMatchException extends BaseException
{

    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return parent::__toString();
    }

}

class ClassNotSetException extends BaseException
{

    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return parent::__toString();
    }

}
