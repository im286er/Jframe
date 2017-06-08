<?php

namespace Jframe;

use Jframe;
use Jframe\base\UrlManager;

class Application extends base\Object
{

    /**
     * @var array The system's important components
     */
    private static $classNameMap = [];

    /**
     * @var array All the instance of the given class
     */
    private static $_set = [];

    public function __construct()
    {
        Jframe::$app = $this;
    }

    /**
     * @param string $className
     * @return Object The object or instance of the Given Class Name
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
