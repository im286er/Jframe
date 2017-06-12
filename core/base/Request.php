<?php

/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\base;

use Jframe;
/**
 * The Request component for the Jframe
 * You can access *Request* use Jframe::$app->request to get the Request instance
 */
class Request extends Object
{

    /**
     * @var strng csrfToken To avoid the csrf attack
     */
    public static $csrfToken = '';

    /**
     * @return strng Getting the csrf token
     */
    public function getCsrfToken()
    {
        session_start();
        return isset($_SESSION['_csrfToken']) ? $_SESSION['_csrfToken'] : '';
    }

    /**
     * @return strng|string
     */
    public function setCsrfToken()
    {
        $_SESSION['_csrfToken'] = hash_hmac('sha1', time(), 'Jframe', false);
        return $_SESSION['_csrfToken'];
    }

    /**
     * Return the POST data from the user input
     * @param string $key
     * @param mixed $defaultValue
     * @return array|string|integer|mixed
     */
    public function post($key = '', $defaultValue = null)
    {
        if (empty($key)) {
            return $_POST;
        } else {
            if (array_key_exists($key, $_POST)) {
                $result = $_POST[$key];
                if (empty($result)) {
                    return $defaultValue;
                } else {
                    return $result;
                }
            }
        }
    }

    /**
     * Return the $_GET data from the user input
     * @param string $key
     * @param mixed $defaultValue
     * @return array|string|integer|mixed
     */
    public function get($key = '', $defaultValue = null)
    {
        if (empty($key)) {
            return $_GET;
        } else {
            if (array_key_exists($key, $_GET)) {
                $result = $_GET[$key];
                if (empty($result)) {
                    return $defaultValue;
                } else {
                    return $result;
                }
            }
        }
    }

    /**
     * Returns the method of the current request (e.g. GET, POST, HEAD, PUT, PATCH, DELETE).
     * @return string request method, such as GET, POST, HEAD, PUT, PATCH, DELETE.
     * The value returned is turned into upper case.
     */
    public function getMethod()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }

        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

}
