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
                $tmpComponentInstance = Jframe::createObject(static::$classNameMap[$className]['class']);
                // If contains the additional attributes to attach it to the Object
                unset(static::$classNameMap[$className]['class']);
                if (!empty(static::$classNameMap[$className])) {
                    foreach (static::$classNameMap[$className] as $option => $value) {
                        $tmpComponentInstance->$option = $value;
                    }
                }
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
        // 初始化一些必要组件
        if (!empty($config)) {
            // 可以添加额外的需要初始化的组件的配置
            $components = $config['components'];
            self::$classNameMap = $components;
            unset($config['components']);
            // 其余的配置文件附属到Application对象上
            foreach ($config as $key => $value) {
                Jframe::$app->$key = $value;
            }
        }
        // 初始化后进行URL的路由处理
        (new UrlManager())->dealUrl();
    }

}
