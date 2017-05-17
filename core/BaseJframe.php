<?php

/**
 * This is the application class for the JFrame
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All rights reserved.
 */

namespace Jframe;

use Jframe\exception\ClassNotFound;

/**
 * The Base class for the PHP Jframe, every one can access it using the Jframe::$app
 * to get the Appcation's instance
 */
class BaseJframe
{

    /**
     * Return the Application's object to the user to use the global variables
     * @var Application $app 
     */
    public static $app = null;

    /**
     * The initialse code for the PHP Application
     */
    public function __construct()
    {
        
    }

    /**
     * @param string $name
     */
    public function __get($name)
    {
        $methodName = 'get' . ucwords($name);
        if (method_exists($this, $methodName)) {
            return call_user_func([$this, $methodName]);
        }
    }

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
        //exit($className);
        if (!empty(self::$classMap) && \array_key_exists($className, self::$classMap)) {
            require_once(self::$classMap[$className]);
        } else {
            // 命名空间的第一个斜线之前的名字
            $slashPosition = strpos($className, '\\');
            $rootAlias = substr($className, 0, $slashPosition);
            // 将命名空间的其余路径合并成可用的路径
            $alias = '@' . $rootAlias;
            if (!empty(self::$aliases) && \array_key_exists($alias, self::$aliases)) {
                $classFilePath = self::$aliases[$alias] . substr($className, $slashPosition);
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
     */
    public static function getAlias($aliasName)
    {
        if (array_key_exists($aliasName, self::$aliases)) {
            return self::$aliases[$aliasName];
        }
    }

    /**
     * @param string $className
     * @param array $params The properties which you want to set for the newly instance Class
     * @return Object The instance of the given class with the name
     */
    public static function createObject($className, array $params = [])
    {
        $classInstance = (new \ReflectionClass($className))->newInstanceArgs();
        if (!$classInstance instanceof $className) {
            throw new ClassNotFound("Create Class Instance [[{$className}]] Failure.");
        }
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if (property_exists($classInstance, $k)) {
                    $classInstance->$key = $value;
                }
            }
        }
        return $classInstance;
    }

    /**
     * getAllAliases return all the aliases
     * @return array aliases
     */
    public static function getAllAliases()
    {
        return self::$aliases;
    }

}
