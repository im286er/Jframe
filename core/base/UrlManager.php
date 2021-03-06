<?php

/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\base;

use Jframe;
use Jframe\exception\ControllerNotFound;
use Jframe\exception\MethodNotFound;
use Jframe\exception\ParameterNotMatch;
use Jframe\exception\VerbsNotAllowed;
use Jframe\exception\CsrfAttackException;

class UrlManager extends Object
{

    /**
     * Change the array's value into lower
     * @param type $value
     * @return type
     */
    private static function changeLowerArray($value)
    {
        return strtolower($value);
    }

    /**
     * dealUrl to deal with the request from the user
     */
    public function dealUrl()
    {
        /**
         * The Web URL of the Jframe
         */
        Jframe::$app->webPath = substr(dirname($_SERVER['SCRIPT_NAME']), strlen(dirname(dirname($_SERVER['SCRIPT_NAME']))));
        if (isset($_SERVER['PATH_INFO'])) {
            $pathInfo = trim($_SERVER['PATH_INFO'], '/');
            $urlInfo = explode('/', $pathInfo);
        } else {
            $urlInfo[0] = Jframe::$app->defaultController;
            $urlInfo[1] = Jframe::$app->defaultMethod;
        }
        $this->handle($urlInfo);
    }

    /**
     * Verify the parameter from the user input of the url- [[PATH-INFO]]
     * @param array $urlInfo
     * @param boolean $checkModule
     * @throws Jframe\exception\ControllerNotFound
     * @throws Jframe\exception\MethodNotFound
     */
    private function handle($urlInfo, $checkModule = true)
    {
        // Check the module is exists or not
        $module = ROOT . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $urlInfo[0];
        Jframe::$app->modulePath = $module;
        // Module exists
        if (is_dir($module)) {
            // After checking the module, checking the controller
            if (!isset($urlInfo[1])) {
                $urlInfo[1] = Jframe::$app->defaultController;
            }
            Jframe::$app->pureController = $urlInfo[1];
            $controllerName = ucwords($urlInfo[1]) . 'Controller';
            $controller = $module . DIRECTORY_SEPARATOR . 'controllers' .
                DIRECTORY_SEPARATOR . $controllerName . '.php';
            if (file_exists($controller)) {
                // Method
                $controllerClassName = str_replace("/", "\\", 'app' . DIRECTORY_SEPARATOR . 'modules' .
                    DIRECTORY_SEPARATOR . $urlInfo[0] . DIRECTORY_SEPARATOR . 'controllers' .
                    DIRECTORY_SEPARATOR . $controllerName);
                $controllerInstance = new \ReflectionClass($controllerClassName);
                if (!isset($urlInfo[2])) {
                    $urlInfo[2] = Jframe::$app->defaultMethod;
                }
                $method = 'action' . ucwords($urlInfo[2]);
                Jframe::$app->pureMethod = $urlInfo[2];
                if ($controllerInstance->hasMethod($method)) {
                    // Invoke the method with the parameter
                    unset($urlInfo[0], $urlInfo[1], $urlInfo[2]);
                    $this->invokeMethodWithParameter($urlInfo, $controllerClassName, $method, $controllerName);
                } else {
                    throw new MethodNotFound("Method [[{$method}]] Not Found In Controller [{$controllerName}]", 102);
                }
            } else {
                throw new ControllerNotFound("Controller [[{$controllerName}]] Not Found!");
            }
        } else {
            // Controller
            Jframe::$app->modulePath = ROOT;
            if (!isset($urlInfo[0])) {
                $urlInfo[0] = Jframe::$app->defaultController;
            }
            Jframe::$app->pureController = $urlInfo[0];
            $controllerName = ucwords($urlInfo[0]) . 'Controller';
            $controller = ROOT . DIRECTORY_SEPARATOR . 'controllers' .
                DIRECTORY_SEPARATOR . $controllerName . '.php';
            if (file_exists($controller)) {
                // fuction and parameter check
                $controllerClassName = str_replace("/", "\\", 'app' .
                    DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controllerName);
                $controllerInstance = new \ReflectionClass($controllerClassName);
                if (!isset($urlInfo[1])) {
                    $urlInfo[1] = Jframe::$app->defaultMethod;
                }
                $method = 'action' . ucwords($urlInfo[1]);
                Jframe::$app->pureMethod = $urlInfo[1];
                if ($controllerInstance->hasMethod($method)) {
                    // invoke it with the parameter
                    unset($urlInfo[0], $urlInfo[1]);
                    $this->invokeMethodWithParameter($urlInfo, $controllerClassName, $method, $controllerName);
                } else {
                    throw new MethodNotFound("Method [{$method}] Not Found In Controller [{$controllerName}]", 102);
                }
            } else {
                throw new ControllerNotFound("Controller [{$controllerName}] Not Found!");
            }
        }
    }

