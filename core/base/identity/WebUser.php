<?php

/**
 * This is the bootstrap setting of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\base\identity;

class WebUser
{

    /**
     * The User's identity Object, Which can be used in the app's runtime life style
     * @var \Jframe\base\identity\WebUser $identity 
     */
    public $identity = null;

    /**
     * @return boolean returns true means the user if guest, otherwise false
     */
    public function getIsGuest()
    {
        if (is_null($this->identity)) {
            return true;
        }
        return false;
    }

    /**
     * Login the user to let the identity procedure works well
     * @param \Jframe\base\identity\WebUser $webUser
     * @return boolean True means login success, otherwise false
     */
    public function login($webUser)
    {
        if (is_object($webUser) && !is_null($webUser)) {
            $this->identity = $webUser;
        } else {
            return false;
        }
        return true;
    }

    /**
     * Logout the user
     * @return boolean true logout success, otherwise false
     */
    public function logout()
    {
        $this->identity = null;
        return true;
    }

}
