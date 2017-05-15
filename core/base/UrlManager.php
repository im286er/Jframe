<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jframe\base;

use Jframe;

class UrlManager extends Object
{

    /**
     * dealUrl to deal with the request from the user
     */
    public function dealUrl()
    {
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
     * Verify the parameter from the user input of the url- PATH-INFO
     * @param type $urlInfo
     * @param type $checkModule
     * @throws \Jframe\exception\ParameterNotMatch
     * @throws \Jframe\exception\MethodNotFound
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
                $controllerClassName = 'app' . DIRECTORY_SEPARATOR . 'modules' .
                        DIRECTORY_SEPARATOR . $urlInfo[0] . DIRECTORY_SEPARATOR . 'controllers' .
                        DIRECTORY_SEPARATOR . $controllerName;
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
                    throw new \Jframe\exception\MethodNotFound("Method [[{$method}]] Not Found In Controller [{$controllerName}]", 102);
                }
            } else {
                throw new \Jframe\exception\ControllerNotFound("Controller [[{$controllerName}]] Not Found!");
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
                $controllerClassName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controllerName;
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
                    throw new \Jframe\exception\MethodNotFound("Method [{$method}] Not Found In Controller [{$controllerName}]", 102);
                }
            } else {
                throw new \Jframe\exception\ControllerNotFound("Controller [{$controllerName}] Not Found!");
            }
        }
    }
    
    private static function changeLowerArray($value)
    {
        return strtolower($value);
    }

    /**
     * Check the parameter from the user input and check, then invoke the method with the parameter
     * @throws \Jframe\exception\ParameterNotMatch
     */
    private function invokeMethodWithParameter($urlInfo, $controllerClassName, $method, $controllerName)
    {
        $controllerObj = new $controllerClassName();
        // Some variables in the Jframe
        $controllerObj->id = $controllerName;
        Jframe::$app->viewId = $method;
        Jframe::$app->context = $controllerObj;
        // Do the pre-invoke
        // Before doing the invoke
        // Check the function which you has the privileges to execute
        $behaviors = $controllerObj->behaviors();
        if (!empty($behaviors)) {
            if (isset($behaviors['verbs']['actions'])) {
                // Execute the verb filter before the function invoke
                foreach ($behaviors['verbs']['actions'] as $k => $v) {
                    $v = array_map(['\Jframe\base\UrlManager', 'changeLowerArray'], $v);
                    if (strcmp('action' . ucwords($k), $method) == 0) {
                        $requestMethod = Jframe::$app->request->getMethod();
                        if (!in_array(strtolower($requestMethod), $v)) {
                            throw new Jframe\exception\VerbsNotAllowed("Request Method [[{$requestMethod}]] Not Allowed!", 103);
                        }
                    }
                }
            }
        }
        // After check the ver fileter continue the body action
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
                throw new \Jframe\exception\ParameterNotMatch("Parameter [[{$v->name}]] Not Match!", 103);
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
        header('Status Code :' . Jframe::$app->request->statusCode);
        switch ($response->format)
        {
            case Response::FORMAT_RAW:
                return $response->formatOut();
                break;
            case Response::FORMAT_JSON:
                return $response->formatOut();
                break;
            case Response::FORMAT_XML:
                return $response->formatOut();
                break;
        }
        // After doing the normal thing in the body action, do the ending jobs
        // Adding some code below
        // Terminate the application with a standard lifestyle
        exit(0);
    }

}