    /**
     * Check the parameter from the user input and check, then invoke the method with the parameter
     * @throws \Jframe\exception\ParameterNotMatch
     */
    private function invokeMethodWithParameter($urlInfo, $controllerClassName, $method, $controllerName)
    {
        $controllerObj = new $controllerClassName();
        // Get the csrf token
        if ($controllerObj->enableCsrfFilter) {
            $csrfToken = Jframe::$app->request->getCsrfToken();
            $data = array_merge(Jframe::$app->request->get(), Jframe::$app->request->post());
            if (!empty($data['_data']) && $data['_data'] == 'data') {
                if (!isset($data['_csrf'])) {
                    throw new CsrfAttackException("Csrf attack exception", 501);
                }
                if (isset($data['_csrf']) && $data['_csrf'] !== $csrfToken) {
                    throw new CsrfAttackException("Csrf attack exception", 501);
                }
            }
        }

        $controllerObj->beforeAction();
        // Some variables in the Jframe
        $controllerObj->id = $controllerName;
        Jframe::$app->viewId = $method;
        Jframe::$app->context = $controllerObj;
        // Do the pre-invoke
        // Before doing the invoke
        // Check the function which you has the privileges to execute
        $behaviors = $controllerObj->behaviors();
        if (!empty($behaviors)) {
            // To deal with the actions
            if (isset($behaviors['verbs']['actions'])) {
                // Execute the verb filter before the function invoke
                foreach ($behaviors['verbs']['actions'] as $k => $v) {
                    $v = array_map(['\Jframe\base\UrlManager', 'changeLowerArray'], $v);
                    if (strcmp('action' . ucwords($k), $method) == 0) {
                        $requestMethod = Jframe::$app->request->getMethod();
                        if (!in_array(strtolower($requestMethod), $v)) {
                            throw new VerbsNotAllowed("Request Method [[{$requestMethod}]] Not Allowed!", 103);
                        }
                    }
                }
            }
            // To deal the access controller
            if (isset($behaviors['access'])) {
                if (!isset($behaviors['access']['class'])) {
                    throw new ParameterNotMatch("Paramter [[class]] must be set!", 106);
                }
                $className = $behaviors['access']['class'];
                unset($behaviors['access']['class']);
                $instance = Jframe::createObject($className, $behaviors['access']);
                $instance->init($controllerObj, strtolower(ucwords(substr($method, 6))));
            }
        }
        // After check the ver filter continue the body action
        $controllerInstanceRef = new \ReflectionMethod($controllerClassName, $method);
        $pvAssoc = [];
        $passParam = [];
        $realP = $controllerInstanceRef->getParameters();
        $urlInfo = array_merge($urlInfo, []);
        foreach ($urlInfo as $k => $v) {
            if ($k % 2 == 0) {
                $pvAssoc[$urlInfo[$k]] = '';
            } else {
                $pvAssoc[$urlInfo[$k - 1]] = $v;
            }
        }
        foreach ($realP as $v) {
            if ($v->isDefaultValueAvailable()) {
                $tmpV = $v->getDefaultValue();
            }
            if (!array_key_exists($v->name, $pvAssoc) && ($v->isDefaultValueAvailable() == FALSE)) {
                throw new ParameterNotMatch("Parameter [[{$v->name}]] Not Match!", 103);
            }
            if (array_key_exists($v->name, $pvAssoc)) {
                $tmpV = $pvAssoc[$v->name];
            }
            $passParam[] = $tmpV;
        }
        // Invoke the function
        $result = $controllerInstanceRef->invokeArgs($controllerObj, $passParam);
        // Do something if you want to change the data of the code
        $response = Jframe::$app->response;
        $response->data = $result;
        $response->formatOut();
        $controllerObj->afterAction();
        exit(0);
    }

}
