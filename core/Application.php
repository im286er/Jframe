<?php

namespace Jframe;

use Jframe;
use Jframe\base\UrlManager;

class Application extends base\Object
{
    /**
     * @var array The system's important components 
     */
    private static $classNameMap = [
        'request'  => '\Jframe\base\Request',
        'response' => '\Jframe\base\Response',
        'view'     => '\Jframe\base\View'
    ];
    
    /**
     * @var array All the instance of the given class
     */
    private $_set = [];

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
        if (empty($this->_set[$className]) || !($this->_set[$className] instanceof $className)){
            if (!empty(static::$classNameMap[$className])){
                $this->_set[$className] = Jframe::createObject(static::$classNameMap[$className]);
            } else {
                throw new exception\ClassNotFound("Component Class [[{$className}]] Not Found.", '105');
            }
        }
        return $this->_set[$className];
    }

    /**
     * Return the status code for access-user
     * @var int $statusCode StatusCode default : 200 : OK
     */
    private $statusCode = 200;

    /**
     * Return the status code for access-user
     * @return int $statusCode StatusCode default : 200 : OK
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * The version of the Jframe
     * @var string $version
     */
    private $version = '1.5.0';

    /**
     * Return the version info. of the Jframe
     * @return string version name
     */
    public function getVersion()
    {
        return $this->version;
    }

    /* The View of the Controller */

    private $view = null;

    /**
     * The View of the Jframe to show the html
     * @return \Jframe\base\View $view
     */
    public function getView()
    {
        if ($this->view !== null) {
            return $this->view;
        } else {
            $this->view = new base\View();
            return $this->view;
        }
    }

    /**
     * __set() method implement the function which can modify the class's attribute external.
     * @param string $name The attribute you want to set
     * @param mixed $value The value for the attribute
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
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
            // 其余的配置文件附属到Application对象上
            foreach ($config as $key => $value) {
                Jframe::$app->$key = $value;
            }
        }
        // 初始化后进行URL的路由处理
        (new UrlManager())->dealUrl();
    }
}
