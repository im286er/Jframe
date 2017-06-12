<?php

/**
 * This is the AppAsset of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\helpers;

class AppAsset extends Assets
{

    /**
     * The Javascript file which the Jframe will load default
     * @var array
     */
    public static $js = [
        'js/jquery-1.12.4.min.js',
        'js/bootstrap.min.js',
        'Validator/js/bootstrapValidator.min.js',
    ];

    /**
     * The css file needed by the javascript, or other HTML, loaded default
     * @var array
     */
    public static $css = [
        'css/bootstrap.min.css',
        'Validator/css/bootstrapValidator.min.css'
    ];

}
