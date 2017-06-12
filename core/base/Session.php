<?php

namespace Jframe\base;

class Session
{

    /**
     * Constructing a Session object and start the session
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Setting the session
     * @param string $sessionKey
     * @param mixed $sessionValue
     */
    public function setSession($sessionKey, $sessionValue, $overWrite = false)
    {
        if (!isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = $sessionValue;
            return true;
        } else {
            if ($overWrite) {
                $_SESSION[$sessionKey] = $sessionValue;
                return true;
            }
            return false;
        }
    }

    /**
     * Return the session value from the session
     * @param type $sessionKey
     * @param type $defaultValue
     * @return type
     */
    public function getSession($sessionKey, $defaultValue = null)
    {
        if (isset($_SESSION[$sessionKey])) {
            return $_SESSION[$sessionKey];
        } else {
            return $defaultValue;
        }
    }

}
