<?php

/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 *
 * ``` Application Class
 * ```
 * ``` The most important class in the Jframe framework
 *
 * In Every Class in the development You can use the global variable
 * ``` Jframe::$app to access the Application Object
 *
 * ``` Also you can use the Jframe::$app to get the system defined object
 *
 * The class defined in the web.php file named as below: 'components'=>[]
 *
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe;

use Jframe;
use Jframe\base\UrlManager;

class Application extends base\Object
{
    /**
     * @var string The web root path
     */
    public $webPath;

    /**
     * @var string The default controller, configured in web.php
     */
    public $defaultController;

    /**
     * @var string The default method which means the default-method configured in web.php
     */
    public $defaultMethod;

    /**
     * @var string The module path, it point to the current accessing module
     */
    public $modulePath;

    /**
     * @var string The controller name without any suffix or prefix
     */
    public $pureController;

    /**
     * @var string The method which means the the current method, without the action prefix
     */
    public $pureMethod;

    /**
     * @var string The current view file name, without the .php or .html suffix
     */
    public $viewId;

    /**
     * @var object The current controller object
     */
    public $context;

    /**
     * @var array The system's important components
     */
    private static $classNameMap = [];

    /**
     * @var array All the instance of the given class
     */
    private static $_set = [];

    /**
     * Application constructor.
     */
    public function __construct()
    {
        Jframe::$app = $this;
    }

    /**
     * @param string $className
     * @return Object The object or instance of the Given Class Name
     * @throws exception\ClassNotFound
     */
    public function __get($className)
    {
        if (empty(static::$_set[$className])) {
            if (!empty(static::$classNameMap[$className])) {
                $class = static::$classNameMap[$className]['class'];
                unset(static::$classNameMap[$className]['class']);
                $tmpComponentInstance = Jframe::createObject($class, !empty(static::$classNameMap[$className]) ? static::$classNameMap[$className] : []);
                static::$_set[$className] = $tmpComponentInstance;
            } else {
                throw new exception\ClassNotFound("Component Class [[{$className}]] Not Found.", '105');
            }
        }
        return static::$_set[$className];
    }

    /**
     * Run the Jframe, The entrance of the Jframe using the given config to initialise the Jframe instance
     * @param array $config The config for the Jframe
     */
    public function run($config = [])
    {
        if (!empty($config)) {
            $components = $config['components'];
            self::$classNameMap = $components;
            unset($config['components']);
            foreach ($config as $key => $value) {
                Jframe::$app->$key = $value;
            }
        }
        Jframe::createObject(UrlManager::className())->dealUrl();
    }

}
