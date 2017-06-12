<?php

/**
 * This is the bootstrap setting of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\behavior;

use Jframe\exception\GuestNotAllowedException;
use Jframe\exception\MethodNotFound;

/**
 * The Access controller in order to help improve the identity to access
 */
class AccessFilter extends Filter
{

    /**
     * @var array $only Only to controller which method, It may be a array
     * eg.
     * [
     *    'index', 'update'
     * ]
     * It means to control the method `index` `update`, other method will pass through
     */
    public $only = [];

    /**
     * @var array $rules The rules for the access to do
     * eg.
     * [
     *  [
     *    'verbs'=>['post', 'get'],
     *    'roles'=['@']
     *  ],
     *  [
     *     'allow'=>false,
     *     'verbs'=>['post', 'get']
     *  ]
     * ]
     */
    public $rules = [];

    /**
     * To do the real work for the Access Filter do
     * eg.
     * @param \Jframe\base\Controller $object The controller instance
     * @param string $method The controller's current calling method
     * @throws \Jframe\exception\GuestNotAllowedException
     * @throws \Jframe\exception\MethodNotFound
     */
    public function init($object, $method)
    {
        if (!empty($this->only)) {
            if (in_array($method, $this->only)) {
                // Exists means to deal with the next rules
                foreach ($this->rules as $value) {
                    // Each rule's attributes
                    $allow = $value['allow'];
                    if (isset($value['verbs'])) {
                        // To apply those verbs
                        $verbs = array_map([$this, 'arrayToUpper'], $value['verbs']);
                        $verb = \Jframe::$app->request->getMethod();
                        if (in_array($verb, $verbs)) {
                            // check wheather allow or not
                            if (!$allow) {
                                throw new MethodNotFound("Request Method [[{$verb}]] Not Allowed", 107);
                            }
                        }
                    }
                    if (isset($value['roles'])) {
                        $roles = $value['roles'];
                        if ($roles == '?') {
                            if (\Jframe::$app->user->getIsGuest()) {
                                if (!$allow) {
                                    throw new GuestNotAllowedException("[[Guest]] Not Allowed!", 108);
                                }
                            }
                        }
                        if ($roles == '@') {
                            if (\Jframe::$app->user->getIsGuest()) {
                                throw new GuestNotAllowedException("[[Guest]] Not Allowed!", 108);
                            } else {
                                if (!$allow) {
                                    throw new GuestNotAllowedException("[[Guest]] Not Allowed!", 108);
                                }
                            }
                        }
                    }
                }
            }
            // Not exists means to pass it to the regular step
        }
    }

}
