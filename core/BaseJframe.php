<?php

/**
 * This is the application class for the JFrame
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All rights reserved.
 */

namespace Jframe;

use Jframe\exception\ClassNotFound;
use Jframe\exception\ClassNotSetException;

/**
 * The Base class for the PHP Jframe, every one can access it using the Jframe::$app
 * to get the Appcation's instance
 */
class BaseJframe
{
    /**
     * The universal index count program
     * @var int
     */
    private static $index = 0;

    /**
     * Getting the index number of the global runtime
     * @return int The index number
     */
    public static function getIndex()
    {
        return self::$index++;
    }

    /**
     * Return the Application's object to the user to use the global variables
     * @var Application $app
     */
    public static $app = null;

    /**
     * $aliases： The system's aliases
     * @var array The aliases which can be make the system to found the file and require or include.
     */
    private static $aliases = [
        '@Jframe' => __DIR__,
    ];

    /**
     * The class-map loading array
     * @var array $classMap The classmap file which can make the load system a little more faster
     */
    private static $classMap = [];

    /**
     * The Jframe's autoload function which can be load automatic
     * The autoload divide into 3 steps
     * - One:
     *      Load the class file from the class map file array
     * - Two:
     *      Load the class file from the namespace define
     * @param string $className The class which want to be loaded automatic.
     * @author Josin <774542602@qq.com>
     * @throws \Exception
     */
    public static function autoload($className)
    {
        if (!empty(self::$classMap) && \array_key_exists($className, self::$classMap)) {
            require_once(self::$classMap[$className]);
        } else {
            $slashPosition = strpos($className, '\\');
            $rootAlias = substr($className, 0, $slashPosition);
            $alias = '@' . $rootAlias;
            if (!empty(self::$aliases) && \array_key_exists($alias, self::$aliases)) {
                $classFilePath = self::$aliases[$alias] . str_replace('\\', '/', substr($className, $slashPosition));
                $classFileName = $classFilePath . '.php';
                if (\file_exists($classFileName)) {
                    require_once($classFileName);
                } else {
                    throw new ClassNotFound("Class [[{$className}]] Not Found.", '101');
                }
            }
        }
    }

    /**
     * setAlias function is the method which can be used to set the alias for the system to load the file directory and correctly
     * @param string $aliasName The alias name starts with the '@' character
     * @param string $aliasPath The fully path for the alias
     * @param boolean $override The mode for the alias mode
     */
    public static function setAlias($aliasName, $aliasPath, $override = FALSE)
    {
        if (array_key_exists($aliasName, self::$aliases)) {
            if ($override) {
                self::$aliases[$aliasName] = $aliasPath;
            }
        } else {
            self::$aliases[$aliasName] = $aliasPath;
        }
    }

    /**
     * Get the aliase value from the setting Aliases
     * @param string $aliasName
     * @return mixed
     */
    public static function getAlias($aliasName)
    {
        if (array_key_exists($aliasName, self::$aliases)) {
            return self::$aliases[$aliasName];
        }
    }

    /**
     * @param string $classData
     * @param array $params The properties which you want to set for the newly instance Class
     * @return Object The instance of the given class with the name
     * @throws ClassNotSetException
     */
    public static function createObject($classData, array $params = [])
    {
        if (is_string($classData)) {
            $tmpClassData = $classData;
            $classData = [];
            $classData['class'] = $tmpClassData;
        }
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
            if (!empty($params)) {
                foreach ($params as $property => $value) {
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
     * getAllAliases return all the aliases
     * @return array aliases
     */
    public static function getAllAliases()
    {
        return self::$aliases;
    }


    /**
     * Get the class name without the namespace
     * @param $model
     * @return bool|string
     */
    public static function getOnlyClassName($model)
    {
        $className = get_class($model);
        return substr($className, strrpos($className, '\\') + 1);
    }

}
